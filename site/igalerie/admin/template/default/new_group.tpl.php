
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

		<span id="back"><a href="<?php echo $tpl->getLink('groups'); ?>"><?php echo __('retour'); ?></a></span>
		<h3><?php echo __('Nouveau groupe'); ?></h3>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/group_infos.tpl.php'); ?>
