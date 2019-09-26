<meta charset="<?php echo $tpl->getGallery('charset'); ?>" />
<?php if ($tpl->disGallery('meta_description')) : ?>
<meta name="description" content="<?php echo $tpl->getGallery('meta_description'); ?>" />
<?php endif; ?>

<?php if ($tpl->disGallery('canonical_image')) : ?>
<link rel="canonical" href="<?php echo $tpl->getImage('canonical'); ?>" />
<?php endif; ?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tpl->getGallery('style_file'); ?>" />

<style type="text/css">
<?php while ($tpl->nextLang()) : ?>
.icon_<?php echo $tpl->getLang('code'); ?> {
	background-image: url(<?php echo $tpl->getGallery('gallery_path'); ?>/images/flags/<?php echo $tpl->getLang('code'); ?>.png);
}
<?php endwhile; ?>
<?php echo $tpl->getGallery('style_additional'); ?>

</style>

<?php if ($tpl->disGallery('rss')) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo $tpl->getRSS('images_desc_head'); ?>" href="<?php echo $tpl->getRSS('images_url_head'); ?>" />
<?php if ($tpl->disGallery('comments')) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo $tpl->getRSS('comments_desc_head'); ?>" href="<?php echo $tpl->getRSS('comments_url_head'); ?>" />
<?php endif; ?>
<?php endif; ?>
