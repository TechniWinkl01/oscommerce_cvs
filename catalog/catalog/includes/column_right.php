<?php
/*
  $Id: column_right.php,v 1.14 2002/03/10 01:32:09 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require(DIR_WS_BOXES . 'shopping_cart.php');

  if ($HTTP_GET_VARS['products_id']) {
    include(DIR_WS_BOXES . 'manufacturer_info.php');
  }

  if ($HTTP_GET_VARS['products_id']) {
    if (session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "' and global_product_notifications = '1'");
      $check = tep_db_fetch_array($check_query);
      if ($check['count'] > 0) {
        include(DIR_WS_BOXES . 'best_sellers.php');
      } else {
        include(DIR_WS_BOXES . 'product_notifications.php');
      }
    } else {
      include(DIR_WS_BOXES . 'product_notifications.php');
    }
  } else {
    include(DIR_WS_BOXES . 'best_sellers.php');
  }

  if ($HTTP_GET_VARS['products_id']) {
    if (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) {
      include(DIR_WS_BOXES . 'tell_a_friend.php');
    }
  } else {
    include(DIR_WS_BOXES . 'specials.php');
  }

  require(DIR_WS_BOXES . 'reviews.php');

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'languages.php');
    include(DIR_WS_BOXES . 'currencies.php');
  }
?>
