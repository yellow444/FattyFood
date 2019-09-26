<?php $item = ($_GET['section'] == 'image') ? 'image' : 'page'; ?>

<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="first"><a title="<?php printf(__('Première %s'), $item); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="prev"><a title="<?php printf(__('%s précédente'), ucfirst($item)); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

<?php if ($_GET['section'] != 'image') : ?>
			<form action="<?php echo $tpl->getGallery('page_url'); ?>" method="get">
				<div>
					<select name="page" onchange="window.location.href='<?php echo $tpl->getNavigation('link'); ?>'+this.options[this.selectedIndex].value">
						<?php echo $tpl->getNavigation('html_options'); ?>

					</select>
				</div>
			</form>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="next"><a title="<?php printf(__('%s suivante'), ucfirst($item)); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="last"><a title="<?php printf(__('Dernière %s'), $item); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>
