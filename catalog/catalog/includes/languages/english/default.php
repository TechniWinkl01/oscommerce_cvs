<?php
/*
  $Id: default.php,v 1.20 2003/02/14 12:51:58 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', 'This is a default setup of the osCommerce project, products shown are for demonstrational purposes, <b>any products purchased will not be delivered nor will the customer be billed</b>. Any information seen on these products is to be treated as fictional.<br><br>If you wish to download the solution powering this shop, or if you wish to contribute to the osCommerce project, please visit the <a href="http://oscommerce.com"><u>support site of osCommerce</u></a>. This shop is running on osCommerce version <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.<br><br>The text shown here can be modified in the following file, on each language basis: [path&nbsp;to&nbsp;catalog]/includes/languages/[language]/default.php.<br><br>That can be edited manually, or via the Administration Tool with the Languages->[language]->Define option, or by using the Tools->File Manager feature.');
define('TABLE_HEADING_NEW_PRODUCTS', 'New Products For %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Upcoming Products');
define('TABLE_HEADING_DATE_EXPECTED', 'Date Expected');

if ( ($category_depth == 'products') || ($HTTP_GET_VARS['manufacturers_id']) ) {
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
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categories');
}
?>
