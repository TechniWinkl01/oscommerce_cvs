<?php
/*
  $Id: column_left.php,v 1.9 2001/06/05 18:53:58 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  if (CACHE_ON) {
    if (!$cache->cache(120, $cache->cache_default_object(), $HTTP_GET_VARS['cPath'])) {
      include(DIR_WS_BOXES . 'categories.php');
      $cache->endcache();
    }
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }

  if (CACHE_ON) {
    if (!$cache->cache(3600, $cache->cache_default_object(), $HTTP_GET_VARS['manufacturers_id'])) {
      include(DIR_WS_BOXES . 'manufacturers.php');
      $cache->endcache();
    }
  } else {
    include(DIR_WS_BOXES . 'manufacturers.php');
  }

  require(DIR_WS_BOXES . 'whats_new.php');
  require(DIR_WS_BOXES . 'search.php');
  require(DIR_WS_BOXES . 'add_a_quickie.php');
  require(DIR_WS_BOXES . 'information.php');
?>
