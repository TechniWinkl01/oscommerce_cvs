<?php
/*
  $Id: column_left.php,v 1.13 2002/06/16 22:08:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if ( (USE_CACHE == 'true') && !SID) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }

  if ( (USE_CACHE == 'true') && !SID) {
    echo tep_cache_manufacturers_box();
  } else {
    include(DIR_WS_BOXES . 'manufacturers.php');
  }

  require(DIR_WS_BOXES . 'whats_new.php');
  require(DIR_WS_BOXES . 'search.php');
  require(DIR_WS_BOXES . 'information.php');
?>