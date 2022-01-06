YOURLS Admin Invisible reCaptcha
====================

**Admin Invisible reCaptcha** is a plugin for [YOURLS](https://github.com/YOURLS/YOURLS) to protect logins with Google's No CAPTCHA reCAPTCHA.

Enables Google's invisible reCaptcha on Admin login screen.

Works with YOURLS v1.8.2.

Installation
------------
* In `/user/plugins`, create a new folder named `admin-invisible-recaptcha`
* Drop these files in that directory
* Add and replace default keys for your google's keys in `/user/config.php` as a example:
```
define( 'RECAPTCHA_PUBLIC_KEY', "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" );
define( 'RECAPTCHA_PRIVATE_KEY', "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe" );
```
* Go to the Plugins administration page and activate the plugin
