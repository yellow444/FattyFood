<?php
/**
 * Code commun pour les scripts d'affichage et de téléchargement d'images ou
 * d'albums, avec vérification des permissions utilisateur.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

// Connexion à la base de données.
utils::$db = new db();
if (utils::$db->connexion === NULL)
{
	die('Unable to connect to the database.');
}

// Récupération de la configuration de la galerie.
utils::getConfig();

// La galerie est-elle fermée ?
if (utils::$config['gallery_closure'] && !$object_infos['admin'])
{
	utils::redirect();
	die;
}

// Récupération de l'identifiant de session que possède l'utilisateur.
if (empty($object_infos['session_token']))
{
	utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);
	$object_infos['session_token'] = user::getSessionCookieToken();
}

if ($object_infos['type'] == 'image')
{
	// On récupère les informations utiles de l'image,
	// et on vérifie par la même occasion que l'image existe bien.
	$object_infos['sql'] =
		'SELECT cat.cat_id,
				cat.cat_watermark,
				image_id,
				image_path,
				image_adddt,
				image_width,
				image_height,
				image_rotation,
				image_status,
				image_hits,
				u.user_id,
				u.user_watermark
		   FROM ' . CONF_DB_PREF . 'images AS img
	  LEFT JOIN ' . CONF_DB_PREF . 'users AS u
			 ON img.user_id = u.user_id
	  LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
			 ON img.cat_id = cat.cat_id
		  WHERE image_id = ' . (int) $_GET['id']
				. ($object_infos['admin'] ? '' : ' AND image_status = "1"');
}

$object_infos['sql_protect'] = '';

// Si la gestion de membres est activée, on tient compte des droits d'accès.
if (utils::$config['users'] || $object_infos['admin'])
{
	$auth = FALSE;

	// Récupération des informations utilisateur.
	if ($object_infos['session_token'] !== FALSE)
	{
		$sql = 'SELECT user_id,
					   user_nohits,
					   user_lang,
					   user_tz,
					   g.group_id,
					   group_admin,
					   group_perms
				  FROM ' . CONF_DB_PREF . 'sessions AS s,
					   ' . CONF_DB_PREF . 'groups AS g,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE u.session_id = s.session_id
				   AND u.group_id = g.group_id
				   AND user_status = "1"
				   AND session_token = "' . $object_infos['session_token'] . '"
				   AND session_expire > NOW()';
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult === 1)
		{
			$auth = TRUE;
		}
	}

	// Récupération des droits invités.
	if (!$auth)
	{
		if (utils::$config['users_only_members'] || $object_infos['admin'])
		{
			die('You are not allowed here.');
		}
		$sql = 'SELECT 2 AS user_id,
					   2 AS group_id,
					   group_perms
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id = 2';
		if (utils::$db->query($sql, 'row') === FALSE
		 || utils::$db->nbResult !== 1)
		{
			die;
		}
	}

	// Permissions.
	$object_infos['user_infos'] = utils::$db->queryResult;
	$object_infos['user_perms'] = $object_infos['user_infos']['group_perms'];
	unset($object_infos['user_infos']['group_perms']);
	if (!utils::isSerializedArray($object_infos['user_perms']))
	{
		die('Error.');
	}
	$object_infos['user_perms'] = unserialize($object_infos['user_perms']);

	// Mode d'accès aux albums.
	if (utils::$config['users'])
	{
		sql::categoriesPerms(
			$object_infos['user_infos']['group_id'],
			$object_infos['user_perms'],
			$object_infos['admin']
		);
		$object_infos['sql_protect'] = sql::$categoriesAccess;
	}
}

// Protection de l'objet par mot de passe
// (mais pas en administration).
if (!$object_infos['admin'])
{
	$object_infos['sql_protect'] .= ($object_infos['session_token'] === FALSE)
		? ' AND cat_password IS NULL'
		: ' AND (cat_password IS NULL OR
				(SELECT 1
				   FROM ' . CONF_DB_PREF . 'passwords
				   JOIN ' . CONF_DB_PREF . 'sessions USING(session_id)
				  WHERE cat_password LIKE CONCAT("%:", password)
					AND session_token = "' . $object_infos['session_token'] . '"
					AND session_expire > NOW()
				) = 1
			  )';
}

// Exécution de la requête.
if ($object_infos['sql'])
{
	if (utils::$db->query($object_infos['sql'] . $object_infos['sql_protect'], 'row') === FALSE)
	{
		die('Error.');
	}
	if (utils::$db->nbResult === 0)
	{
		die('You are not allowed here.');
	}
	$object_infos['result'] = utils::$db->queryResult;

	if ($object_infos['type'] == 'image')
	{
		// On vérifie que le fichier existe bien.
		$object_infos['file_path'] = GALLERY_ROOT . '/'
			. CONF_ALBUMS_DIR . '/' . $object_infos['result']['image_path'];
		if (!file_exists($object_infos['file_path']))
		{
			die('File does not exist.');
		}

		// On détermine le type mime de l'image.
		$object_infos['mime_type'] = img::getMimeType(
			preg_replace('`^.*\.([a-z]{2,4})$`', '$1', $object_infos['result']['image_path'])
		);
	}
}

// Changement de la configuration en fonction des permissions.
user::changeConfig($object_infos['user_perms']);
?>