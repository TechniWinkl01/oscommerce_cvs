<?php
/*
  $Id: logoff.php,v 1.6 2002/03/10 22:35:12 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  $cart->reset(FALSE);
  require('includes/counter.php');
  tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>