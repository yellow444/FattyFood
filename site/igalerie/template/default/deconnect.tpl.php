
<?php if ($tpl->disDeconnect()) : ?>
		<form id="deconnect_object" action="<?php echo $tpl->getGallery('page_url'); ?>" method="post">
			<p>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
				<input id="deconnect_object_input" name="deconnect_object" type="submit" value="<?php echo __('Déconnexion'); ?>" />
				<script type="text/javascript">document.getElementById('deconnect_object_input').style.display = 'none';</script>
			</p>
		</form>
<?php endif; ?>
