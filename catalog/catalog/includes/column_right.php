<?
  require(DIR_WS_BOXES . 'shopping_cart.php');
  require(DIR_WS_BOXES . 'best_sellers.php');
  require(DIR_WS_BOXES . 'specials.php');
  require(DIR_WS_BOXES . 'reviews.php');

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'languages.php');
  }

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'currencies.php');
  }
?>
