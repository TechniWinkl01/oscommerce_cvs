<?php
/*
  $Id: password_forgotten.php,v 1.4 2001/12/17 14:26:08 dgw_ Exp $

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
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - New Password');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A new password was requested from ' . $REMOTE_ADDR . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', 'A New Password Has Been Sent To Your Email Address');
?>