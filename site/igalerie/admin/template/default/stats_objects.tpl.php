<?php include_once(dirname(__FILE__) . '/stats_submenu.tpl.php'); ?>

		<div class="objects_stats">
			<table class="default" summary="">
				<tbody>
					<tr class="objects_stats_sub"><td><?php echo __('Images'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre d\'images'); ?></td>
						<td><?php echo $tpl->getStat('images_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Poids total'); ?></td>
						<td><?php echo $tpl->getStat('images_filesize_total'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Poids moyen'); ?></td>
						<td><?php echo $tpl->getStat('images_filesize_average'); ?></td>
					</tr>

					<tr class="objects_stats_sub"><td><?php echo __('Catégories'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre de catégories'); ?></td>
						<td><?php echo $tpl->getStat('categories_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre d\'albums'); ?></td>
						<td><?php echo $tpl->getStat('albums_count'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Nombre d\'images par album'); ?></td>
						<td><?php echo $tpl->getStat('images_per_album'); ?></td>
					</tr>

					<tr class="objects_stats_sub"><td><?php echo __('Tags'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre de tags distincts'); ?></td>
						<td><?php echo $tpl->getStat('tags_distinct_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre total de tags'); ?></td>
						<td><?php echo $tpl->getStat('tags_total_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre d\'images taggées'); ?></td>
						<td><?php echo $tpl->getStat('tags_images_count'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Nombre de tags par image'); ?></td>
						<td><?php echo $tpl->getStat('tags_per_image'); ?></td>
					</tr>

					<tr class="objects_stats_sub"><td><?php echo __('Visites'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre de visites'); ?></td>
						<td><?php echo $tpl->getStat('hits_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre d\'images visitées'); ?></td>
						<td><?php echo $tpl->getStat('hits_images_count'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Nombre de visites par image'); ?></td>
						<td><?php echo $tpl->getStat('hits_per_image'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="objects_stats">
			<table class="default" summary="">
				<tbody>
					<tr class="objects_stats_sub"><td><?php echo __('Utilisateurs'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre d\'administrateurs'); ?></td>
						<td><?php echo $tpl->getStat('users_admins_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre de membres'); ?></td>
						<td><?php echo $tpl->getStat('users_members_count'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Nombre de groupes'); ?></td>
						<td><?php echo $tpl->getStat('users_groups_count'); ?></td>
					</tr>

					<tr class="objects_stats_sub"><td><?php echo __('Commentaires'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre de commentaires'); ?></td>
						<td><?php echo $tpl->getStat('comments_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre d\'images commentées'); ?></td>
						<td><?php echo $tpl->getStat('comments_images_count'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Nombre de commentaires par image'); ?></td>
						<td><?php echo $tpl->getStat('comments_per_image'); ?></td>
					</tr>

					<tr class="objects_stats_sub"><td><?php echo __('Votes'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre de votes'); ?></td>
						<td><?php echo $tpl->getStat('votes_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre d\'images notées'); ?></td>
						<td><?php echo $tpl->getStat('votes_images_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre de votes par image'); ?></td>
						<td><?php echo $tpl->getStat('votes_per_image'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Note moyenne'); ?></td>
						<td><?php echo $tpl->getStat('votes_average_rate'); ?></td>
					</tr>

					<tr class="objects_stats_sub"><td><?php echo __('Favoris'); ?></td><td class="objects_stats_sub_last"></td></tr>
					<tr>
						<td><?php echo __('Nombre de favoris'); ?></td>
						<td><?php echo $tpl->getStat('favorites_count'); ?></td>
					</tr>
					<tr>
						<td><?php echo __('Nombre d\'images mis en favoris'); ?></td>
						<td><?php echo $tpl->getStat('favorites_images_count'); ?></td>
					</tr>
					<tr class="objects_stats_last">
						<td><?php echo __('Nombre de favoris par utilisateur'); ?></td>
						<td><?php echo $tpl->getStat('favorites_per_user'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
