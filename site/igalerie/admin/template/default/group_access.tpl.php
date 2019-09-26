
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/group_related.tpl.php'); ?>

		<form action="<?php echo $tpl->getAdmin('page_url'); ?>" method="post" class="nolegend" id="group_edit">
			<div>
				<fieldset>
<?php if ($_GET['object_id'] > 1) : ?>
					<div class="group_categories" id="group_blacklist">
					<div>
						<p class="field">
							<input<?php echo $tpl->getListType('black'); ?> type="radio" id="blacklist" name="list" value="black" />
							<label for="blacklist"><?php echo __('Blocage par liste noire'); ?></label>
							<span class="group_list_info"><?php echo __('Autorise par défaut l\'accès à toutes les catégories<br />et interdit l\'accès aux catégories au cas par cas.'); ?></span>
						</p>
						<?php echo $tpl->getList('black'); ?>

					</div>
					</div>
					<div class="group_categories" id="group_whitelist">
					<div>
						<p class="field">
							<input<?php echo $tpl->getListType('white'); ?> type="radio" id="whitelist" name="list" value="white" />
							<label for="whitelist"><?php echo __('Blocage par liste blanche'); ?></label>
							<span class="group_list_info"><?php echo __('Interdit par défaut l\'accès à toutes les catégories<br />et autorise l\'accès aux catégories au cas par cas.'); ?></span>
						</p>
						<?php echo $tpl->getList('white'); ?>

					</div>
					</div>
					<hr class="clear" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
<?php else : ?>
					<div class="report_msg report_info">
						<p><?php echo __('Aucune permission pour ce groupe.'); ?></p>
					</div>
<?php endif; ?>
				</fieldset>
			</div>
		</form>
