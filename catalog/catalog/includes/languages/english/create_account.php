<?php
/*
  $Id: create_account.php,v 1.5 2001/06/03 18:08:50 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Create an Account');
define('TOP_BAR_TITLE', 'Create an Account');
define('HEADING_TITLE', 'My Account Information');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTE:</b></font></small> If you already have an account with us, please login at the <a href="' . tep_href_link(FILENAME_LOGIN, 'origin=checkout_address&connection=' . $HTTP_GET_VARS['connection'], 'NONSSL') . '"><u>login page</u></a>.');
?>