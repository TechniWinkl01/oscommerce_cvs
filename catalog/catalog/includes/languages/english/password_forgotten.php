<?php
/*
  $Id: password_forgotten.php,v 1.3 2001/05/26 16:45:24 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Login');
define('NAVBAR_TITLE_2', 'Password Forgotten');
define('TOP_BAR_TITLE', 'Password Forgotten');
define('HEADING_TITLE', 'I\'ve Forgotten My Password!');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>NOTE:</b></font> The E-Mail Address was not found in our records, please try again.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Password Reminder');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A password reminder was requested from ' . $REMOTE_ADDR . '.' . "\n\n" . 'Your password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");
?>