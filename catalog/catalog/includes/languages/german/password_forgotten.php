<?php
/*
  $Id: password_forgotten.php,v 1.6 2001/12/20 14:14:15 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Anmelden');
define('NAVBAR_TITLE_2', 'Passwort Vergessen');
define('HEADING_TITLE', 'Wie war noch mal mein Passwort?');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Adresse:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>ACHTUNG:</b></font> Diese E-Mail Adresse ist nicht registriert. Bitte versuchen Sie es noch einmal.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Ihr Passwort wurde erneuert.');
define('EMAIL_PASSWORD_REMINDER_BODY', 'Von der Adresse ' . $REMOTE_ADDR . ' haben wir eine Anfrage zur Passworterneuerung erhalten.' . "\n\n" . 'Ihr neues Passwort fuer \'' . STORE_NAME . '\' lautet ab sofort:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', 'Ein neues Passwort wurde per eMail verschickt.');
?>