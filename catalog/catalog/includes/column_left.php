<?
  if (CACHE_ON == true) {
    if (!$cache->cache(120, $cache->cache_default_object(), $HTTP_GET_VARS['cPath'])) {
      include(DIR_WS_BOXES . 'categories.php');
      $cache->endcache();
    }
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }

  require(DIR_WS_BOXES . 'manufacturers.php');
  require(DIR_WS_BOXES . 'whats_new.php');
  require(DIR_WS_BOXES . 'search.php');
  require(DIR_WS_BOXES . 'add_a_quickie.php');
?>
