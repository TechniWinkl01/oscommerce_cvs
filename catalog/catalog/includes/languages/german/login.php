<?php
/*
  $Id: login.php,v 1.12 2002/06/17 23:10:03 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', 'Bestellen');
  define('HEADING_TITLE', 'Eine Online-Bestellung ist einfach.');
  define('TEXT_STEP_BY_STEP', 'Wir begleiten Sie Schritt f&uuml;r Schritt bei diesem Vorgang.');
} else {
  define('NAVBAR_TITLE', 'Anmelden');
  define('HEADING_TITLE', 'Melden Sie sich an');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_NEW_CUSTOMER', 'Neuer Kunde');
define('TEXT_NEW_CUSTOMER', 'Ich bin ein neuer Kunde.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Durch Ihre Anmeldung bei ' . STORE_NAME . ' sind Sie in der Lage schneller zu bestellen, kennen jederzeit den Status Ihrer Bestellungen und haben immer eine aktuelle &Uuml;bersicht &uuml;ber Ihre bisherigen Bestellungen.');

define('HEADING_RETURNING_CUSTOMER', 'Bereits Kunde');
define('TEXT_RETURNING_CUSTOMER', 'Ich bin bereits Kunde.');
define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');
define('ENTRY_PASSWORD', 'Passwort:');

define('TEXT_PASSWORD_FORGOTTEN', 'Sie haben Ihr Passwort vergessen? Dann klicken Sie <u>hier</u>');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>FEHLER:</b></font> Keine &Uuml;bereinstimmung der eingebenen \'eMail-Adresse\' und/oder dem \'Passwort\'.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>ACHTUNG:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win();">[Mehr Information]</a>');
?>