<?php
/*
  $Id: login.php,v 1.8 2001/12/20 14:14:15 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', 'Bestellen');
  define('HEADING_TITLE', 'Eine Online-Bestellung ist einfach.');
  define('TEXT_STEP_BY_STEP', 'Wir begleiten Sie Schritt f¸r Schritt bei dem Vorgang.');
} else {
  define('NAVBAR_TITLE', 'Anmelden');
  define('HEADING_TITLE', 'Melden Sie sich an');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('ENTRY_EMAIL_ADDRESS2', 'Geben Sie Ihre EMail-Adresse ein:');
define('TEXT_NEW_CUSTOMER', 'Ich bin ein neuer Kunde.');
define('TEXT_RETURNING_CUSTOMER', 'Ich bin bereits Kunde<br>&nbsp;und mein Passwort ist:');
define('TEXT_COOKIE', 'Wollen Sie die Anmeldeinformation in einem Cookie speichern?');
define('TEXT_PASSWORD_FORGOTTEN', 'Haben Sie Ihr Paﬂwort vergessen?');
define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>FEHLER:</b></font> Keine &Uuml;bereinstimmung der \'E-Mail Adresse\' und/oder dem \'Passwort\'.');
define('TEXT_LOGIN_ERROR_EMAIL', '<font color="#ff0000"><b>FEHLER:</b></font> Ihre \'E-Mail Adresse\' befindet sich bereits in unserer Datenbank, bitte melden Sie sich mit Ihrem Passwort an.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>ACHTUNG:</b></font> Ihre Besuchereingaben werden automatisch mit ihrem Mitgliedschaft Konto verbunden. <a href="javascript:session_win();">[Mehr Information]</a>');
?>
