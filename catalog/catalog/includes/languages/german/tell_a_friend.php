<?php
/*
  $Id: tell_a_friend.php,v 1.9 2003/02/16 00:42:03 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Produkt weiterempfehlen');
define('HEADING_TITLE', 'Empfehlen Sie \'%s\' weiter');
define('HEADING_TITLE_ERROR', 'Produkt weiterempfehlen');
define('ERROR_INVALID_PRODUCT', 'Das von Ihnen gew&auml;hlte Produkt wurde nicht gefunden!');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Ihre Angaben');
define('FORM_TITLE_FRIEND_DETAILS', 'Angaben Ihres Freundes');
define('FORM_TITLE_FRIEND_MESSAGE', 'Ihre Nachricht (wird mit der Empfehlung versendet)');

define('FORM_FIELD_CUSTOMER_NAME', 'Ihr Name:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Ihre eMail-Adresse:');
define('FORM_FIELD_FRIEND_NAME', 'Name Ihres Freundes:');
define('FORM_FIELD_FRIEND_EMAIL', 'eMail-Adresse Ihres Freundes:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Ihre eMail &uuml;ber <b>%s</b> wurde gesendet an <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', 'Ihr Freund %s, hat dieses Produkt gefunden, und zwar hier: %s');
define('TEXT_EMAIL_INTRO', 'Hallo %s!' . "\n\n" . 'Ihr Freund, %s, hat dieses Produkt %s bei %s gefunden.');
define('TEXT_EMAIL_LINK', 'Um das Produkt anzusehen, klicken Sie bitte auf den Link oder kopieren diesen und fügen Sie ihn in der Adress-Zeile Ihres Browsers ein:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Mit freundlichen Grüssen,' . "\n\n" . '%s');
?>