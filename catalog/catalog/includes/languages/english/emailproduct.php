<?
/*
English Text for The Exchange Project Preview Release 2.2
Last Update: 04/24/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

define('NAVBAR_TITLE', 'Email Product');
define('TOP_BAR_TITLE', 'Email Product');
define('HEADING_TITLE', 'Tell a friend about this product');

define('TEXT_EMAILPRODUCT_EMAIL', 'Your Email Address:');
define('TEXT_EMAILPRODUCT_NAME', 'Your Name:');
define('TEXT_EMAILPRODUCT_FRIEND_EMAIL', 'Your Friends Email Address:');
define('TEXT_EMAILPRODUCT_MESSAGE', 'Your Message:');
define('TEXT_EMAILPRODUCT_TELLAFRIEND', 'Tell your friend about:');
define('TEXT_EMAILPRODUCT_YOUR_MAIL_ABOUT', 'Your email about');
define('TEXT_EMAILPRODUCT_HAS_BEEN_SENT', 'has been sent to -');

$email_subject = 'Your friend ' . $yourname . ' has recommended this great product from ' . STORE_NAME;
$email_taf = 'Your friend, ' . $HTTP_POST_VARS['yourname'] . ', thought that you would be interested in the ' . $products_name . ' from ' . STORE_NAME . '.' . "\n\n";

if ($yourmessage != '') {
  $email_taf .= $yourmessage . "\n\n";
}
$email_taf .= 'To view the product click on the link below or copy and paste the link into your web browser:' . "\n\n" . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $products_id . "\n\n";
$email_taf .= 'Regards' . "\n" . STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n";

define('IMAGE_PROCESS', 'Process');
define('IMAGE_BACK', 'Back');
?>