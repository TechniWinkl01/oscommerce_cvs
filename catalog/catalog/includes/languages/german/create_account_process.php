<?php
/*
  $Id: create_account_process.php,v 1.8 2001/06/10 15:38:41 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Zugang erstellen');
define('NAVBAR_TITLE_2', 'Bearbeitung');
define('TOP_BAR_TITLE', 'Zugang erstellen');
define('HEADING_TITLE', 'Meine Zugangsinformation');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>ACHTUNG:</b></font></small> Wenn Sie schon einen Zugang bei uns besitzen, dann klicken Sie bitte zu der <a href="' . tep_href_link(FILENAME_LOGIN, 'origin=checkout_address', 'NONSSL') . '"><u>Anmeldeseite</u></a>.');

define('EMAIL_SUBJECT', 'Willkommen zu ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Sehr geehrte Herr ' . $lastname . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Sehr geehrte Frau ' .$lastname . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Sehr geehrte ' . $lastname . ',' . "\n\n");
define('EMAIL_WELCOME', 'willkommen zu <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Sie können jetzt unser <b>Mitglieder-Service</b> nutzen. Der Service bietet unter anderem:' . "\n\n" . '<li><b>Kundeneinkaufswagen</b> - Jeder Artikel bleibt registriert bis Sie zur Kasse gehen, oder die Produkte aus dem Warenkorb entfernen.' . "\n" . '<li><b>Adressbuch</b> - Wir können jetzt die Produkte zu der von Ihnen ausgesuchten Adresse senden. Der perfekte Weg ein Geburtstagsgeschenk zu versenden.' . "\n" . '<li><b>Vorherige Bestellung</b> - Sie können Ihre vorherigen Bestellungen überprüfen.' . "\n" . '<li><b>Meinungen über Produkte</b> - Teilen Sie Ihre Meinung mit anderen Kunden.' . "\n\n");
define('EMAIL_CONTACT', 'Falls Sie Fragen über unserem Mitglieder-Service haben, wenden Sie sich bitte an den Vertrieb: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Achtung:</b> Diese E-Mail Adresse wurde uns von einem Kunden eingegeben. Falls Sie sich nicht angemeldet haben, senden Sie bitte eine E-Mail an ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
?>
