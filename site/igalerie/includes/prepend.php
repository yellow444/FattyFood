<?php
/**
 * Paramètres communs à toute l'application.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (!function_exists('version_compare') || !defined('PHP_VERSION')
 || !version_compare(PHP_VERSION, 5.4, '>='))
{
	die('PHP 5.4.0 or greater is required.');
}

define('GALLERY_ROOT', dirname(dirname(__FILE__)));
define('GALLERY_HTTPS', (isset($_SERVER['HTTPS'])
	&& (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1)));
define('GALLERY_HOST', (GALLERY_HTTPS ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);

// Chargement du fichier de configuration.
if (file_exists(GALLERY_ROOT . '/config/conf.php'))
{
	require_once(GALLERY_ROOT . '/config/conf.php');
}
else
{
	require_once(GALLERY_ROOT . '/config/conf_default.php');
}

// Chargement des classes.
spl_autoload_register(function($class)
{
	$file = GALLERY_ROOT . '/includes/classes/' . $class . '.class.php';
	if (!file_exists($file))
	{
		$message = 'Failed opening required file.';
		trigger_error($message, E_USER_WARNING);
		die($message);
	}
	require_once($file);
});

// Gestion des erreurs et de la mémoire.
error_reporting(-1);
if (function_exists('ini_set'))
{
	ini_set('memory_limit', '1024M');
	ini_set('display_errors', CONF_ERRORS_DISPLAY ? 1 : 0);
}
set_error_handler(array('errorHandler', 'phpError'));
set_exception_handler(array('errorHandler', 'exception'));
register_shutdown_function(array('errorHandler', 'shutdown'));

// Gestion du temps.
define('START_TIME', microtime(TRUE));
if (function_exists('ignore_user_abort'))
{
	ignore_user_abort(TRUE);
}
if (function_exists('set_time_limit'))
{
	//set_time_limit(30);
}
date_default_timezone_set(CONF_DEFAULT_TZ);

// Supression de tout paramètre GET non utilisé.
$gets = (isset($gets)) ? $gets : array('q');
foreach ($_GET as $name => $value)
{
	if (!in_array($name, $gets))
	{
		unset($_GET[$name]);
	}
}
unset($_REQUEST);

// Jeu de caractères pour les fonctions mbstring.
if (!extension_loaded('mbstring'))
{
	die('mbstring not loaded.');
}
mb_internal_encoding(CONF_CHARSET);
if (function_exists('mb_regex_encoding'))
{
	mb_regex_encoding(CONF_CHARSET);
}

// Compression de la page.
if (!CONF_DEBUG && extension_loaded('zlib') && !utils::getIniBool('zlib.output_compression'))
{
	ob_start('ob_gzhandler');
}

if (empty($no_header))
{
	header('Content-Type: text/html; charset=' . CONF_CHARSET);
}



/**
 * Localisation.
 *
 * @param string $str
 * @global array $L10N
 * @return string
 */
function __($str)
{
	global $L10N;

	if (isset($L10N[$str]))
	{
		$L10N[$str] = trim($L10N[$str]);
		return ($L10N[$str] == '')
			? $str
			: $L10N[$str];
	}
	else
	{
		if (CONF_DEBUG)
		{
			trigger_error("Localization not found: \"$str\"", E_USER_NOTICE);
		}
		return $str;
	}
}

/**
 * Extraction des paramètres de la requête.
 *
 * @param array $params
 * @return void
 */
function extract_request($params)
{
	if (empty($_GET['q']))
	{
		return;
	}

	// Sécurité : supprime les caractères spéciaux sensibles.
	$_GET['q'] = str_replace(array('<', '>', '&', '"', "'", "\\"), '?', $_GET['q']);
	$_GET['q'] = preg_replace("`[\s\t]+`", '_', $_GET['q']);
	$_GET['q'] = utils::deleteInvisibleChars($_GET['q']);

	// Détermine la section.
	if (!preg_match('`^([^/]+)(?:/(.*))?$`', $_GET['q'], $m_q))
	{
		return;
	}
	if (!isset($params[$m_q[1]]) || !is_array($params[$m_q[1]]))
	{
		return;
	}
	$_GET['section'] = $m_q[1];
	if (!isset($m_q[2]) && empty($params[$m_q[1]]))
	{
		return;
	}

	// Vérifie la requête par regexp.
	$q2 = isset($m_q[2]) ? $m_q[2] : '';
	foreach ($params[$m_q[1]] as $regexp => &$gets)
	{
		if (preg_match('`^' . $regexp . '$`', $q2, $m))
		{
			array_shift($m);
			for ($i = 0, $count = count($m); $i < $count; $i++)
			{
				$_GET[$gets[$i]] = $m[$i];
			}
			return;
		}
	}

	// Si la page demandée n'existe pas, on supprime tout.
	$_GET = array();
}
?>