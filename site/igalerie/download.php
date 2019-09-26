<?php
/**
 * Téléchargement d'images.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (isset($_GET['alb']))
{
	$_GET['id'] = (int) $_GET['alb'];
}
else if (isset($_GET['img']))
{
	$_GET['id'] = (int) $_GET['img'];
}
else if (isset($_GET['sel']))
{
	$_GET['id'] = $_GET['sel'];
}
else if (!isset($_GET['basket']))
{
	die('You are not allowed here.');
}

// Téléchargement d'une image.
if (isset($_GET['img']))
{
	$download_image = TRUE;
	require_once(dirname(__FILE__) . '/image.php');
	die;
}

// Téléchargement d'un album.
if (isset($_GET['alb']))
{
	$gets = array('id');
	require_once(dirname(__FILE__) . '/includes/prepend.php');

	$object_infos = array(
		'admin' => FALSE,
		'sql' => 'SELECT cat_path,
						 cat_name
					FROM ' . CONF_DB_PREF . 'categories AS cat
				   WHERE cat_id = ' . (int) $_GET['id'] . '
					 AND cat_filemtime IS NOT NULL
					 AND cat_downloadable = "1"
					 AND cat_status = "1"',
		'type' => 'album'
	);
	require_once(dirname(__FILE__) . '/includes/object_infos.php');

	// Permissions d'accès à la fonctionnalité.
	if ((utils::$config['download_zip_albums']
	&& (!utils::$config['users'] || (utils::$config['users']
	&& $object_infos['user_perms']['gallery']['perms']['download_albums']))) === FALSE)
	{
		die('You are not allowed here.');
	}

	// Récupération des informations utiles des images.
	$sql = 'SELECT image_id,
				   image_path,
				   image_name
			  FROM ' . CONF_DB_PREF . 'images
			 WHERE cat_id = ' . (int) $_GET['id'] . '
			   AND image_status = "1"';

	$archive_name = utils::getLocale($object_infos['result']['cat_name']);
}

// Téléchargement d'une sélection d'images.
if (isset($_GET['sel']))
{
	$gets = array('id');
	require_once(dirname(__FILE__) . '/includes/prepend.php');

	$object_infos = array(
		'admin' => FALSE,
		'sql' => NULL,
		'type' => NULL
	);
	require_once(dirname(__FILE__) . '/includes/object_infos.php');

	// La fonctionnalité "Téléchargement Zip" doit être activée.
	if (!utils::$config['download_zip_albums'])
	{
		die('You are not allowed here.');
	}

	// Quelques vérifications.
	if (!preg_match('`^\d+(,\d+)*$`', $_GET['id'])
	 || strlen(preg_replace('`\d+`', '', $_GET['id'])) > 999)
	{
		die('You are not allowed here.');
	}

	// Récupération des informations utiles des images.
	$sql = 'SELECT image_id,
				   image_name,
				   image_path
			  FROM ' . CONF_DB_PREF . 'images AS img
			  JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
			 WHERE image_status = "1"
			   AND image_id IN (' . $_GET['id'] . ')
				   ' . $object_infos['sql_protect'];

	$archive_name = 'selection';
}

// Téléchargement du panier.
if (isset($_GET['basket']))
{
	require_once(dirname(__FILE__) . '/includes/prepend.php');

	$object_infos = array(
		'admin' => FALSE,
		'sql' => NULL,
		'type' => NULL
	);
	require_once(dirname(__FILE__) . '/includes/object_infos.php');

	// La fonctionnalité "Panier" doit être activée.
	if (!utils::$config['basket'])
	{
		die('You are not allowed here.');
	}

	// Récupération des informations utiles des images.
	if (isset($object_infos['user_infos']) && $object_infos['user_infos']['user_id'] != 2)
	{
		$sql = 'SELECT i.image_id,
					   i.image_name,
					   i.image_path
				  FROM ' . CONF_DB_PREF . 'basket AS b,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE b.user_id = ' . (int) $object_infos['user_infos']['user_id'] . '
				   AND b.image_id = i.image_id
				   AND cat.cat_id = i.cat_id
				   AND i.image_status = "1"
					   ' . $object_infos['sql_protect'];
	}
	else if ($object_infos['session_token'] !== FALSE)
	{
		$sql = 'SELECT i.image_id,
					   i.image_name,
					   i.image_path
				  FROM ' . CONF_DB_PREF . 'basket AS b,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'sessions AS s
				 WHERE s.session_token = "' . $object_infos['session_token'] . '"
				   AND b.session_id = s.session_id
				   AND b.image_id = i.image_id
				   AND cat.cat_id = i.cat_id
				   AND i.image_status = "1"
					   ' . $object_infos['sql_protect'];
	}
	else
	{
		die('You are not allowed here.');
	}

	$archive_name = __('panier');
}

// Récupération des images.
$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
if (utils::$db->query($sql, $fetch_style) === FALSE)
{
	die;
}
if (utils::$db->nbResult === 0)
{
	die('You are not allowed here.');
}
$images = utils::$db->queryResult;

// Fermeture de la connexion à la base de données.
utils::$db->connexion = NULL;

// Fichiers à placer dans l'archive.
$files = array();
foreach ($images as &$infos)
{
	// Chemin complet du fichier.
	$files[] = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $infos['image_path'];
}

// Envoi de l'archive.
utils::zipArchive($archive_name . '.zip', $files);
?>