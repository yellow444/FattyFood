<?php
/**
 * Gestion des métadonnées.
 * Permet d'extraire et de formater les informations EXIF, IPTC et XMP.
 *
 * Spécifications EXIF :
 *  http://www.cipa.jp/english/hyoujunka/kikaku/pdf/DC-008-2010_E.pdf (version 2.3)
 *	http://www.exif.org/Exif2-2.PDF (version 2.2)
 * Spécifications IPTC :
 *	http://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata%28200907%29_1.pdf
 * Spécifications XMP :
 *	http://www.adobe.com/devnet/xmp.html
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class metadata
{
	/**
	 * Données EXIF formatées.
	 *
	 * @var array
	 */
	public $exif = array();

	/**
	 * Indique s'il faut corriger les données EXIF récupérées
	 * par exif_read_data() avec la classe exifReadData.
	 *
	 * @var boolean
	 */
	public $exifCorrect = TRUE;

	/**
	 * Données EXIF brutes.
	 *
	 * @var array
	 */
	public $exifData;

	/**
	 * Date de création (EXIF).
	 *
	 * @var string
	 */
	public $exifDateTimeOriginal;

	/**
	 * Latitude GPS (EXIF).
	 *
	 * @var string
	 */
	public $exifGPSLatitude;

	/**
	 * Longitude GPS (EXIF).
	 *
	 * @var string
	 */
	public $exifGPSLongitude;

	/**
	 * Fabriquant (EXIF).
	 *
	 * @var string
	 */
	public $exifMake;

	/**
	 * Modèle de l'appareil (EXIF).
	 *
	 * @var string
	 */
	public $exifModel;

	/**
	 * Orientation (EXIF).
	 *
	 * @var integer
	 */
	public $exifOrientation;

	/**
	 * Chemin du fichier.
	 *
	 * @var string
	 */
	public $filePath;

	/**
	 * Type du fichier.
	 *
	 * @var string
	 */
	public $fileType;

	/**
	 * Informations utiles de l'image.
	 *
	 * @var array
	 */
	public $imageInfos;

	/**
	 * Données IPTC formatées.
	 *
	 * @var array
	 */
	public $iptc = array();

	/**
	 * Données IPTC brutes.
	 *
	 * @var array
	 */
	public $iptcData;

	/**
	 * Description (IPTC).
	 *
	 * @var string
	 */
	public $iptcDescription;

	/**
	 * Mots-clés (IPTC).
	 *
	 * @var array
	 */
	public $iptcKeywords;

	/**
	 * Titre (IPTC).
	 *
	 * @var string
	 */
	public $iptcTitle;

	/**
	 * Données XMP formatées.
	 *
	 * @var array
	 */
	public $xmp = array();

	/**
	 * Date de création (XMP).
	 *
	 * @var string
	 */
	public $xmpCrtdt;

	/**
	 * Données XMP brutes.
	 *
	 * @var string
	 */
	public $xmpData;

	/**
	 * Description (XMP).
	 *
	 * @var string
	 */
	public $xmpDescription;

	/**
	 * Mots-clés (XMP).
	 *
	 * @var array
	 */
	public $xmpKeywords;

	/**
	 * Orientation (XMP).
	 *
	 * @var integer
	 */
	public $xmpOrientation;

	/**
	 * Titre (XMP).
	 *
	 * @var string
	 */
	public $xmpTitle;



	/**
	 * Initialisation.
	 *
	 * @param array $image_infos
	 *	Informations utiles de l'image.
	 * @param string $file
	 *	Chemin du fichier à examiner.
	 * @return void
	 */
	public function __construct($file = NULL, &$image_infos = NULL)
	{
		$this->imageInfos =& $image_infos;

		// S'il existe un backup de l'image,
		// on utilise ce fichier pour récupérer les métadonnées.
		// Ceci à cause d'éventuelles transformations effectuées avec GD
		// (rotation, etc) qui font perdre les métadonnées.
		if ($file === NULL
		 && isset($image_infos['image_adddt']) && isset($image_infos['image_id']))
		{
			$backup_file = GALLERY_ROOT . '/' . img::filepath('im_backup',
				$image_infos['image_path'], $image_infos['image_id'],
				$image_infos['image_adddt']);
			if (file_exists($backup_file))
			{
				$file = $backup_file;
			}
		}

		if ($file === NULL && isset($image_infos['image_path']))
		{
			$file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $image_infos['image_path'];
		}

		if (!file_exists($file))
		{
			return;
		}

		$this->filePath = $file;
		$this->fileType = ($i = getimagesize($file)) ? $i[2] : 0;
	}

	/**
	 * Retourne la marque d'un appareil à partir
	 * de l'information EXIF $exif_make.
	 *
	 * @param string $exif_make
	 * @return string
	 */
	public function getCameraMake($exif_make)
	{
		if (utils::isEmpty($exif_make)
		|| preg_match('`^\s*(digital camera|unknown)\s*$`i', $exif_make))
		{
			return;
		}

		$brands = array(
			'acer' => 'Acer',
			'aigo' => 'Aigo',
			'apple' => 'Apple',
			'benq' => 'BenQ',
			'blackberry' => 'BlackBerry',
			'canon' => 'Canon',
			'casio' => 'Casio',
			'concord' => 'Concord',
			'dji' => 'DJI',
			'docomo' => 'DoCoMo',
			'epson' => 'Epson',
			'fuji' => 'Fujifilm',
			'gopro' => 'GoPro',
			'hasselblad' => 'Hasselblad',
			'helio' => 'Helio',
			'hewlett-packard|hp' => 'Hewlett Packard',
			'htc' => 'HTC',
			'huawei' => 'Huawei',
			'jvc' => 'JVC',
			'kddi' => 'KDDI',
			'kodak' => 'Kodak',
			'kyocera' => 'Kyocera',
			'leaf' => 'Leaf',
			'leica' => 'Leica',
			'lg' => 'LG',
			'olympus' => 'Olympus',
			'meizu' => 'Meizu',
			'minolta' => 'Konica Minolta',
			'motorola' => 'Motorola',
			'nokia' => 'Nokia',
			'nikon' => 'Nikon',
			'oppo' => 'Oppo',
			'palm' => 'Palm',
			'panasonic' => 'Panasonic',
			'pentax' => 'Pentax',
			'phase one' => 'Phase One',
			'polaroid' => 'Polaroid',
			'ricoh' => 'Ricoh',
			'samsung' => 'Samsung',
			'sanyo' => 'Sanyo',
			'sharp' => 'Sharp',
			'sigma' => 'Sigma',
			'sony ericsson' => 'Sony Ericsson',
			'sony' => 'Sony',
			'toshiba' => 'Toshiba',
			'vivitar' => 'Vivitar',
			'vivo' => 'Vivo',
			'xiaomi' => 'Xiaomi'
		);

		foreach ($brands as $regex => &$brand)
		{
			if (preg_match('`' . $regex . '`i', $exif_make))
			{
				return $brand;
			}
		}

		return $exif_make;
	}

	/**
	 * Récupère les informations Exif formatées.
	 *
	 * @param array $exif_data
	 * @return void
	 */
	public function getExif($exif_data = NULL)
	{
		if (is_array($exif_data))
		{
			$this->exifData = $exif_data;
		}
		if (!$this->_getExifData())
		{
			return;
		}

		if (!is_array(utils::$config['exif_order']))
		{
			utils::$config['exif_order'] = unserialize(utils::$config['exif_order']);
			utils::$config['exif_params'] = unserialize(utils::$config['exif_params']);
		}

		foreach (utils::$config['exif_order'] as &$info)
		{
			if (utils::$config['exif_params'][$info]['status'] == 1)
			{
				$formated = $this->_getExifFormatedInfo($info);
				if (!is_array($formated))
				{
					continue;
				}

				$this->exif[$info] = array(
					'name' => $formated['name'],
					'value' => utils::UTF8($formated['value'])
				);
			}
		}

		// Si le fabriquant et le modèle de l'appareil sont trouvés,
		// on récupère les informations utiles de ces éléments en base de données.
		if (isset($this->exif['Make'])
		 && isset($this->exif['Model']))
		{
			$sql = 'SELECT cam_b.camera_brand_id,
						   cam_b.camera_brand_url,
						   cam_m.camera_model_id,
						   cam_m.camera_model_url
					  FROM ' . CONF_DB_PREF . 'cameras_models AS cam_m
				 LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands AS cam_b
						ON cam_m.camera_brand_id = cam_b.camera_brand_id
				 LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_mi
						ON cam_m.camera_model_id = cam_mi.camera_model_id
				 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
						ON cam_mi.image_id = img.image_id
					 WHERE img.image_id = ' . (int) $this->imageInfos['image_id'];
			if (utils::$db->query($sql, 'row') !== FALSE
			 && utils::$db->nbResult === 1)
			{
				$this->imageInfos['image_camera'] = utils::$db->queryResult;
			}
		}
	}

	/**
	 * Retourne les informations EXIF destinées
	 * à être enregistrées en base de données.
	 *
	 * @return array|null
	 */
	public function getExifDB()
	{
		if (!$this->_getExifData())
		{
			return;
		}

		$exif = array();

		if (isset($this->exifData['GPS']))
		{
			$exif['GPS'] = $this->exifData['GPS'];
		}
		if (isset($this->exifData['IFD0']))
		{
			$exif['IFD0'] = $this->exifData['IFD0'];
		}
		if (isset($this->exifData['EXIF']))
		{
			$exif['EXIF'] = $this->exifData['EXIF'];
			if (isset($exif['EXIF']['MakerNote']))
			{
				unset($exif['EXIF']['MakerNote']);
			}
		}

		if (empty($exif))
		{
			return;
		}

		utils::UTF8Array($exif);
		$exif = serialize($exif);
		if (is_string($exif) && strlen($exif) < 65536)
		{
			return $exif;
		}
	}

	/**
	 * Récupère les données Exif utilisées pour
	 * remplir les champs de la table des images.
	 *
	 * @return void
	 */
	public function getExifImageFields()
	{
		if (!utils::$config['exif_crtdt']
		 && !utils::$config['exif_camera']
		 && !utils::$config['exif_gps']
		 && !utils::$config['images_orientation'])
		{
			return FALSE;
		}

		// Récupération des données brutes.
		$this->_getExifData();

		if (!is_array($this->exifData))
		{
			return FALSE;
		}

		// Date et heure de la prise de vue.
		if (utils::$config['exif_crtdt']
		&& (!empty($this->exifData['EXIF']['DateTimeOriginal'])
		 || !empty($this->exifData['IFD0']['DateTime'])))
		{
			$datetime = (isset($this->exifData['EXIF']['DateTimeOriginal']))
				? $this->exifData['EXIF']['DateTimeOriginal']
				: $this->exifData['IFD0']['DateTime'];
			if ($datetime = $this->_exifDate($datetime))
			{
				if ($date = $this->_checkDate(date('Y-m-d H:i:s', strtotime($datetime))))
				{
					$this->exifDateTimeOriginal = $date;
				}
			}
		}

		// Fabriquant.
		if (utils::$config['exif_camera']
		&& !empty($this->exifData['IFD0']['Make'])
		&& !utils::isEmpty($this->exifData['IFD0']['Make'])
		&& strtolower($this->exifData['IFD0']['Make']) != 'exif'
		&& preg_match('`[a-z]`i', $this->exifData['IFD0']['Make']))
		{
			$this->exifMake = utils::UTF8(trim($this->exifData['IFD0']['Make']));
		}

		// Modèle de l'appareil.
		if (utils::$config['exif_camera']
		&& !empty($this->exifData['IFD0']['Model'])
		&& !utils::isEmpty($this->exifData['IFD0']['Model'])
		&& preg_match('`[a-z]`i', $this->exifData['IFD0']['Model']))
		{
			$this->exifModel = utils::UTF8(trim($this->exifData['IFD0']['Model']));
		}

		// Coordonnées GPS.
		if (utils::$config['exif_gps']
		&& ($coords = $this->_getGPSCoordinates()) !== FALSE)
		{
			$this->exifGPSLatitude = $coords[0];
			$this->exifGPSLongitude = $coords[1];
		}

		// Orientation.
		if (utils::$config['images_orientation']
		&& isset($this->exifData['IFD0']['Orientation']))
		{
			$orientation = (int) $this->exifData['IFD0']['Orientation'];
			if ($orientation > 1 && $orientation < 9)
			{
				$this->exifOrientation = $orientation;
			}
		}

		return TRUE;
	}

	/**
	 * Localisation du nom des informations Exif.
	 *
	 * @param string $info
	 * @return string
	 */
	public static function getExifLocale($info)
	{
		$params = self::_getExifParams($info);

		return $params['name'];
	}

	/**
	 * Récupère les informations IPTC formatées.
	 *
	 * @param array $iptc_data
	 * @return void
	 */
	public function getIptc($iptc_data = NULL)
	{
		if (is_array($iptc_data))
		{
			$this->iptcData = $iptc_data;
		}
		if (!$this->_getIptcData())
		{
			return;
		}

		if (!is_array(utils::$config['iptc_order']))
		{
			utils::$config['iptc_order'] = unserialize(utils::$config['iptc_order']);
			utils::$config['iptc_params'] = unserialize(utils::$config['iptc_params']);
		}

		foreach (utils::$config['iptc_order'] as &$info)
		{
			if (utils::$config['iptc_params'][$info]['status'] == 1)
			{
				$formated = $this->_getIptcFormatedInfo($info);
				if (!is_array($formated))
				{
					continue;
				}

				$this->iptc[$info] = array(
					'name' => $formated['name'],
					'value' => utils::UTF8($formated['value'])
				);
			}
		}
	}

	/**
	 * Retourne les informations IPTC destinées
	 * à être enregistrées en base de données.
	 *
	 * @return array|null
	 */
	public function getIptcDB()
	{
		if (!$this->_getIptcData())
		{
			return;
		}

		$iptc = $this->iptcData;
		utils::UTF8Array($iptc);
		$iptc = serialize($iptc);
		if (is_string($iptc) && strlen($iptc) < 65536)
		{
			return $iptc;
		}
	}

	/**
	 * Récupère les données IPTC utilisées pour
	 * remplir les champs de la table des images.
	 *
	 * @return boolean
	 */
	public function getIptcImageFields()
	{
		if (!utils::$config['iptc_description']
		 && !utils::$config['iptc_keywords']
		 && !utils::$config['iptc_title'])
		{
			return FALSE;
		}

		// Récupération des données brutes.
		$this->_getIptcData();

		if (!is_array($this->iptcData))
		{
			return FALSE;
		}

		// Mots-clés.
		if (utils::$config['iptc_keywords']
		&& !empty($this->iptcData['2#025']))
		{
			$this->iptcKeywords = $this->iptcData['2#025'];
		}

		// Titre.
		if (utils::$config['iptc_title']
		&& !empty($this->iptcData['2#105'][0]))
		{
			$this->iptcTitle = utils::UTF8(trim($this->iptcData['2#105'][0]));
		}

		// Description.
		if (utils::$config['iptc_description']
		&& !empty($this->iptcData['2#120'][0]))
		{
			$this->iptcDescription = utils::UTF8(trim($this->iptcData['2#120'][0]));
		}

		return TRUE;
	}

	/**
	 * Localisation du nom des informations IPTC.
	 *
	 * @param string $info
	 * @return string
	 */
	public static function getIptcLocale($info)
	{
		return self::_getIptcParams($info);
	}

	/**
	 * Récupère les informations XMP formatées.
	 *
	 * @param array $xmp_data
	 * @return void
	 */
	public function getXmp($xmp_data = NULL)
	{
		if (is_array($xmp_data))
		{
			$this->xmpData = $xmp_data;
		}
		if (!$this->_getXmpData())
		{
			return;
		}

		if (!is_array(utils::$config['xmp_order']))
		{
			utils::$config['xmp_order'] = unserialize(utils::$config['xmp_order']);
			utils::$config['xmp_params'] = unserialize(utils::$config['xmp_params']);
		}

		foreach (utils::$config['xmp_order'] as &$info)
		{
			if (utils::$config['xmp_params'][$info]['status'] == 1)
			{
				$formated = $this->_getXmpFormatedInfo($info);
				if (!is_array($formated))
				{
					continue;
				}

				$this->xmp[$info] = array(
					'name' => $formated['name'],
					'value' => utils::UTF8($formated['value'])
				);
			}
		}
	}

	/**
	 * Retourne les informations XMP destinées
	 * à être enregistrées en base de données.
	 *
	 * @return array|null
	 */
	public function getXmpDB()
	{
		if (!$this->_getXmpData())
		{
			return;
		}

		$xmp = $this->xmpData;
		$xmp = utils::UTF8($xmp);
		if (is_string($xmp) && strlen($xmp) > 20 && strlen($xmp) < 65536)
		{
			return $xmp;
		}
	}

	/**
	 * Récupère les données XMP utilisées pour
	 * remplir les champs de la table des images.
	 *
	 * @return boolean
	 */
	public function getXmpImageFields()
	{
		if (!utils::$config['xmp_crtdt']
		 && !utils::$config['xmp_description']
		 && !utils::$config['xmp_keywords']
		 && !utils::$config['xmp_title']
		 && !utils::$config['images_orientation'])
		{
			return FALSE;
		}

		// Récupération des données brutes.
		$this->_getXmpData();

		if ($this->xmpData === NULL)
		{
			return FALSE;
		}

		// Date de création.
		if (utils::$config['xmp_crtdt'])
		{
			$xmp_crtdt = $this->_getRdfText('exif:DateTimeOriginal');

			if ($xmp_crtdt === NULL)
			{
				$xmp_crtdt = $this->_getRdfText('photoshop:DateCreated');
			}

			if (($xmp_crtdt = strtotime($xmp_crtdt)) !== FALSE)
			{
				if ($date = $this->_checkDate(date('Y-m-d H:i:s', $xmp_crtdt)))
				{
					$this->xmpCrtdt = $date;
				}
			}
		}

		// Description.
		if (utils::$config['xmp_description'])
		{
			$this->xmpDescription = $this->_getRdfAlt('dc:description');
		}

		// Mots-clés.
		if (utils::$config['xmp_keywords'])
		{
			$this->xmpKeywords = $this->_getRdfBag('dc:subject');
		}

		// Titre.
		if (utils::$config['xmp_title'])
		{
			$this->xmpTitle = $this->_getRdfAlt('dc:title');
		}

		// Orientation.
		if (utils::$config['images_orientation'])
		{
			$orientation = (int) $this->_getRdfText('tiff:Orientation');
			if ($orientation > 1 && $orientation < 9)
			{
				$this->xmpOrientation = $orientation;
			}
		}

		return TRUE;
	}

	/**
	 * Localisation du nom des informations XMP.
	 *
	 * @param string $info
	 * @return string
	 */
	public static function getXmpLocale($info)
	{
		$params = self::_getXmpParams($info);

		return $params['name'];
	}



	/**
	 * Vérifie que la date $date est dans un format valide.
	 *
	 * @param array $data
	 * @return boolean|string
	 */
	private function _checkDate($date)
	{
		if (preg_match('`^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$`', $date))
		{
			return $date;
		}

		return FALSE;
	}

	/**
	 * Nettoie les valeurs du tableau $data.
	 *
	 * @param array $data
	 * @return void
	 */
	private function _cleanValues(&$data)
	{
		if (!is_array($data))
		{
			return;
		}
		array_walk_recursive($data,
			function(&$str)
			{
				$str = utils::deleteInvisibleChars($str);
			}
		);
	}

	/**
	 * Vérifie le format d'une date.
	 *
	 * @param string $value
	 * @return string
	 */
	private function _exifDate($value)
	{
		$value = trim($value);

		// \W et \s+ à cause de certains formats de date non valide
		// (Samsung).
		if (substr($value, 0, 4) == '0000'
		|| !preg_match('`^\d{4}(\W)\d{2}\W\d{2}(\s+)\d{2}(\W)\d{2}\W\d{2}$`', $value, $m))
		{
			return;
		}
		$value = str_replace(array($m[1], $m[2], $m[3]), array(':', ' ', ':'), $value);

		if (strtotime($value) === FALSE)
		{
			return;
		}

		return $value;
	}

	/**
	 * Formate une information EXIF avec la méthode "list".
	 *
	 * @param array $list
	 * @param string $value
	 * @return string|boolean
	 */
	private function _exifList($list, $value)
	{
		if (isset($list[$value]))
		{
			return $list[$value];
		}

		return FALSE;
	}

	/**
	 * Formate une information EXIF avec la méthode "number".
	 *
	 * @param string $format
	 * @param string $value
	 * @param boolean $localize
	 * @return string
	 */
	private function _exifNumber($format, $value, $localize = TRUE)
	{
		if (preg_match('`^[-0-9/+\*]{1,255}$`', $value)
		&& substr($value, -2, 2) != '/0')
		{
			eval("\$newval=$value;");

			$value = preg_replace('`\.0+(?=\D|$)`', '', sprintf($format, $newval));
			return $localize ? utils::numeric($value) : $value;
		}
	}

	/**
	 * Formate une information EXIF avec la méthode "version".
	 *
	 * @param string $value
	 * @return string
	 */
	private function _exifVersion($value)
	{
		if (strlen($value) < 5 && is_numeric($value))
		{
			$version = sscanf($value, '%2d%2d');

			return sprintf('%d.%d', $version[0], $version[1]);
		}
	}

	/**
	 * Récupère les données Exif brutes
	 * et les place dans la propriété $exifData.
	 *
	 * @return boolean
	 */
	private function _getExifData()
	{
		// Si les données ont déjà été récupérées, inutile d'aller plus loin.
		if (is_array($this->exifData))
		{
			return TRUE;
		}

		// L'extension Exif doit être chargée.
		if (!function_exists('exif_read_data'))
		{
			return FALSE;
		}

		// Le fichier doit exister !
		if (!file_exists($this->filePath))
		{
			return FALSE;
		}

		// Seules les images JPEG sont concernées.
		if ($this->fileType != 2)
		{
			return FALSE;
		}

		// Récupération des informations avec la fonction native de PHP.
		if (!is_array($exif_data = exif_read_data($this->filePath, 'ANY_TAG', TRUE, FALSE)))
		{
			return FALSE;
		}
		$sections = array('EXIF', 'GPS', 'IFD0');
		foreach ($exif_data as $s => &$arr)
		{
			if (!in_array($s, $sections))
			{
				unset($exif_data[$s]);
			}
		}
		$this->exifData = $exif_data;

		// Correction des données EXIF ?
		if ($this->exifCorrect)
		{
			// Récupération des informations avec la classe exifReadData.
			$exif_data = new exifReadData($this->filePath);
			if (!is_array($exif_data->imageInfo))
			{
				return FALSE;
			}
			$exif_data = $exif_data->imageInfo;

			// On supprime les données à problème.
			// Permet notamment de résoudre le problème lié à Lightroom 6.1.
			if (isset($exif_data['IFD0']['Make']) && isset($this->exifData['IFD0']['Make'])
			&& $this->exifData['IFD0']['Make'] != $exif_data['IFD0']['Make'])
			{
				$this->exifData['IFD0'] = array();
			}

			// On remplit les tags vides ou inexistants récupérés avec
			// exif_read_data() par ceux de la classe exifReadData,
			// s'ils existent.
			foreach ($exif_data as $section => &$infos)
			{
				foreach ($infos as $tag => &$value)
				{
					if (!isset($this->exifData[$section][$tag]))
					{
						$this->exifData[$section][$tag] = $value;
					}
					else if (is_array($this->exifData[$section][$tag]))
					{
						if (empty($this->exifData[$section][$tag]))
						{
							$this->exifData[$section][$tag] = $value;
						}
					}
					else if (utils::isEmpty($this->exifData[$section][$tag]))
					{
						$this->exifData[$section][$tag] = $value;
					}
				}
			}
		}

		// Changement du format de certaines valeurs.
		// Sensibilité ISO.
		if (isset($this->exifData['EXIF']['ISOSpeedRatings'])
		&& is_array($this->exifData['EXIF']['ISOSpeedRatings'])
		&& isset($this->exifData['EXIF']['ISOSpeedRatings'][0]))
		{
			$this->exifData['EXIF']['ISOSpeedRatings']
				= $this->exifData['EXIF']['ISOSpeedRatings'][0];
		}

		// Objectif.
		$lens = '';
		if (isset($this->exifData['EXIF']['UndefinedTag:0xA433']))
		{
			// Fabricant.
			$lens = $this->exifData['EXIF']['UndefinedTag:0xA433'];
		}
		if (isset($this->exifData['EXIF']['UndefinedTag:0xA434']))
		{
			// Modèle.
			$lens .= ($lens === '') ? '' : ' ';
			$lens .= $this->exifData['EXIF']['UndefinedTag:0xA434'];
		}
		else if (isset($this->exifData['EXIF']['UndefinedTag:0xA432'])
		&& is_array($this->exifData['EXIF']['UndefinedTag:0xA432'])
		&& count($this->exifData['EXIF']['UndefinedTag:0xA432']) == 4)
		{
			// Spécifications.
			$specs = $this->exifData['EXIF']['UndefinedTag:0xA432'];
			$lens .= ($lens === '') ? '' : ' ';
			$lens .= $this->_exifNumber('%2.2F', $specs[0], FALSE);
			if ($specs[1] > 0 && $specs[1] != $specs[0])
			{
				$lens .= '-' . $this->_exifNumber('%2.2F', $specs[1], FALSE);
			}
			$lens .= ' mm';
			if ($specs[2] > 0)
			{
				$lens .= ' f/' . $this->_exifNumber('%2.1F', $specs[2], FALSE);
				if ($specs[3] > 0 && $specs[3] != $specs[2])
				{
					$lens .= '-' . $this->_exifNumber('%2.1F', $specs[3], FALSE);
				}
			}
		}
		if ($lens !== '')
		{
			$this->exifData['EXIF']['Lens'] = $lens;
		}

		// Coordonnées GPS.
		if (($coords = $this->_getGPSCoordinates()) !== FALSE)
		{
			$text = '';
			foreach ($coords as $c => $v)
			{
				$ref = (substr($v, 0, 1) == '-')
					? (($c == 0) ? 'S' : 'W')
					: (($c == 0) ? 'N' : 'E');
				$v = preg_replace('`^-`', '', $v);
				$nums = array();
				for ($i = 0; $i < 2; $i++)
				{
					if (!preg_match('`^(\d+).(\d+)$`', $v, $m))
					{
						$text = '';
						continue 2;
					}
					$nums[] = $m[1];
					$v -= $m[1];
					$v *= 60;
				}
				$text .= ($text == '') ? '' : ', ';
				$text .= sprintf('%d° %d\' %2.2F" %s', $nums[0], $nums[1], $v, $ref);
			}
			if ($text !== '')
			{
				$this->exifData['GPS']['GPSCoordinates'] = $text;
			}
		}

		// Nettoyage des données.
		$this->_cleanValues($this->exifData);

		return TRUE;
	}

	/**
	 * Retourne l'information EXIF $info formatée.
	 *
	 * @param string $info
	 * @return array
	 */
	private function _getExifFormatedInfo($info)
	{
		$params = self::_getExifParams($info);

		if (!isset($this->exifData[$params['section']][$info]))
		{
			return;
		}
		$value = $value_original = $this->exifData[$params['section']][$info];
		if (!is_string($value) && !is_numeric($value))
		{
			return;
		}

		// Temps d'exposition.
		if ($info == 'ExposureTime')
		{
			if (!($val = $this->_exifNumber('%s', $value, FALSE)))
			{
				return;
			}

			if ($val < 1)
			{
				$val = explode('/', $value);
				$val = '1/' . round($val[1] / $val[0]);
			}
			else
			{
				$val = utils::numeric(round($val, 2));
			}

			return array(
				'name' => $params['name'],
				'value' => $val . ' s'
			);
		}

		// Autres paramètres.
		switch ($params['method'])
		{
			case 'date' :
				$value = $this->_exifDate($value);
				if ($value)
				{
					$value = utils::localeTime(
						utils::$config['exif_params'][$info]['format'],
						$value
					);
				}
				break;

			case 'list' :
				$value = $this->_exifList($params['list'], $value);
				break;

			case 'number' :
				$value = $this->_exifNumber(
					utils::$config['exif_params'][$info]['format'],
					$value
				);
				break;

			case 'version' :
				$value = $this->_exifVersion($value);
				break;
		}

		if (utils::isEmpty($value))
		{
			return;
		}

		// Changement de certaines valeurs.
		if ($info == 'DigitalZoomRatio' && substr($value_original, 0, 2) == '0/')
		{
			$value = __('Non utilisé');
		}
		if ($info == 'ExposureBiasValue' && substr($value_original, 0, 2) == '0/')
		{
			$value = __('Aucune');
		}
		if ($info == 'SubjectDistance' && $value[0] == '-')
		{
			$value = __('Infinie');
		}
		if ($info == 'Model')
		{
			switch ($value)
			{
				case 'FC200' :
					$value .= ' (Phantom 2 Vision)';
					break;

				case 'FC2103' :
					$value .= ' (Mavic Air)';
					break;

				case 'FC220' :
					$value .= ' (Mavic Pro)';
					break;

				case 'FC300C' :
					$value .= ' (Phantom 3 Standard)';
					break;

				case 'FC300S' :
					$value .= ' (Phantom 3 Professional)';
					break;

				case 'FC300X' :
					$value .= ' (Phantom 3 Advanced)';
					break;

				case 'FC350' :
					$value .= ' (Zenmuse X3)';
					break;

				case 'FC330' :
					$value .= ' (Phantom 4)';
					break;

				case 'FC550' :
					$value .= ' (Zenmuse X5)';
					break;

				case 'FC1102' :
					$value .= ' (Spark)';
					break;

				case 'FC6310' :
					$value .= ' (Phantom 4 Pro)';
					break;

				case 'FC6520' :
					$value .= ' (Zenmuse X5S)';
					break;
			}
		}

		return array(
			'name' => $params['name'],
			'value' => $value
		);
	}

	/**
	 * Retourne les paramètres d'une information EXIF.
	 *
	 * @param string $info
	 * @return string
	 */
	private static function _getExifParams($info)
	{
		static $params;

		if (empty($params))
		{
			$params = array(
				'Artist' => array(
					'name' => __('Auteur'),
					'section' => 'IFD0',
					'method' => 'simple'
				),
				'ColorSpace' => array(
					'name' => __('Espace colorimétrique'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'1' => 'sRGB',
						'2' => 'Adobe RGB (1998)',
						'65535' => __('Non calibré')
					)
				),
				'Contrast' => array(
					'name' => __('Contraste'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Normal'),
						'1' => __('Faible'),
						'2' => __('Fort')
					)
				),
				'Copyright' => array(
					'name' => __('Copyright'),
					'section' => 'IFD0',
					'method' => 'simple'
				),
				'CustomRendered' => array(
					'name' => __('Rendu personnalisé'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Processus normal'),
						'1' => __('Processus personnalisé')
					)
				),
				'DateTimeDigitized' => array(
					'name' => __('Date et heure de la numérisation'),
					'section' => 'EXIF',
					'method' => 'date'
				),
				'DateTimeOriginal' => array(
					'name' => __('Date et heure de la prise de vue'),
					'section' => 'EXIF',
					'method' => 'date'
				),
				'DigitalZoomRatio' => array(
					'name' => __('Zoom numérique'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'ExifVersion' => array(
					'name' => __('Version Exif'),
					'section' => 'EXIF',
					'method' => 'version'
				),
				'ExposureBiasValue' => array(
					'name' => __('Correction de l\'exposition'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'ExposureMode' => array(
					'name' => __('Mode d\'exposition'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Automatique'),
						'1' => __('Manuel'),
						'2' => __('Bracketing automatique')
					)
				),
				'ExposureProgram' => array(
					'name' => __('Programme d\'exposition'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Non défini'),
						'1' => __('Manuel'),
						'2' => __('Programme normal'),
						'3' => __('Priorité à l\'ouverture'),
						'4' => __('Priorité à l\'obturateur'),
						'5' => __('Programme \'créatif\''
							. ' (préférence à la profondeur de champ)'),
						'6' => __('Programme \'action\''
							. ' (préférence à la vitesse d\'obturation)'),
						'7' => __('Mode portrait'
							. ' (pour des clichés de près avec arrière-plan flou)'),
						'8' => __('Mode paysage'
							. ' (pour des clichés de paysages avec arrière-plan net)')
					)
				),
				'ExposureTime' => array(
					'name' => __('Durée d\'exposition'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'Flash' => array(
					'name' => __('Flash'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Flash non déclenché'),
						'1' => __('Flash déclenché'),
						'5' => __('Retour de flash non détecté'),
						'7' => __('Retour de flash détecté'),
						'9' => __('Flash déclenché') . ', ' . __('mode forcé'),
						'13'=> __('Flash déclenché') . ', ' . __('mode forcé')
							. ', ' . __('retour de flash non détecté'),
						'15'=> __('Flash déclenché') . ', ' . __('mode forcé')
							. ', ' . __('retour de flash détecté'),
						'16'=> __('Flash non déclenché') . ', ' . __('mode forcé'),
						'24'=> __('Flash non déclenché') . ', ' . __('mode automatique'),
						'25'=> __('Flash déclenché') . ', ' . __('mode automatique'),
						'29'=> __('Flash déclenché') . ', ' . __('mode automatique')
							. ', ' . __('retour de flash non détecté'),
						'31'=> __('Flash déclenché') . ', ' . __('mode automatique')
							. ', ' . __('retour de flash détecté'),
						'32'=> __('Pas de flash activé'),
						'65'=> __('Flash déclenché') . ', ' . __('anti yeux-rouges activé'),
						'69'=> __('Flash déclenché') . ', ' . __('anti yeux-rouges activé')
							. ', ' . __('retour de flash non détecté'),
						'71'=> __('Flash déclenché') . ', ' . __('anti yeux-rouges activé')
							. ', ' . __('retour de flash détecté'),
						'73'=> __('Flash déclenché') . ', ' . __('mode forcé')
							. ', ' . __('anti yeux-rouges activé'),
						'77'=> __('Flash déclenché') . ', ' . __('mode forcé')
							. ', ' . __('anti yeux-rouges activé')
							. ', ' . __('retour de flash non détecté'),
						'79'=> __('Flash déclenché') . ', ' . __('mode forcé')
							. ', ' . __('anti yeux-rouges activé')
							. ', ' . __('retour de flash détecté'),
						'89'=> __('Flash déclenché') . ', ' . __('mode automatique')
							. ', ' . __('anti yeux-rouges activé'),
						'93'=> __('Flash déclenché') . ', ' . __('mode automatique')
							. ', ' . __('anti yeux-rouges activé')
							. ', ' . __('retour de flash non détecté'),
						'95'=> __('Flash déclenché') . ', ' . __('mode automatique')
							. ', ' . __('anti yeux-rouges activé')
							. ', ' . __('retour de flash détecté')
					)
				),
				'FlashPixVersion' => array(
					'name' => __('Version FlashPix'),
					'section' => 'EXIF',
					'method' => 'version'
				),
				'FNumber' => array(
					'name' => __('Ouverture'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'FocalLength' => array(
					'name' => __('Longueur de focale'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'FocalLengthIn35mmFilm' => array(
					'name' => __('Longueur de focale équivalente en 35mm'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'GainControl' => array(
					'name' => __('Contrôle du gain'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Aucun'),
						'1' => __('Faible augmentation'),
						'2' => __('Forte augmentation'),
						'3' => __('Faible diminution'),
						'4' => __('Forte diminution')
					)
				),
				'GPSAltitude' => array(
					'name' => __('Altitude GPS'),
					'section' => 'GPS',
					'method' => 'number'
				),
				'GPSCoordinates' => array(
					'name' => __('Coordonnées GPS'),
					'section' => 'GPS',
					'method' => 'simple'
				),
				'ISOSpeedRatings' => array(
					'name' => __('Sensibilité ISO'),
					'section' => 'EXIF',
					'method' => 'simple'
				),
				'Lens' => array(
					'name' => __('Objectif'),
					'section' => 'EXIF',
					'method' => 'simple'
				),
				'LightSource' => array(
					'name' => __('Source lumineuse'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Inconnue'),
						'1' => __('Lumière du jour'),
						'2' => __('Fluorescent'),
						'3' => __('Tungstène (lumière incandescente)'),
						'4' => __('Flash'),
						'9' => __('Beau temps'),
						'10' => __('Temps nuageux'),
						'11' => __('Ombre'),
						'12' => __('Fluorescent lumière du jour (D 5700 - 7100K)'),
						'13' => __('Fluorescent teintes blanches (N 4600 - 5400K)'),
						'14' => __('Fluorescent froid (W 3900 - 4500K)'),
						'15' => __('Fluorescent blanc (WW 3200 - 3700K)'),
						'17' => __('Lumière standard A'),
						'18' => __('Lumière standard B'),
						'19' => __('Lumière standard C'),
						'20' => __('D55'),
						'21' => __('D65'),
						'22' => __('D75'),
						'23' => __('D50'),
						'24' => __('ISO tungstène pour le studio'),
						'255' => __('Autre source de lumière')
					)
				),
				'Make' => array(
					'name' => __('Marque'),
					'section' => 'IFD0',
					'method' => 'simple'
				),
				'MaxApertureValue' => array(
					'name' => __('Ouverture maximale'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'MeteringMode' => array(
					'name' => __('Mode de mesure'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Inconnu'),
						'1' => __('Moyenne'),
						'2' => __('Moyenne pondérée au centre'),
						'3' => __('Tache'),
						'4' => __('Multipoint'),
						'5' => __('Motif'),
						'6' => __('Partiel'),
						'255' => __('Autre')
					)
				),
				'Model' => array(
					'name' => __('Modèle'),
					'section' => 'IFD0',
					'method' => 'simple'
				),
				'Orientation' => array(
					'name' => __('Orientation'),
					'section' => 'IFD0',
					'method' => 'list',
					'list' => array(
						'1' => __('Normale'),
						'2' => __('Retournement horizontal'),
						'3' => __('Rotation de 180°'),
						'4' => __('Retournement vertical'),
						'5' => __('Rotation à gauche de 90° et retournement vertical'),
						'6' => __('Rotation à droite de 90°'),
						'7' => __('Rotation à droite de 90° et retournement vertical'),
						'8' => __('Rotation à gauche de 90°')
					)
				),
				'ResolutionUnit' => array(
					'name' => __('Unité de résolution'),
					'section' => 'IFD0',
					'method' => 'list',
					'list' => array(
						'1' => __('Pixels'),
						'2' => __('Pouces'),
						'3' => __('Centimètres')
					)
				),
				'Saturation' => array(
					'name' => __('Saturation'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Normale'),
						'1' => __('Basse'),
						'2' => __('Élevée')
					)
				),
				'SceneCaptureType' => array(
					'name' => __('Type de capture de scène'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Standard'),
						'1' => __('Paysage'),
						'2' => __('Portrait'),
						'3' => __('Nuit')
					)
				),
				'SceneType' => array(
					'name' => __('Type de scène'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'1' => __('Une image photographiée directement')
					)
				),
				'SensingMethod' => array(
					'name' => __('Type de capteur'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'1' => __('Non défini'),
						'2' => __('Capteur couleur à un processeur'),
						'3' => __('Capteur couleur à deux processeurs'),
						'4' => __('Capteur couleur à trois processeurs'),
						'5' => __('Capteur couleur séquentiel'),
						'7' => __('Capteur trilinéaire'),
						'8' => __('Capteur couleur linéaire séquentiel')
					)
				),
				'Sharpness' => array(
					'name' => __('Netteté'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Normale'),
						'1' => __('Faible'),
						'2' => __('Forte')
					)
				),
				'Software' => array(
					'name' => __('Logiciel'),
					'section' => 'IFD0',
					'method' => 'simple'
				),
				'SubjectDistance' => array(
					'name' => __('Distance de mise au point'),
					'section' => 'EXIF',
					'method' => 'number'
				),
				'SubjectDistanceRange' => array(
					'name' => __('Plage de distance de mise au point'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Inconnue'),
						'1' => __('Macro (<1 mètre)'),
						'2' => __('Proche (1 à 3 mètres)'),
						'3' => __('Éloignée (>3 mètres)')
					)
				),
				'WhiteBalance' => array(
					'name' => __('Balance des blancs'),
					'section' => 'EXIF',
					'method' => 'list',
					'list' => array(
						'0' => __('Automatique'),
						'1' => __('Manuelle')
					)
				),
				'XResolution' => array(
					'name' => __('Résolution horizontale'),
					'section' => 'IFD0',
					'method' => 'number'
				),
				'YResolution' => array(
					'name' => __('Résolution verticale'),
					'section' => 'IFD0',
					'method' => 'number'
				)
			);
		}

		if (isset($params[$info]))
		{
			return $params[$info];
		}
	}

	/**
	 * Récupère les coordonnées GPS.
	 *
	 * @return array|boolean
	 */
	private function _getGPSCoordinates()
	{
		if (!empty($this->exifData['GPS'])
		&& is_array($this->exifData['GPS'])
		&& !empty($this->exifData['GPS']['GPSLatitudeRef'])
		&& is_array($this->exifData['GPS']['GPSLatitude'])
		&& count($this->exifData['GPS']['GPSLatitude']) == 3
		&& !empty($this->exifData['GPS']['GPSLongitudeRef'])
		&& is_array($this->exifData['GPS']['GPSLongitude'])
		&& count($this->exifData['GPS']['GPSLongitude']) == 3)
		{
			foreach (array('GPSLatitude', 'GPSLongitude') as $coord)
			{
				for ($i = $$coord = 0; $i < 3; $i++)
				{
					if (!preg_match('`^(\d+)/(\d+)$`', $this->exifData['GPS'][$coord][$i], $m)
					|| $coord == 'GPSLatitude'  && $i == 0 && strlen($m[1]) > 2
					|| $coord == 'GPSLongitude' && $i == 0 && strlen($m[1]) > 3)
					{
						unset($$coord);
						break 2;
					}
					if ($m[2] != 0)
					{
						$$coord += $m[1] / pow(60, $i) / $m[2];
					}
				}
				$ref = ($coord == 'GPSLatitude') ? 'S' : 'W';
				if ($this->exifData['GPS'][$coord . 'Ref'] == $ref)
				{
					$$coord -= $$coord * 2;
				}
			}
			if (isset($GPSLatitude) && isset($GPSLongitude))
			{
				return array($GPSLatitude, $GPSLongitude);
			}
		}

		return FALSE;
	}

	/**
	 * Récupère les données IPTC brutes
	 * et les place dans la propriété $iptcData.
	 *
	 * @return boolean
	 */
	private function _getIptcData()
	{
		// Si les données ont déjà été récupérées, inutile d'aller plus loin.
		if (is_array($this->iptcData))
		{
			return TRUE;
		}

		if (!function_exists('iptcparse'))
		{
			return FALSE;
		}

		if (!file_exists($this->filePath))
		{
			return FALSE;
		}

		if (!getimagesize($this->filePath, $image_infos))
		{
			return FALSE;
		}

		if (!is_array($image_infos) || !isset($image_infos['APP13']))
		{
			return FALSE;
		}

		if (!is_array($iptc_data = iptcparse($image_infos['APP13'])))
		{
			return FALSE;
		}

		$this->iptcData =& $iptc_data;

		// Nettoyage des données.
		$this->_cleanValues($this->iptcData);

		return TRUE;
	}

	/**
	 * Retourne l'information IPTC $info formatée.
	 *
	 * @param string $info
	 * @return array
	 */
	private function _getIptcFormatedInfo($info)
	{
		if (!isset($this->iptcData['2#' . $info])
		 || !isset($this->iptcData['2#' . $info][0])
		 || utils::isEmpty($this->iptcData['2#' . $info][0]))
		{
			return;
		}

		return array(
			'name' => $this->getIptcLocale($info),
			'value' => implode(', ', $this->iptcData['2#' . $info])
		);
	}

	/**
	 * Retourne les paramètres d'une information IPTC.
	 *
	 * @param string $info
	 * @return string
	 */
	private static function _getIptcParams($info)
	{
		static $params;

		if (empty($params))
		{
			$params = array(
				'005' => __('Nom de l\'objet'),
				'007' => __('Statut éditorial'),
				'010' => __('Priorité'),
				'015' => __('Catégories'),
				'020' => __('Identificateur'),
				'025' => __('Mots-clés'),
				'026' => __('Code de l\'emplacement du contenu'),
				'027' => __('Nom de l\'emplacement du contenu'),
				'030' => __('Date de sortie'),
				'035' => __('Heure de sortie'),
				'040' => __('Instructions spéciales'),
				'055' => __('Date de création'),
				'060' => __('Heure de création'),
				'065' => __('Programme'),
				'070' => __('Version du programme'),
				'075' => __('Cycle de l\'objet'),
				'080' => __('Auteurs'),
				'085' => __('Titre de l\'auteur'),
				'090' => __('Ville'),
				'092' => __('Région'),
				'095' => __('Province/État'),
				'100' => __('Code pays'),
				'101' => __('Pays'),
				'103' => __('Référence de la transmission'),
				'105' => __('Titre'),
				'110' => __('Crédit'),
				'115' => __('Source'),
				'116' => __('Copyright'),
				'118' => __('Contact'),
				'120' => __('Description'),
				'122' => __('Auteur de la description'),
				'130' => __('Type de l\'image')
			);
		}

		if (isset($params[$info]))
		{
			return $params[$info];
		}
	}

	/**
	 * Récupère le texte contenu dans les propriétés XMP de type "Lang Alt",
	 * puis formate ce contenu avec les tags de langues utilisés par iGalerie.
	 * Contenu qui pourra être exploité avec utils::getLocale().
	 *
	 * @param string $field
	 *	Nom du champ a examiné.
	 *	Exemples: "dc:description", "dc:title".
	 * @return null|string
	 *	Retourne le contenu trouvé (string)
	 *	ou NULL si aucun contenu n'a été trouvé.
	 */
	private function _getRdfAlt($field)
	{
		if (!preg_match('`<' . $field . '>([^$]*)</' . $field . '>`', $this->xmpData, $m))
		{
			return;
		}

		$content = NULL;
		$content_x_default = NULL;
		$items = explode('</rdf:li>', $m[1]);
		$n = 0;

		foreach ($items as &$val)
		{
			if (!preg_match('`<rdf:li' .
			' xml:lang=[\'\"]([a-z]{2}-[a-z]{2}|x-default)[\'\"]>(.+)`', $val, $m))
			{
				continue;
			}

			$m[2] = trim($m[2]);

			if (utils::isEmpty($m[2]))
			{
				continue;
			}

			// Contenu par défaut.
			if ($m[1] == 'x-default')
			{
				$content_x_default = $m[2];
				continue;
			}

			$lang = substr($m[1], 0, 2) . '_' . strtoupper(substr($m[1], 3, 2));

			// La langue doit faire partie des langues installées.
			if (!isset(utils::$config['locale_langs'][$lang]))
			{
				continue;
			}

			$content .= '<' . $lang . '>' . $m[2] . '</' . $lang . '>';
			$n++;
		}

		// Si une seule langue a été trouvée,
		// et qu'il n'y a pas de contenu par défaut,
		// on supprime les tags de langues
		// pour faire de son contenu le contenu par défaut.
		if ($n == 1 && $content_x_default === NULL)
		{
			$content = preg_replace('`^<' . $lang . '>|</' . $lang . '>$`', '', $content);
		}

		// Contenu par défaut (sans tags de langues).
		if ($content_x_default !== NULL)
		{
			$content = $content_x_default . $content;
		}

		return ($content === NULL)
			? NULL
			: utils::UTF8($content);
	}

	/**
	 * Récupère le contenu des propriétés XMP
	 * de type "Bag" et "Seq" dans un tableau.
	 *
	 * @param string $field
	 *	Nom du champ a examiné.
	 *	Exemple: "dc:subject".
	 * @return null|array
	 *	Retourne le contenu trouvé (array)
	 *	ou NULL si aucun contenu n'a été trouvé.
	 */
	private function _getRdfBag($field)
	{
		if (!preg_match('`<' . $field . '>([^$]*)</' . $field . '>`', $this->xmpData, $m))
		{
			return;
		}

		$array = array();
		$items = explode('</rdf:li>', $m[1]);

		foreach ($items as &$val)
		{
			if (!preg_match('`<rdf:li>(.+)`', $val, $m))
			{
				continue;
			}

			if (utils::isEmpty($m[1]))
			{
				continue;
			}

			$array[] = utils::UTF8(trim($m[1]));
		}

		return ($array == array())
			? NULL
			: $array;
	}

	/**
	 * Récupère le contenu d'une propriété XMP.
	 *
	 * @param string $field
	 *	Nom du champ a examiné.
	 *	Exemples: "dc:format", "dc:source".
	 * @return null|string
	 *	Retourne le contenu trouvé (string)
	 *	ou NULL si aucun contenu n'a été trouvé.
	 */
	private function _getRdfText($field)
	{
		$field = preg_quote($field);
		if (!preg_match('`<' . $field . '>([^$]*)</' . $field . '>`', $this->xmpData, $m))
		{
			return;
		}

		return utils::UTF8($m[1]);
	}

	/**
	 * Récupère les données XMP brutes
	 * et les place dans la propriété $xmpData.
	 *
	 * @return boolean
	 */
	private function _getXmpData()
	{
		// Si les données ont déjà été récupérées, inutile d'aller plus loin.
		if ($this->xmpData !== NULL)
		{
			return TRUE;
		}

		if (!file_exists($this->filePath))
		{
			return FALSE;
		}

		if (($fp = fopen($this->filePath, 'rb')) === FALSE)
		{
			return FALSE;
		}

		$inside = FALSE;
		$done = FALSE;
		$xmp_data = NULL;

		// Parcours du fichier à la recherche des données XMP.
		while (!feof($fp))
		{
			if (($buffer = fgets($fp, 4096)) === FALSE)
			{
				continue;
			}

			$xmp_start = strpos($buffer, '<x:xmpmeta');

			if ($xmp_start !== FALSE)
			{
				$buffer = substr($buffer, $xmp_start);
				$inside = TRUE;
			}

			if ($inside)
			{
				$xmp_end = strpos($buffer, '</x:xmpmeta>');
				if ($xmp_end !== FALSE)
				{
					$buffer = substr($buffer, $xmp_end, 12);
					$inside = FALSE;
					$done = TRUE;
				}

				$xmp_data .= $buffer;
			}

			if ($done)
			{
				break;
			}
		}
		fclose($fp);

		if (empty($xmp_data))
		{
			return FALSE;
		}

		$this->xmpData =& $xmp_data;

		// Nettoyage des données.
		$this->_cleanValues($this->xmpData);

		return TRUE;
	}

	/**
	 * Retourne l'information XMP $info formatée.
	 *
	 * @param string $info
	 * @return array
	 */
	private function _getXmpFormatedInfo($info)
	{
		$params = self::_getXmpParams($info);

		switch ($params['method'])
		{
			case 'alt' :
				$value = $this->_getRdfAlt($info);
				if (utils::isEmpty($value))
				{
					return;
				}
				$value = utils::getLocale($value);
				break;

			case 'bag' :
				$value = $this->_getRdfBag($info);
				if (empty($value) || !is_array($value))
				{
					return;
				}
				$value = implode(', ', $value);
				break;

			case 'text' :
				$value = $this->_getRdfText($info);
				if (utils::isEmpty($value))
				{
					return;
				}
				break;

			default :
				$value = '';
		}

		return array(
			'name' => $params['name'],
			'value' => $value
		);
	}

	/**
	 * Retourne les paramètres d'une information XMP.
	 *
	 * @param string $info
	 * @return string
	 */
	private static function _getXmpParams($info)
	{
		static $params;

		if (empty($params))
		{
			$params = array(
				'dc:contributor' => array(
					'method' => 'bag',
					'name' => __('Contributeurs')
				),
				'dc:coverage' => array(
					'method' => 'text',
					'name' => __('Portée')
				),
				'dc:creator' => array(
					'method' => 'bag',
					'name' => __('Auteurs')
				),
				'dc:date' => array(
					'method' => 'bag',
					'name' => __('Dates')
				),
				'dc:description' => array(
					'method' => 'alt',
					'name' => __('Description')
				),
				'dc:format' => array(
					'method' => 'text',
					'name' => __('Format')
				),
				'dc:identifier' => array(
					'method' => 'text',
					'name' => __('Identificateur')
				),
				'dc:language' => array(
					'method' => 'bag',
					'name' => __('Langues')
				),
				'dc:publisher' => array(
					'method' => 'bag',
					'name' => __('Éditeurs')
				),
				'dc:relation' => array(
					'method' => 'bag',
					'name' => __('Ressources liées')
				),
				'dc:rights' => array(
					'method' => 'alt',
					'name' => __('Droits')
				),
				'dc:source' => array(
					'method' => 'text',
					'name' => __('Source')
				),
				'dc:subject' => array(
					'method' => 'bag',
					'name' => __('Mots-clés')
				),
				'dc:title' => array(
					'method' => 'alt',
					'name' => __('Titre')
				),
				'dc:type' => array(
					'method' => 'bag',
					'name' => __('Genre')
				)
			);
		}

		if (isset($params[$info]))
		{
			return $params[$info];
		}
	}
}
?>