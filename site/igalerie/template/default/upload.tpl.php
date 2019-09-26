		<div class="box" id="upload_box">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Ajouter des images'); ?></h2></div></td></tr>
				<tr>
					<td>
						<form action="<?php echo $tpl->getGallery('page_url'); ?>" method="post" id="upload_form">
							<fieldset>
<?php if ($tpl->disReport()) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<p><?php echo __('Choisissez l\'album dans lequel vous souhaitez envoyer des images :'); ?></p>
								<div id="upload_categories"></div>

								<p class="message message_info" id="select_path"><?php echo __('Aucun album sélectionné.'); ?></p>
								<p class="field"><?php echo __('Images à ajouter à l\'album :'); ?></p>
								<div id="upload">
									<div id="upload_list">
										<p id="upload_startmsg"><?php echo __('Déposez vos images ici.'); ?></p>
									</div>
									<div id="upload_infos">
										<p id="upload_infos_total">
											<span><?php echo __('Liste :'); ?></span>
											<span id="upload_infos_images"></span>
											-
											<span id="upload_infos_filesize"></span>
										</p>
										<p>
											<span><?php echo __('Envoyé :'); ?></span>
											<span id="upload_infos_progress_pc">0%</span>
										</p>
									</div>
<?php if ($tpl->disValidate()) : ?>
									<p class="message message_info"><?php echo __('Vos images n\'apparaîtront dans la galerie qu\'après validation par un administrateur.'); ?></p>
<?php endif; ?>
									<p class="message message_info"><?php printf(__('Vos images doivent être au format JPEG, GIF ou PNG uniquement et faire %s Ko et %s pixels maximum par fichier.'), $tpl->getLimits('maxfilesize'), $tpl->getLimits('maxsize')); ?></p>
									<div id="upload_buttons">
										<input style="display:none" type="file" id="upload_input_file" multiple accept="image/*">
										<a id="upload_add" href="javascript:;"><?php echo __('Ajouter des fichiers'); ?></a>
										<a id="upload_clear" href="javascript:;"><?php echo __('Vider la liste'); ?></a>
										<a id="upload_start" href="javascript:;"><?php echo __('Envoyer'); ?></a>
									</div>
									<script type="text/javascript">
									var upload_options =
									{
										ajaxData:
										{
											section: 'upload-image',
											from: 'gallery',
											id: <?php echo ($_GET['object_id'] > 1) ? $_GET['object_id'] : 'null'; ?>,
											anticsrf: '<?php echo $tpl->getGallery('anticsrf'); ?>',
											session_token: '<?php echo $tpl->getAuthUser('session_token'); ?>',
											tempdir: '<?php echo $tpl->getTempDir(); ?>'
										},
										ajaxScript: '<?php echo $tpl->getGallery('gallery_path'); ?>' + '/ajax.php',
										l10n:
										{
											images: "<?php echo $tpl->getL10nJS(__('%s images')); ?>",
											sizeUnits: ["<?php echo $tpl->getL10nJS(__('%s Ko')); ?>", "<?php echo $tpl->getL10nJS(__('%s Mo')); ?>"],
											decimalPoint: "<?php echo $tpl->getL10nJS(__(',')); ?>",
											warning:
											{
												filename: "<?php echo $tpl->getL10nJS(__('Nom de fichier incorrect.')); ?>",
												filesize: "<?php echo $tpl->getL10nJS(__('Poids du fichier trop grand.')); ?>",
												filetype: "<?php echo $tpl->getL10nJS(__('Type de fichier non valide.')); ?>",
												sameFilename: "<?php echo $tpl->getL10nJS(__('Une autre image possède le même nom de fichier.')); ?>",
												totalfiles: "<?php echo $tpl->getL10nJS(__('Nombre maximum de fichiers atteint.')); ?>",
												totalsize: "<?php echo $tpl->getL10nJS(__('Poids maximum atteint.')); ?>"
											},
											failed: "<?php echo $tpl->getL10nJS(__('Échec de l\'envoi du fichier.')); ?>",
											success: "<?php echo $tpl->getL10nJS(__('Envoi effectué.')); ?>",
											noAlbum: "<?php echo $tpl->getL10nJS(__('Aucun album sélectionné.')); ?>"
										},
										maxFilesize: <?php echo $tpl->getMaxFileSize(); ?>,
										maxTotalFiles: 50,
										maxTotalSize: 52428800,
										maxFileNameLength: 45
									};
									<?php echo $tpl->getAlbumsList(); ?>

									var l10n_upload_album = "<?php echo $tpl->getL10nJS(__('Vous devez d\'abord sélectionner un album.')); ?>";
									var cat_separator = '<?php echo $tpl->getGallery('level_separator'); ?>';
									</script>
									<input name="cat_id" type="hidden" value="<?php echo ($_GET['object_id'] > 1) ? $_GET['object_id'] : ''; ?>" />
									<input name="tempdir" type="hidden" value="<?php echo $tpl->getTempDir(); ?>" />
									<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
								</div>
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
		</div>
<?php include(dirname(__FILE__) . '/user_menu.tpl.php'); ?>
