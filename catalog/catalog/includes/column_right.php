<?
  require(DIR_WS_BOXES . 'shopping_cart.php');
  require(DIR_WS_BOXES . 'best_sellers.php');

  if ($HTTP_GET_VARS['products_id']) {
    include(DIR_WS_BOXES . 'tell_a_friend.php');
  } else {
    include(DIR_WS_BOXES . 'specials.php');
  }

  if ($HTTP_GET_VARS['products_id']) {
    $reviews_query = tep_db_query("select count(*) as count from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
      include(DIR_WS_BOXES . 'reviews.php');
    }
  } else {
    include(DIR_WS_BOXES . 'reviews.php');
  }

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'languages.php');
  }

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'currencies.php');
  }
?>
