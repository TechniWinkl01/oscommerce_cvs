<?
  if (!$cache->cache(120, $cache->cache_default_object(), $HTTP_GET_VARS['cPath'])) {
    $include_file = DIR_WS_BOXES . 'categories.php'; include(DIR_WS_INCLUDES . 'include_once.php');
    $cache->endcache();
  }

  $include_file = DIR_WS_BOXES . 'manufacturers.php'; include(DIR_WS_INCLUDES . 'include_once.php');
  $include_file = DIR_WS_BOXES . 'whats_new.php'; include(DIR_WS_INCLUDES . 'include_once.php');
  $include_file = DIR_WS_BOXES . 'search.php'; include(DIR_WS_INCLUDES . 'include_once.php');
  $include_file = DIR_WS_BOXES . 'add_a_quickie.php'; include(DIR_WS_INCLUDES . 'include_once.php');
?>
