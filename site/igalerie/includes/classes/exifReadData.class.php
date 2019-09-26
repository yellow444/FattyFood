<?php
/**
 * PHP Class to read EXIF information
 * that most of the digital camera produce
 *
 * This class is based on jhead (in C) by Matthias Wandel
 *
 * Vinay Yadav (vinayRas) < vinay@sanisoft.com >
 * http://www.sanisoft.com/phpexifrw/
 *
 * For more information on EXIF
 * http://www.exif.org/
 *
 *
 *
 * @author Vinay Yadav (vinayRas) < vinay@sanisoft.com >
 *
 * @version 0.5
 * @licence http://opensource.org/licenses/lgpl-license.php GNU LGPL
 *
 *
 * Code remanié et adapté pour iGalerie.
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 *
 */
class exifReadData
{
	public $imageInfo = array();

	private $_currSection = 0;
	private $_motorolaOrder = 0;
	private $_sections = array();
	private $_tags = array(
		0x010F => array('IFD0', 'Make'),
		0x0110 => array('IFD0', 'Model'),
		0x0112 => array('IFD0', 'Orientation'),
		0x011A => array('IFD0', 'XResolution'),
		0x011B => array('IFD0', 'YResolution'),
		0x0128 => array('IFD0', 'ResolutionUnit'),
		0x0131 => array('IFD0', 'Software'),
		0x013B => array('IFD0', 'Artist'),
		0x8298 => array('IFD0', 'Copyright'),
		0x829A => array('EXIF', 'ExposureTime'),
		0x829D => array('EXIF', 'FNumber'),
		0x8822 => array('EXIF', 'ExposureProgram'),
		0x8827 => array('EXIF', 'ISOSpeedRatings'),
		0x9000 => array('EXIF', 'ExifVersion'),
		0x9003 => array('EXIF', 'DateTimeOriginal'),
		0x9004 => array('EXIF', 'DateTimeDigitized'),
		0x9204 => array('EXIF', 'ExposureBiasValue'),
		0x9206 => array('EXIF', 'SubjectDistance'),
		0x9209 => array('EXIF', 'Flash'),
		0x920A => array('EXIF', 'FocalLength'),
		0x9205 => array('EXIF', 'MaxApertureValue'),
		0x9207 => array('EXIF', 'MeteringMode'),
		0x9208 => array('EXIF', 'LightSource'),
		0xA000 => array('EXIF', 'FlashPixVersion'),
		0xA001 => array('EXIF', 'ColorSpace'),
		0xA301 => array('EXIF', 'SceneType'),
		0xA217 => array('EXIF', 'SensingMethod'),
		0xA401 => array('EXIF', 'CustomRendered'),
		0xA402 => array('EXIF', 'ExposureMode'),
		0xA403 => array('EXIF', 'WhiteBalance'),
		0xA404 => array('EXIF', 'DigitalZoomRatio'),
		0xA405 => array('EXIF', 'FocalLengthIn35mmFilm'),
		0xA406 => array('EXIF', 'SceneCaptureType'),
		0xA407 => array('EXIF', 'GainControl'),
		0xA408 => array('EXIF', 'Contrast'),
		0xA409 => array('EXIF', 'Saturation'),
		0xA40A => array('EXIF', 'Sharpness'),
		0xA40C => array('EXIF', 'SubjectDistanceRange'),
		0xA432 => array('EXIF', 'UndefinedTag:0xA432'),
		0xA433 => array('EXIF', 'UndefinedTag:0xA433'),
		0xA434 => array('EXIF', 'UndefinedTag:0xA434')
	);

	public function __construct($file = '')
	{
		$this->_processFile($file);
	}

	private function _convertAnyFormat($value_ptr, $format)
	{
		$value = 0;

		switch($format)
		{
			case '1' :
			case '6' :
				$value = $value_ptr[0];
				break;			

			case '3' :
				$value = $this->_get16u($value_ptr[0], $value_ptr[1]);
				break;

			case '4' :
				$value = $this->_get32u(
					$value_ptr[0], $value_ptr[1], $value_ptr[2], $value_ptr[3]
				);
				break;

			case '5' :
			case '10' :
				$num = $this->_get32s(
					$value_ptr[0], $value_ptr[1], $value_ptr[2], $value_ptr[3]
				);
				$den = $this->_get32s(
					$value_ptr[4], $value_ptr[5], $value_ptr[6], $value_ptr[7]
				);
				$value = ($den == 0) ? 0 : (double) ($num / $den);
				return array($value, array($num, $den));

			case '8' :
				$value = $this->_get16u($value_ptr[0], $value_ptr[1]);
				break;

			case '9' :
				$value = $this->_get32s(
					$value_ptr[0], $value_ptr[1], $value_ptr[2], $value_ptr[3]
				);
				break;

			case '11' :
				$value = $value_ptr[0];
				break;

			case '12' :
				$value = $value_ptr[0];
				break;
		}

		return $value;
	}

	private function _get16u($val, $by)
	{
		if ($this->_motorolaOrder)
		{
			return ((ord($val) << 8) | ord($by));
		}
		else
		{
			return ((ord($by) << 8) | ord($val));
		}
	}

	private function _get32s($val1, $val2, $val3, $val4)
	{
		$val1 = ord($val1);
		$val2 = ord($val2);
		$val3 = ord($val3);
		$val4 = ord($val4);

		if ($this->_motorolaOrder)
		{
			return (($val1 << 24) | ($val2 << 16) | ($val3 << 8 ) | ($val4 << 0 ));
		}
		else
		{
			return  (($val4 << 24) | ($val3 << 16) | ($val2 << 8 ) | ($val1 << 0 ));
		}
	}

	private function _get32u($val1, $val2, $val3, $val4)
	{
		return ($this->_get32s($val1, $val2, $val3, $val4) & 0xffffffff);
	}

	private function _processExif($data, $length)
	{
		if (($data[8] . $data[9]) == 'II')
		{
			$this->_motorolaOrder = 0;
		}
		else if (($data[8] . $data[9]) == 'MM')
		{
			$this->_motorolaOrder = 1;
		}
		else
		{
			return;
		}

		$this->_processExifDir(substr($data, 16), substr($data, 8), $length);
	}

	private function _processExifDir($dir_start, $offset_base, $exif_length)
	{
		$num_dir_entries = 0;
		$bytes_per_format = array(0,1,1,2,4,8,1,1,2,4,8,4,8);
		$value_ptr = array();

		$num_dir_entries = $this->_get16u($dir_start[0], $dir_start[1]);

		for ($de = 0; $de < $num_dir_entries; $de++)
		{
			$dir_entry = substr($dir_start, 2 + 12 * $de);

			if (!isset($dir_entry[0]) || !isset($dir_entry[1])
			 || !isset($dir_entry[2]) || !isset($dir_entry[3]))
			{
				continue;
			}
			$tag = $this->_get16u($dir_entry[0], $dir_entry[1]);
			$format = $this->_get16u($dir_entry[2], $dir_entry[3]);

			if (!isset($dir_entry[4]) || !isset($dir_entry[5])
			 || !isset($dir_entry[6]) || !isset($dir_entry[7]))
			{
				continue;
			}
			$components = $this->_get32u($dir_entry[4],
				$dir_entry[5], $dir_entry[6], $dir_entry[7]);

			if (!isset($bytes_per_format[$format]))
			{
				continue;
			}
			$byte_count = $components * $bytes_per_format[$format];

			if ($byte_count > 4)
			{
				if (!isset($dir_entry[8]) || !isset($dir_entry[9])
				 || !isset($dir_entry[10]) || !isset($dir_entry[11]))
				{
					continue;
				}
				$offset_val = $this->_get32u($dir_entry[8],
					$dir_entry[9], $dir_entry[10], $dir_entry[11]);
				$value_ptr = substr($offset_base, $offset_val);
			}
			else
			{
				$value_ptr = substr($dir_entry, 8);
			}

			switch ($tag)
			{
				case 0x010F : # Make
				case 0x0110 : # Model
				case 0x0131 : # Software
				case 0x013B : # Artist
				case 0x8298 : # Copyright
				case 0x9000 : # ExifVersion
				case 0x9003 : # DateTimeOriginal
				case 0x9004 : # DateTimeDigitized
				case 0xA000 : # FlashPixVersion
				case 0xA432 : # UndefinedTag:0xA432
				case 0xA433 : # UndefinedTag:0xA433
				case 0xA434 : # UndefinedTag:0xA434
					$this->imageInfo[$this->_tags[$tag][0]][$this->_tags[$tag][1]]
						= $this->_stringFormat(substr($value_ptr, 0, $byte_count));
					break;

				case 0x0112 : # Orientation
				case 0x0128 : # ResolutionUnit
				case 0x8822 : # ExposureProgram
				case 0x8827 : # ISOSpeedRatings
				case 0x9207 : # MeteringMode
				case 0x9208 : # LightSource
				case 0x9209 : # Flash
				case 0xA001 : # ColorSpace
				case 0xA301 : # SceneType
				case 0xA217 : # SensingMethod
				case 0xA401 : # CustomRendered
				case 0xA402 : # ExposureMode
				case 0xA403 : # WhiteBalance
				case 0xA405 : # FocalLengthIn35mmFilm
				case 0xA406 : # SceneCaptureType
				case 0xA407 : # GainControl
				case 0xA408 : # Contrast
				case 0xA409 : # Saturation
				case 0xA40A : # Sharpness
				case 0xA40C : # SubjectDistanceRange
					$this->imageInfo[$this->_tags[$tag][0]][$this->_tags[$tag][1]]
						= $this->_convertAnyFormat($value_ptr, $format);
					break;

				case 0x011A : # XResolution
				case 0x011B : # YResolution
				case 0x829A : # ExposureTime
				case 0x829D : # FNumber
				case 0x9204 : # ExposureBiasValue
				case 0x9205 : # MaxApertureValue
				case 0x9206 : # SubjectDistance
				case 0x920A : # FocalLength
				case 0xA404 : # DigitalZoomRatio
					if (isset($this->imageInfo[$this->_tags[$tag][0]][$this->_tags[$tag][1]]))
					{
						break;
					}
					$tmp = $this->_convertAnyFormat($value_ptr, $format);
					$this->imageInfo[$this->_tags[$tag][0]][$this->_tags[$tag][1]]
						= $tmp[1][0] . '/' . $tmp[1][1];
					break;

				case 0x8769 :
				case 0xA005 :
					$sub_dir_start = substr($offset_base, $this->_get32u($value_ptr[0],
						$value_ptr[1], $value_ptr[2], $value_ptr[3]));
					$this->_processExifDir($sub_dir_start, $offset_base, $exif_length);
					break;
			}
		}

		$tmp_dir_start = substr($dir_start, 2 + 12 * $num_dir_entries);
		if (strlen($tmp_dir_start) + 4 <= strlen($offset_base) + $exif_length)
		{
			if (!isset($tmp_dir_start[0]) || !isset($tmp_dir_start[1])
			 || !isset($tmp_dir_start[2]) || !isset($tmp_dir_start[3]))
			{
				return;
			}
			$offset = $this->_get32u($tmp_dir_start[0], $tmp_dir_start[1],
				$tmp_dir_start[2], $tmp_dir_start[3]);
			if ($offset)
			{
				$sub_dir_start = substr($offset_base, $offset);
				if (strlen($sub_dir_start) > strlen($offset_base) + $exif_length)
				{
				}
				else
				{
					if (strlen($sub_dir_start) <= strlen($offset_base) + $exif_length)
					{
						$this->_processExifDir($sub_dir_start, $offset_base, $exif_length);
					}
				}
			}
		}
	}

	private function _processFile($file)
	{
		if (($fp = fopen($file, 'rb')) === FALSE)
		{
			return FALSE;
		}

		$a = fgetc($fp);

		if (ord($a) != 0xff || ord(fgetc($fp)) != '0xD8')
		{
			return FALSE;
		}

		while (!feof($fp))
		{
			$data = array();
			for ($a = 0; $a < 7; $a++)
			{
				$marker = fgetc($fp);
				if (ord($marker) != 0xff)
				{
					break;
				}
				if ($a >= 6)
				{
					return FALSE;
				}
			}

			$marker = ord($marker);
			$this->_sections[$this->_currSection]['type'] = $marker;
			$lh = ord(fgetc($fp));
			$ll = ord(fgetc($fp));
			$itemlen = ($lh << 8) | $ll;
			$this->_sections[$this->_currSection]['size'] = $itemlen;

			if ($itemlen - 2 < 1)
			{
				continue;
			}

			$tmp_str = fread($fp, $itemlen - 2);
			$data = chr($lh) . chr($ll) . $tmp_str;
			$this->_currSection++;

			if ($marker == '0xE1')
			{
				if ((3 & 1) && ($data[2] . $data[3] . $data[4] . $data[5]) == 'Exif')
				{
					$this->_processExif($data, $itemlen);
				}
				else
				{
					$this->_sections[--$this->_currSection]['data'] = '';
				}
			}
		}

		fclose($fp);

		return TRUE;
	}

	private function _stringFormat($str)
	{
		$tmpstr = '';
		for ($i = 0; $i < strlen($str); $i++)
		{
			if (ord($str[$i]) != 0)
			{
				$tmpstr .= $str[$i];
			}
		}
		return $tmpstr;
	}
}
?>