<?php
/*
 * Login page.
 */

if (!isset($version)) {exit;}

if (isset($msg) && $msg != '') {echo '<div class="alert alert-warning" role="alert">' . $msg . '</div>';}
?>
<h1 class="login-h1 text-center"><?php echo _('Cloudflare CNAME/IP/NS Setup'); ?></h1>
<form class="form-signin text-center" method="POST" action="">
	<h1 class="h3 mb-3 font-weight-normal"><?php echo _('Please sign in'); ?></h1>
	<label for="inputEmail" class="sr-only"><?php echo _('Your email address on cloudflare.com'); ?></label>
	<input type="email" name="cloudflare_email" id="inputEmail" class="form-control" placeholder="<?php echo _('Your email address on cloudflare.com'); ?>" required autofocus>
	<label for="inputPassword" class="sr-only"><?php echo _('Your password on cloudflare.com'); ?></label>
	<input type="password" name="cloudflare_pass" id="inputPassword" class="form-control" placeholder="<?php echo _('Your password on cloudflare.com'); ?>" required>
	<div class="checkbox mb-3">
		<label>
			<input type="checkbox" value="remember-me" name="remember"> <?php echo _('Remember me'); ?>
		</label>
	</div>
	<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _('Sign in'); ?></button>
	<p class="mt-3 text-muted"><?php echo _('Use your existing account or create a new account here.'); ?></p>
	<p class="text-muted"><?php echo _('We will not store any of your Cloudflare data'); ?></p>
</form>
