<?php
/*
  $Id: logoff.php,v 1.5 2001/09/20 13:31:56 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  tep_session_unregister('customer_id');
  $cart->reset(FALSE);
  require('includes/counter.php');
  tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>