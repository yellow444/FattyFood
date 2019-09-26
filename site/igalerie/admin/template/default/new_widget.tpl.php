
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<span id="back"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('retour'); ?></a></span>
		<p id="position">
			<span class="current"><?php echo __('Nouveau widget'); ?></span>
<?php if ($tpl->disHelp()) : ?>
			<a rel="h_new_page_widget" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</p>

<?php include_once(dirname(__FILE__) . '/widget_text.tpl.php'); ?>
