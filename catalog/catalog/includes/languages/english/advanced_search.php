<?php
/*
  $Id: advanced_search.php,v 1.13 2002/05/27 13:57:38 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Advanced Search');
define('HEADING_TITLE', 'Advanced Search');

define('HEADING_SEARCH_CRITERIA', 'Search Criteria');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Search In Product Descriptions');
define('ENTRY_CATEGORIES', 'Categories:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Include Subcategories');
define('ENTRY_MANUFACTURERS', 'Manufacturers:');
define('ENTRY_PRICE_FROM', 'Price From:');
define('ENTRY_PRICE_TO', 'Price To:');
define('ENTRY_DATE_FROM', 'Date From:');
define('ENTRY_DATE_TO', 'Date To:');

define('TEXT_SEARCH_HELP_LINK', '<u>Search Help</u> [?]');

define('TEXT_ALL_CATEGORIES', 'All Categories');
define('TEXT_ALL_MANUFACTURERS', 'All Manufacturers');

define('HEADING_SEARCH_HELP', 'Search Help');
define('TEXT_SEARCH_HELP', 'Keywords may be separated by AND and/or OR statements for greater control of the search results.<br><br>For example, <u>Microsoft AND mouse</u> would generate a result set that contain both words. However, for <u>mouse OR keyboard</u>, the result set returned would contain both or either words.<br><br>Exact matches can be searched for by enclosing keywords in double-quotes.<br><br>For example, <u>"notebook computer"</u> would generate a result set which match the exact string.<br><br>Brackets can be used for further control on the result set.<br><br>For example, <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

define('JS_AT_LEAST_ONE_INPUT', '* One of the following fields must be entered:\n    Keywords\n    Date Added From\n    Date Added To\n    Price From\n    Price To\n');
define('JS_INVALID_FROM_DATE', '* Invalid From Date\n');
define('JS_INVALID_TO_DATE', '* Invalid To Date\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* To Date must be greater than or equal to From Date\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Price From must be a number\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Price To must be a number\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Price To must be greater than or equal to Price From\n');
define('JS_INVALID_KEYWORDS', '* Invalid keywords\n');
?>
