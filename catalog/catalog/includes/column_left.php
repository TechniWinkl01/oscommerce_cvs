<?php
/*
  $Id: column_left.php,v 1.11 2001/11/17 03:34:17 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

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
  require(DIR_WS_BOXES . 'add_a_quickie.php');
  require(DIR_WS_BOXES . 'information.php');
?>
