<div style="display:none" id="diaporama">
	<div id="diaporama_loading"></div>
	<div class="diaporama_bar" id="diaporama_top">
		<p id="diaporama_top_left"></p>
		<p id="diaporama_top_right">
<?php if ($tpl->disGallery('download_image')) : ?>
			<a id="diaporama_icon_download" title="<?php echo __('Télécharger l\'image'); ?>" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/download.png" alt="" /></a>
<?php endif; ?>
<?php if ($tpl->disAuthUser()) : ?>
			<a id="diaporama_icon_fav" title="<?php echo __('Ajouter aux favoris'); ?>" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/fav.png" alt="" /></a>
<?php endif; ?>
<?php if ($tpl->disGallery('basket')) : ?>
			<a id="diaporama_icon_basket" title="<?php echo __('Ajouter au panier'); ?>" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/basket.png" alt="" /></a>
<?php endif; ?>
<?php if ($tpl->disAuthUser() && $tpl->disPerm('edit')) : ?>
			<a id="diaporama_icon_edit" title="<?php echo __('Édition'); ?>" class="diaporama_icon diaporama_icon_sidebar"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/edit.png" alt="" /></a>
<?php endif; ?>
<?php if ($tpl->disGallery('widget_stats_images')) : ?>
			<a id="diaporama_icon_infos" title="<?php echo __('Informations'); ?>" class="diaporama_icon diaporama_icon_sidebar"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/infos.png" alt="" /></a>
<?php endif; ?>
			<a id="diaporama_icon_options" title="<?php echo __('Options'); ?>" class="diaporama_icon diaporama_icon_sidebar"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/options.png" alt="" /></a>
			<a id="diaporama_icon_close" title="<?php echo __('Fermer'); ?>" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/cross.png" alt="" /></a>
		</p>
	</div>
	<div class="diaporama_bar" id="diaporama_bottom">
		<p id="diaporama_bottom_left">
			<span id="diaporama_image_position"></span>
		</p>
		<p id="diaporama_bottom_center">
			<a id="diaporama_icon_first" class="diaporama_icon"><img width="30" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/first-grey.png" alt="" /></a>
			<a id="diaporama_icon_prev" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/prev-grey.png" alt="" /></a>
			<a id="diaporama_icon_switch" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/resize-grey.png" alt="" /></a>
			<a id="diaporama_icon_next" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/next-grey.png" alt="" /></a>
			<a id="diaporama_icon_last" class="diaporama_icon"><img width="30" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/last-grey.png" alt="" /></a>
		</p>
		<p id="diaporama_bottom_right">
			<a id="diaporama_icon_start" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/start.png" alt="" /></a>
			<a id="diaporama_icon_stop" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/stop-active.png" alt="" /></a>
			<span id="diaporama_seconds"></span>
			<a id="diaporama_icon_less" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/less.png" alt="" /></a>
			<a id="diaporama_icon_more" class="diaporama_icon"><img width="20" height="20" src="<?php echo $tpl->getGallery('style_path'); ?>/diaporama/more.png" alt="" /></a>
		</p>
	</div>
	<div id="diaporama_carousel">
		<div id="diaporama_carousel_prev"><a></a></div>
		<div id="diaporama_carousel_next"><a></a></div>
		<div id="diaporama_carousel_thumbs"></div>
	</div>
	<div class="diaporama_sidebar" id="diaporama_options">
		<p class="diaporama_sidebar_title">
			<a class="diaporama_sidebar_close"></a>
			<?php echo __('Options'); ?>

		</p>
		<div class="diaporama_sidebar_inner">
			<p class="field field_ftw">
				<label for="diaporama_transitions_effect"><?php echo __('Effet de transition :'); ?></label>
				<select class="diaporama_focus" id="diaporama_transitions_effect">
					<option value="none"><?php echo __('Aucun'); ?></option>
					<option value="fade"><?php echo __('Fondu'); ?></option>
					<option value="slideX"><?php echo __('Défilement horizontal'); ?></option>
					<option value="slideY"><?php echo __('Défilement vertical'); ?></option>
					<option value="slideXLeft"><?php echo __('Diapositive - gauche'); ?></option>
					<option value="slideYBottom"><?php echo __('Diapositive - bas'); ?></option>
					<option value="curtainX"><?php echo __('Rideau horizontal'); ?></option>
					<option value="curtainY"><?php echo __('Rideau vertical'); ?></option>
					<option value="puff"><?php echo __('Souffle'); ?></option>
					<option value="zoom"><?php echo __('Zoom'); ?></option>
					<option value="random"><?php echo __('Aléatoire'); ?></option>
				</select>
			</p>
			<p class="field field_ftw">
				<label for="diaporama_transitions_duration"><?php echo __('Durée de transition (en millisecondes) :'); ?></label>
				<input id="diaporama_transitions_duration" class="text" type="text" maxlength="4" size="4" />
			</p>
			<br />
			<p class="field">
				<input id="diaporama_carousel_option" type="checkbox" />
				<label for="diaporama_carousel_option"><?php echo __('Afficher le carrousel'); ?></label>
			</p>
			<p class="field">
				<input id="diaporama_autoloop" type="checkbox" />
				<label for="diaporama_autoloop"><?php echo __('Lecture en boucle du mode automatique'); ?></label>
			</p>
			<p class="field">
				<input id="diaporama_hidebars" type="checkbox" />
				<label for="diaporama_hidebars"><?php echo __('Cacher les barres de contrôle'); ?></label>
			</p>
			<p class="field">
				<input id="diaporama_animate" type="checkbox" />
				<label for="diaporama_animate"><?php echo __('Activer les animations'); ?></label>
			</p>
			<div id="diaporama_keyboard">
				<p><?php echo __('Navigation au clavier :'); ?></p>
				<ul>
					<li><?php echo __('Espace : démarre ou arrête la lecture automatique.'); ?></li>
					<li><?php echo __('Moins (pavé num.) : diminue la durée d\'affichage en lecture automatique.'); ?></li>
					<li><?php echo __('Plus (pavé num.) : augmente la durée d\'affichage en lecture automatique.'); ?></li>
					<li><?php echo __('Point (pavé num.) : switch taille réelle / taille redimensionnée.'); ?></li>
					<li><?php echo __('Flèche gauche : image précédente.'); ?></li>
					<li><?php echo __('Flèche droite : image suivante.'); ?></li>
					<li><?php echo __('Début : première image.'); ?></li>
					<li><?php echo __('Fin : dernière image.'); ?></li>
					<li><?php echo __('Page précédente : page de carrousel précédente.'); ?></li>
					<li><?php echo __('Page suivante : page de carrousel suivante.'); ?></li>
					<li><?php echo __('Echap : ferme le diaporama.'); ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="diaporama_sidebar" id="diaporama_infos">
		<p class="diaporama_sidebar_title">
			<a class="diaporama_sidebar_close"></a>
			<?php echo __('Informations'); ?>

		</p>
		<div class="diaporama_sidebar_inner">
			<ul class="diaporama_sidebar_ul">
				<li id="diaporama_sidebar_desc">
					<p class="diaporama_sidebar_title2"><span></span></p>
					<div class="diaporama_sidebar_content">
						<p></p>
					</div>
				</li>
				<li id="diaporama_sidebar_stats">
					<p class="diaporama_sidebar_title2"><span></span></p>
					<div class="diaporama_sidebar_content">
					</div>
				</li>
				<li id="diaporama_sidebar_tags">
					<p class="diaporama_sidebar_title2"><span></span></p>
					<div class="diaporama_sidebar_content">
					</div>
				</li>
				<li id="diaporama_sidebar_exif">
					<p class="diaporama_sidebar_title2"><span></span></p>
					<div class="diaporama_sidebar_content">
					</div>
				</li>
				<li id="diaporama_sidebar_iptc">
					<p class="diaporama_sidebar_title2"><span></span></p>
					<div class="diaporama_sidebar_content">
					</div>
				</li>
				<li id="diaporama_sidebar_xmp">
					<p class="diaporama_sidebar_title2"><span></span></p>
					<div class="diaporama_sidebar_content">
					</div>
				</li>
			</ul>
		</div>
	</div>
<?php if ($tpl->disAuthUser() && $tpl->disPerm('edit')) : ?>
	<div class="diaporama_sidebar" id="diaporama_edit">
		<p class="diaporama_sidebar_title">
			<a class="diaporama_sidebar_close"></a>
			<?php echo __('Édition'); ?>

		</p>
		<div class="diaporama_sidebar_inner">
			<form action="ajax.php" method="post">
				<div class="fielditems">
					<p class="field" id="diaporama_edit_langs">
						<label for="diaporama_edit_langs_select"><?php echo __('Langue d\'édition :'); ?></label>&nbsp;<select id="diaporama_edit_langs_select">
<?php while ($tpl->nextLang()) : ?>
							<option value="<?php echo $tpl->getLang('code'); ?>" <?php if ($tpl->disLang('default')) : ?> selected="selected"<?php endif; ?>><?php echo $tpl->getLang('name'); ?></option>
<?php endwhile; ?>
						</select>
					</p>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('default')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="diaporama_edit_title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
						<input name="title[<?php echo $tpl->getLang('code'); ?>]" id="diaporama_edit_title_<?php echo $tpl->getLang('code'); ?>" type="text" class="text diaporama_focus" maxlength="255" size="40" />
					</p>
<?php endwhile; ?>
					<p class="field_ftw">
						<label for="diaporama_edit_urlname"><?php echo __('Nom d\'URL :'); ?></label>
						<input id="diaporama_edit_urlname" type="text" class="text" maxlength="255" size="40" />
					</p>
<?php if ($tpl->disGallery('tags')) : ?>
					<p class="field_ftw">
						<label for="diaporama_edit_tags"><?php echo __('Tags (séparés par une virgule) :'); ?></label>
						<textarea class="diaporama_textarea_tags" id="diaporama_edit_tags" rows="3" cols="32"></textarea>
					</p>
<?php endif; ?>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('default')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="diaporama_edit_description_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
						<textarea name="desc[<?php echo $tpl->getLang('code'); ?>]" id="diaporama_edit_description_<?php echo $tpl->getLang('code'); ?>" rows="8" cols="32"></textarea>
					</p>
<?php endwhile; ?>
				</div>
				<p class="field">
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</p>
				<p id="diaporama_edit_loading"></p>
				<p class="message message_success">
					<span><?php echo __('Modifications enregistrées.'); ?></span>
				</p>
				<p class="message message_error"><span></span></p>
			</form>
		</div>
	</div>
<?php endif; ?>
	<span id="diaporama_message"></span>
</div>
<script type="text/javascript">
//<![CDATA[
var diaporama_query = "<?php echo $tpl->getDiaporama('query'); ?>";
var diaporama_prefs = <?php echo $tpl->getDiaporama('prefs'); ?>;
<?php if ($tpl->disGallery('basket')) : ?>
var diaporama_basket_add = "<?php echo $tpl->getL10nJS(__('Ajouter au panier')); ?>";
var diaporama_basket_del = "<?php echo $tpl->getL10nJS(__('Retirer du panier')); ?>";
<?php endif; ?>
<?php if ($tpl->disAuthUser()) : ?>
var diaporama_fav_add = "<?php echo $tpl->getL10nJS(__('Ajouter aux favoris')); ?>";
var diaporama_fav_del = "<?php echo $tpl->getL10nJS(__('Retirer des favoris')); ?>";
<?php endif; ?>
//]]>
</script>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/diaporama.js"></script>
