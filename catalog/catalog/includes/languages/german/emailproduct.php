<?
/*
German Text for The Exchange Project Preview Release 2.2
Last Update: 04/24/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

define('NAVBAR_TITLE', 'Artikel weiterempfehlen');
define('TOP_BAR_TITLE', 'Artikel weiterempfehlen');
define('HEADING_TITLE', 'Empfehlen Sie diesen Artikel weiter');

define('TEXT_EMAILPRODUCT_EMAIL', 'Ihre EMail-Adresse:');
define('TEXT_EMAILPRODUCT_NAME', 'Ihr Vor- und Nachname:');
define('TEXT_EMAILPRODUCT_FRIEND_EMAIL', 'EMail-Adresse Ihres Freundes:');
define('TEXT_EMAILPRODUCT_MESSAGE', 'Ihre Nachricht (wird mit der Empfehlung versendet):');
define('TEXT_EMAILPRODUCT_TELLAFRIEND', 'Sie empfehlen diesen Artikel weiter:');
define('TEXT_EMAILPRODUCT_YOUR_MAIL_ABOUT', 'Ihre EMail über');
define('TEXT_EMAILPRODUCT_HAS_BEEN_SENT', 'wurde gesendet an -');

$email_subject = 'Ihr Freund ' . $yourname . ', hat diesen tollen Artikel gefunden, bei ' . STORE_NAME;
$email_taf = 'Ihr Freund, ' . $HTTP_POST_VARS['yourname'] . ', hat diesen tollen Artikel ' . $products_name . ' bei ' . STORE_NAME . ' gefunden.' . "\n\n";

if ($yourmessage != '') {
  $email_taf .= $yourmessage . "\n\n";
}
$email_taf .= 'Um das Produkt anzusehen, klicken Sie bitte auf den Link oder kopieren diesen und fügen Sie ihn in die URL-Zeile Ihres Browsers ein:' . "\n\n" . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $products_id . "\n\n";
$email_taf .= 'Mit freundlichen Grüßen' . "\n" . STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n";

define('IMAGE_PROCESS', 'Bestätigen');
define('IMAGE_BACK', 'Zurück');
?>