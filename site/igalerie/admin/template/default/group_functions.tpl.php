
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/group_related.tpl.php'); ?>

		<form action="" method="post" class="nolegend" id="group_edit">
			<div>
				<fieldset>
<?php if ($_GET['object_id'] > 1) : ?>
					<div class="group_functions">
						<div>
							<h4><?php echo __('Galerie'); ?></h4>
							<input name="gallery[perm]" type="hidden" value="1" />
							<h5><?php echo __('Commentaires et votes'); ?></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('read_comments'); ?> name="gallery[read_comments]" id="read_comments" type="checkbox" />
								<span><label for="read_comments"><?php echo __('Lecture des commentaires'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('add_comments'); ?> name="gallery[add_comments]" id="add_comments" type="checkbox" />
								<span><label for="add_comments"><?php echo __('Ajout de commentaires :'); ?></label></span>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getGalleryGroupPerm('add_comments_mode_1'); ?> name="gallery[add_comments_mode]" value="1" id="add_comments_direct" type="radio" />
									<span><label for="add_comments_direct"><?php echo __('directement'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGalleryGroupPerm('add_comments_mode_0'); ?> name="gallery[add_comments_mode]" value="0" id="add_comments_pending" type="radio" />
									<span><label for="add_comments_pending"><?php echo __('en attente de validation'); ?></label></span>
								</p>
							</div>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('votes'); ?> name="gallery[votes]" id="votes" type="checkbox" />
								<span><label for="votes"><?php echo __('Votes'); ?></label></span>
							</p>
<?php if ($_GET['object_id'] != 2) : ?>
							<h5><?php echo __('Ajout d\'images et création d\'albums'); ?></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('upload'); ?> name="gallery[upload]" id="upload_perm" type="checkbox" />
								<span><label for="upload_perm"><?php echo __('Ajout d\'images :'); ?></label></span>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getGalleryGroupPerm('upload_mode_1'); ?> name="gallery[upload_mode]" value="1" id="upload_direct" type="radio" />
									<span><label for="upload_direct"><?php echo __('directement'); ?></label></span>
								</p>
								<p class="field checkbox">
									<input<?php echo $tpl->getGalleryGroupPerm('upload_mode_0'); ?> name="gallery[upload_mode]" value="0" id="upload_pending" type="radio" />
									<span><label for="upload_pending"><?php echo __('en attente de validation'); ?></label></span>
								</p>
							</div>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('create_albums'); ?> name="gallery[create_albums]" id="create_albums" type="checkbox" />
								<span><label for="create_albums"><?php echo __('Création d\'albums'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('upload_create_owner'); ?> name="gallery[upload_create_owner]" value="1" id="upload_create_owner" type="checkbox" />
								<span><label for="upload_create_owner"><?php echo __('Ajout d\'images et création d\'albums uniquement dans les catégories dont l\'utilisateur est propriétaire'); ?></label></span>
							</p>
<?php endif; ?>
							<h5><?php echo __('Divers'); ?></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('image_original'); ?> name="gallery[image_original]" id="image_original" type="checkbox" />
								<span><label for="image_original"><?php echo __('Accès aux images originales'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
								<a rel="h_image_original" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('members_list'); ?> name="gallery[members_list]" id="members_list" type="checkbox" />
								<span><label for="members_list"><?php echo __('Liste des membres'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('download_albums'); ?> name="gallery[download_albums]" id="download_albums" type="checkbox" />
								<span><label for="download_albums"><?php echo __('Téléchargement d\'albums'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('options'); ?> name="gallery[options]" id="options" type="checkbox" />
								<span><label for="options"><?php echo __('Options d\'affichage'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('adv_search'); ?> name="gallery[adv_search]" id="adv_search" type="checkbox" />
								<span><label for="adv_search"><?php echo __('Recherche avancée'); ?></label></span>
							</p>
<?php if ($_GET['object_id'] != 2) : ?>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('alert_email'); ?> name="gallery[alert_email]" id="alert_email" type="checkbox" />
								<span><label for="alert_email"><?php echo __('Notifications par courriel'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getGalleryGroupPerm('edit'); ?> name="gallery[edit]" id="edit_infos" type="checkbox" />
								<span><label for="edit_infos"><?php echo __('Édition des informations des images et catégories'); ?></label></span>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<input<?php echo $tpl->getGalleryGroupPerm('edit_owner'); ?> name="gallery[edit_owner]" value="1" id="edit_owner" type="checkbox" />
									<span><label for="edit_owner"><?php echo __('uniquement pour celles dont l\'utilisateur est propriétaire'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
								<a rel="h_edit_owner" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
								</p>
							</div>
<?php endif; ?>
						</div>
					</div>
<?php if ($tpl->disAdminGroupPerm()) : ?>
					<div class="group_functions">
						<h4><?php echo __('Administration'); ?></h4>
						<input name="admin[perm]" type="hidden" value="1" />
						<div id="admin_rights">
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('all'); ?> name="admin[all]" id="all_perms" type="checkbox" />
								<span><label for="all_perms"><?php echo __('Accès total'); ?></label></span>
							</p>
							<p class="field checkbox">
								<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
								-
								<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
							</p>
							<h5><span class="ftp"><?php echo __('FTP'); ?></span></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('ftp'); ?> class="selectable ftp" name="admin[ftp]" id="ftp" type="checkbox" />
								<span><label for="ftp"><?php echo __('Ajout d\'images par FTP'); ?></label></span>
							</p>
							<h5><span class="objects"><?php echo __('Objets'); ?></span></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('albums_modif'); ?> class="selectable objects" name="admin[albums_modif]" id="albums_modif" type="checkbox" />
								<span><label for="albums_modif"><?php echo __('Modification des images'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('albums_edit'); ?> class="selectable objects" name="admin[albums_edit]" id="albums_edit" type="checkbox" />
								<span><label for="albums_edit"><?php echo __('Édition des informations des images et catégories'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('albums_pending'); ?> class="selectable objects" name="admin[albums_pending]" id="albums_pending" type="checkbox" />
								<span><label for="albums_pending"><?php echo __('Gestion des images en attente de validation'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('albums_add'); ?> class="selectable objects" name="admin[albums_add]" id="albums_add" type="checkbox" />
								<span><label for="albums_add"><?php echo __('Ajout d\'images par le navigateur'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('comments_edit'); ?> class="selectable objects" name="admin[comments_edit]" id="comments_edit" type="checkbox" />
								<span><label for="comments_edit"><?php echo __('Gestion et édition des commentaires'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('comments_options'); ?> class="selectable objects" name="admin[comments_options]" id="comments_options" type="checkbox" />
								<span><label for="comments_options"><?php echo __('Options sur les commentaires'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('admin_votes'); ?> class="selectable objects" name="admin[admin_votes]" id="admin_votes" type="checkbox" />
								<span><label for="admin_votes"><?php echo __('Gestion des votes'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('tags'); ?> class="selectable objects" name="admin[tags]" id="tags" type="checkbox" />
								<span><label for="tags"><?php echo __('Gestion des tags et des appareils photos'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('users_members'); ?> class="selectable objects" name="admin[users_members]" id="users_members" type="checkbox" />
								<span><label for="users_members"><?php echo __('Gestion des utilisateurs'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('users_options'); ?> class="selectable objects" name="admin[users_options]" id="users_options" type="checkbox" />
								<span><label for="users_options"><?php echo __('Options sur les utilisateurs'); ?></label></span>
							</p>
							<h5><span class="settings"><?php echo __('Réglages'); ?></span></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('settings_pages'); ?> class="selectable settings" name="admin[settings_pages]" id="settings_pages" type="checkbox" />
								<span><label for="settings_pages"><?php echo __('Gestion des pages'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('settings_widgets'); ?> class="selectable settings" name="admin[settings_widgets]" id="settings_widgets" type="checkbox" />
								<span><label for="settings_widgets"><?php echo __('Gestion des widgets'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('settings_functions'); ?> class="selectable settings" name="admin[settings_functions]" id="settings_functions" type="checkbox" />
								<span><label for="settings_functions"><?php echo __('Gestion des fonctionnalités'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('settings_options'); ?> class="selectable settings" name="admin[settings_options]" id="settings_options" type="checkbox" />
								<span><label for="settings_options"><?php echo __('Options générales'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('settings_themes'); ?> class="selectable settings" name="admin[settings_themes]" id="settings_themes" type="checkbox" />
								<span><label for="settings_themes"><?php echo __('Gestion des thèmes'); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('settings_maintenance'); ?> class="selectable settings" name="admin[settings_maintenance]" id="settings_maintenance" type="checkbox" />
								<span><label for="settings_maintenance"><?php echo __('Accès aux outils de maintenance'); ?></label></span>
							</p>
							<h5><span class="infos"><?php echo __('Informations'); ?></span></h5>
							<p class="field checkbox">
								<input<?php echo $tpl->getAdminGroupPerm('infos_incidents'); ?> class="selectable infos" name="admin[infos_incidents]" id="infos_incidents" type="checkbox" />
								<span><label for="infos_incidents"><?php echo __('Accès aux rapports d\'incidents'); ?></label></span>
							</p>
						</div>
					</div>
<?php endif; ?>
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
