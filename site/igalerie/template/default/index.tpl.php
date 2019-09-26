<?php if (!CONF_INTEGRATED) : ?>
<!DOCTYPE html>

<html lang="<?php echo substr($tpl->getGallery('lang_current_code'), 0, 2); ?>">


<head>

<title><?php if ($tpl->disGallery('page_title')) : ?><?php echo $tpl->getGallery('page_title'); ?> - <?php endif; ?><?php echo $tpl->getGallery('gallery_title'); ?></title>

<?php include_once(dirname(__FILE__) . '/head.tpl.php'); ?>

</head>


<body id="section_<?php echo str_replace('-', '_', $_GET['section']); ?>">
<?php endif; ?>

<div id="igalerie">
	<div id="gallery">
	<header>
		<div id="header_image">
<?php if ($tpl->disWidgets('image')) : ?>
<?php while ($tpl->nextWidgetImage()) : ?>
			<dl class="random_thumb">
				<dt>
					<a title="<?php printf(__('Voir l\'image \'%s\''), $tpl->getWidgetImage('title')); ?>"
						href="<?php echo $tpl->getWidgetImage('link'); ?>">
						<img style="padding:<?php echo $tpl->getWidgetImage('center'); ?>;"
							alt="<?php echo $tpl->getWidgetImage('title'); ?>"
							src="<?php echo $tpl->getWidgetImage('thumb');?>"
							<?php echo $tpl->getWidgetImage('thumb_size'); ?> />
					</a>
				</dt>
				<dd></dd>
			</dl>
<?php endwhile; ?>
<?php endif; ?>
		</div>
		<div id="header_right">
		<div id="header_top">
			<div id="header_title">
<?php if ($tpl->disGallery('users') && $tpl->disWidgets('user')) : ?>
<?php if ($tpl->disAuthUser()) : ?>
<?php if ($tpl->disGallery('avatars')) : ?>
				<a id="user_avatar" href="<?php echo $tpl->getLink(sprintf('user/%s', $tpl->getAuthUser('id'))); ?>">
					<img title="<?php echo __('Consultez votre profil'); ?>" alt="<?php printf(__('Avatar de %s'), $tpl->getAuthUser('login')); ?>" width="50" height="50" src="<?php echo $tpl->getAuthUser('avatar_src'); ?>" />
				</a>
<?php endif; ?>
				<ul id="user_links">
					<li title="<?php echo __('Consultez votre profil'); ?>" id="user_name"><a href="<?php echo $tpl->getLink(sprintf('user/%s', $tpl->getAuthUser('id'))); ?>"><?php echo $tpl->getAuthUser('login'); ?></a></li>
					<li id="user_logout">
						<form action="<?php echo $tpl->getGallery('page_url'); ?>" method="post">
							<p>
								<a style="display:none" id="deconnect_user_link" href="javascript:;"><?php echo __('déconnexion'); ?></a>
								<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
								<input id="deconnect_user_input" name="deconnect_user" type="submit" value="<?php echo __('déconnexion'); ?>" />
								<script type="text/javascript">document.getElementById('deconnect_user_input').style.display = 'none';</script>
								<script type="text/javascript">document.getElementById('deconnect_user_link').style.display = '';</script>
							</p>
						</form>
					</li>
				</ul>
<?php endif; ?>
<?php if (!$tpl->disAuthUser()) : ?>
				<img id="user_avatar" alt="<?php echo __('aucun avatar'); ?>" width="50" height="50" src="<?php echo $tpl->getGallery('style_path'); ?>/avatar-default.png" />
				<ul id="user_links">
					<li id="user_login"><a href="<?php echo $tpl->getLink('login'); ?>"><?php echo __('connexion'); ?></a></li>
<?php if ($tpl->disGallery('users_inscription')) : ?>
					<li id="user_register"><a href="<?php echo $tpl->getLink('register'); ?>"><?php echo __('créer un compte'); ?></a></li>
<?php endif; ?>
				</ul>
<?php endif; ?>
<?php endif; ?>

				<h1><a accesskey="1" href="<?php echo $tpl->getGallery('gallery_path'); ?>/"><?php echo $tpl->getGallery('gallery_title_banner'); ?></a></h1>
			</div>
		</div>
		<div id="header_bottom">
<?php if ($tpl->disGallery('lang_switch')) : ?>
			<div id="lang_list">
				<div>
					<span><?php echo __('Langues'); ?></span>
					<ul>
<?php while ($tpl->nextLang()) : ?>
						<li<?php if ($tpl->disLang('current')) : ?> class="current"<?php endif; ?>><a class="icon icon_<?php echo $tpl->getLang('code'); ?>" data-lang-code="<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getLang('name'); ?></a></li>
<?php endwhile; ?>
					</ul>
				</div>
			</div>
			<img title="<?php echo __('Langue'); ?>" alt="<?php echo $tpl->getGallery('lang_current_name'); ?>" id="lang_change" width="16" height="11"
				src="<?php echo $tpl->getGallery('gallery_path'); ?>/images/flags/<?php echo $tpl->getGallery('lang_current_code'); ?>.png" />
			<form action="<?php echo $tpl->getGallery('page_url'); ?>" method="post">
				<div>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
					<input id="new_lang" name="new_lang" type="hidden" />
					<input style="display:none" id="change_lang" name="change_lang" type="submit" />
				</div>
			</form>
<?php endif; ?>

<?php if ($tpl->disWidgets('navigation')) : ?>
<?php if ($tpl->disPageLinks()) : ?>
			<nav id="header_nav_links">
				<ul>
<?php while ($tpl->nextPageLink()) : ?>
					<li id="pagelink_<?php echo $tpl->getPageLink('id'); ?>"><a href="<?php echo $tpl->getPageLink('link'); ?>"><?php echo $tpl->getPageLink('title'); ?></a></li>
<?php endwhile; ?>
				</ul>
			</nav>
<?php endif; ?>
			<div id="basics_bottom">
<?php if ($tpl->disGallery('nav_categories')) : ?>
				<div class="browse">
					<?php echo __('Parcourir :'); ?>

					<select name="browse" onchange="window.location.href=(this.options[this.selectedIndex].value=='')?'<?php echo $tpl->getGallery('gallery_path'); ?>/':'<?php echo $tpl->getGallery('gallery_base_url'); ?>'+this.options[this.selectedIndex].value">
						<?php echo $tpl->getMap('select'); ?>

					</select>
				</div>
<?php endif; ?>
<?php if ($tpl->disGallery('nav_search')) : ?>
				<div id="search">
<?php if ($tpl->disGallery('search_advanced')) : ?>
					<a title="<?php echo __('Recherche avancée'); ?>" href="<?php echo $tpl->getLink('search-advanced'); ?>"><?php echo __('Recherche'); ?></a>&nbsp;:
<?php else : ?>
					<?php echo __('Recherche'); ?>&nbsp;:
<?php endif; ?>
					<form action="<?php echo $tpl->getGallery('page_url'); ?>" method="post">
						<div>
							<input value="<?php echo $tpl->getGallery('search_query'); ?>" maxlength="255" name="search_query" accesskey="4" class="text" type="text" />
							<input class="submit" type="submit" value="Ok" />
						</div>
					</form>
				</div>
<?php endif; ?>
<?php if ($tpl->disGallery('nav_neighbours')) : ?>
				<div class="browse">
					<?php echo __('Catégories voisines :'); ?>

					<select name="browse" onchange="window.location.href='<?php echo $tpl->getGallery('gallery_base_url'); ?>'+this.options[this.selectedIndex].value">
						<?php echo $tpl->getNeighbours(); ?>

					</select>
				</div>
<?php endif; ?>
			</div>
<?php endif; ?>
		</div>
		</div>
	</header>

	<div id="content">
		<div id="content_inner">

<?php $tpl->inc('page'); ?>

<?php if ($tpl->disRSS()) : ?>
			<ul id="rss_links"<?php if (method_exists($tpl, 'disNavigation') && $tpl->disNavigation('bottom')) : ?> class="no_border"<?php endif; ?>>
<?php if ($tpl->disRSS('comments')) : ?>
				<li><span><a title="<?php echo $tpl->getRSS('comments_desc'); ?>" href="<?php echo $tpl->getRSS('comments_url'); ?>"><?php echo __('fil des commentaires'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disRSS('images')) : ?>
				<li><span><a title="<?php echo $tpl->getRSS('images_desc'); ?>" href="<?php echo $tpl->getRSS('images_url'); ?>"><?php echo __('fil des images'); ?></a></span></li>
<?php endif; ?>
			</ul>
<?php endif; ?>

			<div style="clear:both"></div>
			<br />

		</div>

<?php if ($tpl->disWidgets('stats_categories,online_users,options,tags,links,default')) : ?>
		<div id="bottom">
<?php $tpl->getWidgets('stats_categories,online_users,options,tags,links,default'); ?>

		</div>
<?php endif; ?>
	</div>
	</div>

	<footer>
		<p id="powered_by">
			<?php echo $tpl->getGallery('powered_by'); ?>
			-
			<?php printf(__('icônes : %s'), '<a href="http://p.yusukekamiyamane.com/">Yusuke Kamiyamane</a>'); ?>
		</p>
<?php if ($tpl->disGallery('exec_time')) : ?>
		<p><?php echo $tpl->getGallery('exec_time'); ?></p>
<?php endif; ?>
<?php if ($tpl->disGallery('footer_message')) : ?>
		<p><?php echo $tpl->getGallery('footer_message'); ?></p>
<?php endif; ?>
	</footer>

<?php $tpl->displayErrors(); ?>

<?php echo $tpl->getDebugSQL(); ?>

</div>

<?php
if ($tpl->disGallery('diaporama') && ($_GET['album_page'] || $_GET['section'] == 'image'))
{
	include_once(dirname(__FILE__) . '/diaporama.tpl.php');
}
?>

<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/jquery.js"></script>
<?php if ($_GET['section'] == 'cameras') : ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/tablesorter.js"></script>
<?php endif; ?>
<?php if (strstr($_GET['section'], 'watermark')) : ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/farbtastic/farbtastic.js"></script>
<?php endif; ?>
<?php if ($tpl->disGallery('geoloc') && (($_GET['section'] == 'image' && $tpl->disWidgets('geoloc')) || $_GET['section'] == 'worldmap')) : ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $tpl->getGallery('geoloc_key'); ?>"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getGallery('template_path'); ?>/js/gallery.js"></script>
<?php if ($_GET['section'] == 'upload') : ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/upload.js"></script>
<?php endif; ?>
<?php if ($tpl->disCaptcha()) : ?>
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
<?php endif; ?>
<script type="text/javascript">
//<![CDATA[
var gallery_path = "<?php echo $tpl->getGallery('gallery_path'); ?>";
var style_path = "<?php echo $tpl->getGallery('style_path'); ?>";
//]]>
</script>

<?php if (!CONF_INTEGRATED) : ?>
</body>


</html>
<?php endif; ?>