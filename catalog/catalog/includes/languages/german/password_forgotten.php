<?php
/*
  $Id: password_forgotten.php,v 1.3 2001/05/26 16:49:37 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Anmelden');
define('NAVBAR_TITLE_2', 'Passwort Vergessen');
define('TOP_BAR_TITLE', 'Password Vergessen');
define('HEADING_TITLE', 'Wie war noch mal mein Passwort?');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Adresse:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>ACHTUNG:</b></font> Diese E-Mail Adresse ist nicht registriert. Bitte versuchen Sie es noch mal.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Ihre Passwort wurdt ermittelt');
define('EMAIL_PASSWORD_REMINDER_BODY', 'Eine Passwort Ermittelung wurde von ' . $REMOTE_ADDR . ' gefragt.' . "\n\n" . 'Ihre Passwort zu dem \'' . STORE_NAME . '\' lautet:' . "\n\n" . '   %s' . "\n\n");
?>