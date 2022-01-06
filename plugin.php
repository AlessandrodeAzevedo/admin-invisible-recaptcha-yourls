<?php
/*
Plugin Name: Admin Invisible reCaptcha
Plugin URI: https://github.com/AlessandrodeAzevedo/admin-invisible-recaptcha-yourls
Description: Enables Google's invisible reCaptcha on Admin login screen.
Version: 1.0
Author: Alessandro de Azevedo
Author URI: https://github.com/alessandrodeazevedo
*/

if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Add the JavaScript for reCaptcha widget
yourls_add_action( 'login_form_top', 'invisible_recaptcha_js' );
function invisible_recaptcha_js() { ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback"></script>
	<script>
        $(document).ready(function() {
            $('#login form').first().append('<div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_PUBLIC_KEY ?>" data-callback="onSubmit" data-size="invisible"></div>');
            $('#login form').first().append('<input type="hidden" id="captcha-response" name="captcha-response" />');
        });
        var onSubmit = function(token = null) {
            document.getElementById('captcha-response').value = token;
        }
        var onloadCallback = function() {
            try {
                grecaptcha.render('captcha-response', {
                    'sitekey' : '<?= RECAPTCHA_PUBLIC_KEY ?>',
                    'callback' : onSubmit,
                    'size': 'invisible'
                });
                grecaptcha.execute();
            } catch(error) {
                grecaptcha.reset();
            }
        }
	</script> 
	<?php
}

// Backend validate
yourls_add_action( 'pre_login_username_password', 'recaptcha_validate' );
function recaptcha_validate() {
    //Verify request method and the pages is right
    if(
        in_array(
            $_SERVER["HTTP_REFERER"],
            [
                yourls_admin_url( 'index.php' ), 
                yourls_admin_url()
            ]
        ) && 
        !empty($_REQUEST) && $_SERVER['REQUEST_METHOD'] == "POST"
    ){
        if (
            !empty($_REQUEST['captcha-response']) && 
            !empty(RECAPTCHA_PRIVATE_KEY) && 
            !empty(RECAPTCHA_PRIVATE_KEY)
        ) {
            require_once YOURLS_ABSPATH . '/user/plugins/invisible-recapcha-admin/recaptcha/src/autoload.php';
            $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_PRIVATE_KEY);
            $resp_recaptcha = $recaptcha->verify($_REQUEST['captcha-response']);
            if (!$resp_recaptcha->isSuccess()) {
                yourls_do_action( 'login_failed' );
                yourls_login_screen( 'reCaptcha validation failed (Invalid captcha)' );
                return false;
            }
        } else {
            yourls_do_action( 'login_failed' );
            yourls_login_screen( 'reCaptcha validation failed (Empty captcha)' );
            return false;
        }
	}
}
