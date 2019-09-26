		<div id="contact" class="box box_large box_notools">
<?php if ($tpl->disContact('field_error')) : ?>
			<script type="text/javascript">var field_error = '<?php echo $tpl->getContact('field_error'); ?>';</script>
<?php endif; ?>
			<table>
				<tr><td class="box_title"><div><h2><?php echo ucfirst(__('Contacter l\'administrateur')); ?></h2></div></td></tr>
<?php if ($tpl->disReport('success')) : ?>
				<tr><td class="box_edit">
					<br />
					<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				</td></tr>
<?php else : ?>
				<tr>
					<td class="box_edit">
						<form action="<?php echo $tpl->getGallery('page_url'); ?>" method="post">
							<fieldset>
<?php if ($tpl->disReport() && !$tpl->disReport('success')) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<div class="fielditems">
<?php if ($tpl->disContact('message')) : ?>
									<p id="contact_message"><?php echo $tpl->getContact('message'); ?></p>
<?php endif; ?>
									<p class="field field_ftw">
										<span class="required">*</span>
										<label for="f_name"><?php echo __('Votre nom :'); ?></label>
										<input value="<?php if (isset($_POST['name'])) : echo $_POST['name']; endif; ?>" class="text<?php if (empty($_POST)) : ?> focus<?php endif; ?>" type="text" maxlength="255" id="f_name" name="name" />
									</p>
									<p style="display:none" class="field field_ftw">
										<label for="email">Email :</label>
										<input value="" maxlength="255" class="text" id="email" name="f_email" type="text" />
									</p>
									<p class="field field_ftw">
										<span class="required">*</span>
										<label for="f_email"><?php echo __('Votre adresse courriel :'); ?></label>
										<input value="<?php if (isset($_POST['email'])) : echo $_POST['email']; endif; ?>" class="text" type="text" maxlength="255" id="f_email" name="email" />
									</p>
									<p class="field field_ftw">
										<span class="required">*</span>
										<label for="f_subject"><?php echo __('Sujet de votre message :'); ?></label>
										<input value="<?php if (isset($_POST['subject'])) : echo $_POST['subject']; endif; ?>" class="text" type="text" maxlength="255" id="f_subject" name="subject" />
									</p>
									<p class="field field_ftw">
										<span class="required">*</span>
										<label for="f_message"><?php echo __('Votre message :'); ?></label>
										<textarea id="f_message" name="message" rows="10" cols="50"><?php if (isset($_POST['message'])) : echo $_POST['message']; endif; ?></textarea>
									</p>
<?php if ($tpl->disCaptcha()) : ?>
									<p class="field">
										<span class="required">*</span>
										<span class="g-recaptcha" data-sitekey="<?php echo $tpl->getCaptcha('public_key'); ?>"></span>
									</p>
<?php endif; ?>
									<br />
									<p class="message message_info"><?php echo __('Les champs marqués d\'un astérisque sont obligatoires.'); ?></p>
									<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
									<input type="submit" class="submit" value="<?php echo __('Envoyer'); ?>" />
								</div>
							</fieldset>
						</form>
					</td>
				</tr>
<?php endif; ?>
			</table>
		</div>
