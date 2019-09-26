<?php if ($tpl->getGroup('id') != 2) : ?>
		<div id="obj_stats" class="obj_banner_box user_banner_box">
			<p class="obj_banner_box_link">
				<a title="<?php echo __('Statistiques'); ?>" href="javascript:;">
					<img width="20" height="20" alt="<?php echo __('Statistiques'); ?>" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/20x20/chart.png" />
				</a>
			</p>
			<div class="obj_banner_box_inner">
				<h3 class="obj_banner_box_title"><span><?php echo __('Statistiques'); ?></span></h3>
				<div>
					<table class="light">
						<tr>
							<td><?php echo __('Date de création'); ?></td>
							<td><?php echo $tpl->getGroup('crtdt'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Nombre d\'utilisateurs'); ?></td>
							<td><?php echo $tpl->getGroup('nb_members'); ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
<?php endif; ?>

		<div id="obj_banner">
			<div id="obj_banner_title">
				<div id="obj_banner_name">
					<span><?php echo $tpl->getStrLimit($tpl->getGroup('name'), 50); ?></span>
				</div>
			</div>
		</div>

		<div class="related">
			<label><?php echo __('Section :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<option value="group/<?php echo $tpl->getGroup('id'); ?>"<?php if ($_GET['section'] == 'group') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('informations'); ?></option>
				<option value="group-functions/<?php echo $tpl->getGroup('id'); ?>"<?php if ($_GET['section'] == 'group-functions') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('fonctionnalités'); ?></option>
				<option value="group-access/<?php echo $tpl->getGroup('id'); ?>"<?php if ($_GET['section'] == 'group-access') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('accès aux catégories'); ?></option>
			</select>
		</div>

		<h3>
<?php switch ($_GET['section']) : ?>
<?php case 'group' : ?>
			<?php echo __('Informations'); ?>
<?php break; ?>
<?php case 'group-access' : ?>
			<?php echo __('Accès aux catégories'); ?>
<?php break; ?>
<?php case 'group-functions' : ?>
			<?php echo __('Fonctionnalités'); ?>
<?php break; ?>
<?php case 'group-upload' : ?>
			<?php echo __('Ajout d\'images'); ?>
<?php break; ?>
<?php endswitch; ?>
		</h3>

		<div id="map_browse" class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<option value="groups"><?php echo __('gestion des groupes'); ?></option>
				<optgroup label="<?php echo __('Groupes'); ?>">
					<?php echo $tpl->getGroupsList(); ?>

				</optgroup>
			</select>
		</div>
		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>
