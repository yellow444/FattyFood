			<div class="obj_tool_box" id="obj_tool_search">
				<p class="obj_tool_title"><span><?php echo __('Recherche'); ?></span></p>
				<form class="obj_tool_body" method="post" action="<?php echo $tpl->getGallery('page_url'); ?>">
					<div class="fielditems">
						<p class="field"><?php echo ($tpl->getCategory('type') == 'album') ? __('Rechercher dans l\'album courant :') : __('Rechercher dans la catégorie courante :'); ?></p>
						<p class="field">
							<input value="<?php echo $tpl->getGallery('search_query'); ?>" maxlength="255" name="search_query" accesskey="4" class="text focus" type="text" />
						</p>
						<p class="igal_help">
							<?php echo __('Utilisez * pour remplacer n\'importe quelle suite de caractères.'); ?>

						</p>
<?php if ($tpl->disGallery('search_advanced')) : ?>
						<p class="field">
							<a href="<?php echo $tpl->getCategory('search_link'); ?>"><?php echo ($tpl->getCategory('type') == 'album') ? __('Recherche avancée dans cet album') : __('Recherche avancée dans cette catégorie'); ?></a>
						</p>
<?php endif; ?>
						<p class="buttons">
							<input type="hidden" name="search_category" value="<?php echo $tpl->getCategory('id'); ?>" />
							<input class="submit" type="submit" value="<?php echo __('Chercher'); ?>" />
							<input type="reset" class="cancel" value="<?php echo __('Annuler'); ?>" />
						</p>
					</div>
				</form>
			</div>