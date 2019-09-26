		<form action="" method="post" class="nolegend" id="group_edit">
			<div>
				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="name_<?php echo $tpl->getLang('code'); ?>"><?php echo (isset($_GET['object_id']) && $_GET['object_id'] < 4) ? __('Nom (laissez vide pour utiliser le nom par défaut) :') : __('Nom :'); ?></label>
						<input value="<?php echo $tpl->getGroupEdit('name'); ?>" maxlength="64" id="name_<?php echo $tpl->getLang('code'); ?>" name="name[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" />
					</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo (isset($_GET['object_id']) && $_GET['object_id'] < 4) ? __('Titre (laissez vide pour utiliser le titre par défaut) :') : __('Titre :'); ?></label>
						<input value="<?php echo $tpl->getGroupEdit('title'); ?>" maxlength="64" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text" />
					</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="desc_<?php echo $tpl->getLang('code'); ?>"><?php echo (isset($_GET['object_id']) && $_GET['object_id'] < 4) ? __('Description (laissez vide pour utiliser la description par défaut) :') : __('Description :'); ?></label>
						<textarea class="resizable" rows="4" cols="50" name="desc[<?php echo $tpl->getLang('code'); ?>]" id="desc_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getGroupEdit('desc'); ?></textarea>
					</p>
<?php endwhile; ?>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
