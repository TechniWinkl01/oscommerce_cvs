<?php
/*
  $Id: column_right.php,v 1.13 2001/06/12 21:02:34 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require(DIR_WS_BOXES . 'shopping_cart.php');

  if ($HTTP_GET_VARS['products_id']) {
    include(DIR_WS_BOXES . 'manufacturer_info.php');
  }

  require(DIR_WS_BOXES . 'best_sellers.php');

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
