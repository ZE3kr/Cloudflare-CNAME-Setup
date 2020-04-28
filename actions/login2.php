<?php
/*
 * login2.php should be used instead of login.php, when there is no available Host API key.
 */

if (!isset($version)) {exit;}

if (isset($msg) && $msg != '') {echo '<div class="alert alert-warning" role="alert">' . $msg . '</div>';}
?>
<h1 class="login-h1 text-center"><?php echo _('Cloudflare CNAME/IP/NS Setup'); ?></h1>
<?php if($no_api_key){
	if(isset($tlo_promotion_header)){
		echo $tlo_promotion_header;
	} else {
		echo '<div class="alert alert-warning" role="alert">' . _('No Host API key found. You cannot add new domain to this service.') . '</div>';
	}
} ?>
<form class="form-signin text-center" method="POST" action="">
	<h1 class="h3 mb-3 font-weight-normal"><?php echo _('Please sign in'); ?></h1>
	<label for="inputEmail" class="sr-only"><?php echo _('Your email address on cloudflare.com'); ?></label>
	<input type="email" name="cloudflare_email" id="inputEmail" class="form-control" placeholder="<?php echo _('Your email address on cloudflare.com'); ?>" required autofocus>
	<label for="inputPassword" class="sr-only"><?php echo _('Your global API key on cloudflare.com'); ?></label>
	<input type="password" name="cloudflare_api" id="inputPassword" class="form-control" minlength="37" maxlength="37" pattern="[0-9a-fA-F]{37}"
		   title="<?php echo _('Your global API key. NOT your password.');?>" placeholder="<?php echo _('Your global API key on cloudflare.com'); ?>" required>
	<div class="checkbox mb-3">
		<label>
			<input type="checkbox" value="remember-me" name="remember"> <?php echo _('Remember me'); ?>
		</label>
	</div>
	<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _('Sign in'); ?></button>
	<p class="mt-3 text-muted"><a href="https://support.cloudflare.com/hc/en-us/articles/200167836-Managing-API-Tokens-and-Keys#12345682"><?php echo _('How to get my global API key?'); ?></a></p>
	<p class="text-muted"><?php echo _('We will not store any of your Cloudflare data'); ?></p>
	<?php if($no_api_key && isset($tlo_promotion_footer)){
		echo $tlo_promotion_footer;
	} ?>
</form>
