<?php
/*
  $Id: default.php,v 1.16 2001/12/20 14:14:14 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TEXT_MAIN', 'This is a demonstration online-shop, <b>any products purchased will not be delivered nor billed</b>. Any information seen on these products are to be treated fictional.<br><br>If you wish to download this sample shop, or to contribute to this project, please visit the <a href="http://theexchangeproject.org"><u>support site</u></a>. This shop is based on <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.');
define('TABLE_HEADING_NEW_PRODUCTS', 'New Products For %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Upcoming Products');
define('TABLE_HEADING_DATE_EXPECTED', 'Date Expected');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('HEADING_TITLE', 'Let\'s See What We Have Here');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Model');
  define('TABLE_HEADING_PRODUCTS', 'Product Name');
  define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');
  define('TABLE_HEADING_QUANTITY', 'Quantity');
  define('TABLE_HEADING_PRICE', 'Price');
  define('TABLE_HEADING_WEIGHT', 'Weight');
  define('TABLE_HEADING_BUY_NOW', 'Buy Now');
  define('TEXT_NO_PRODUCTS', 'There are no products to list in this category.');
  define('TEXT_NO_PRODUCTS2', 'There is no product available from this manufacturer.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Number of Products: ');
  define('TEXT_SHOW', '<b>Show:</b>');
  define('TEXT_BUY', 'Buy 1 \'');
  define('TEXT_NOW', '\' now');
  define('TEXT_ALL', 'All');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'What\'s New Here?');
  define('SUB_BAR_TITLE', strftime(DATE_FORMAT_LONG));
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'What\'s New Here?');
  define('SUB_BAR_TITLE', 'Categories');
}
?>
