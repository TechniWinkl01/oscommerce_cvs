<?php
/*
  $Id: login.php,v 1.5 2001/05/26 16:45:23 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == 'checkout_payment.php') {
  define('NAVBAR_TITLE', 'Order');
  define('TOP_BAR_TITLE', 'Order');
  define('HEADING_TITLE', 'Ordering online is easy.');
  define('TEXT_STEP_BY_STEP', 'We\'ll walk you through the process, step by step.');
} else {
  define('NAVBAR_TITLE', 'Login');
  define('TOP_BAR_TITLE', 'Login to \'' . STORE_NAME . '\'');
  define('HEADING_TITLE', 'Let Me In!');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('ENTRY_EMAIL_ADDRESS2', 'Enter your e-mail address:');
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer,<br>&nbsp;and my password is:');
define('TEXT_COOKIE', 'Save login information in a cookie?');
define('TEXT_PASSWORD_FORGOTTEN', 'Forgot your password? Click here');
define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> No match for \'E-Mail Address\' and/or \'Password\'.');
define('TEXT_LOGIN_ERROR_EMAIL', '<font color="#ff0000"><b>ERROR:</b></font> You\'r \'E-Mail Address\' was not found on our database, please use your \'Password\' for login.');
?>
