<?php
/*
  $Id: logoff.php,v 1.8 2002/10/28 23:40:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  tep_session_destroy();

  require('includes/counter.php');

  tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
