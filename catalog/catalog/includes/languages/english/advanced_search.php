<?php
/*
  $Id: advanced_search.php,v 1.11 2002/01/01 19:08:57 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Advanced Search');
define('HEADING_TITLE', 'Enter Search Criteria');

define('ENTRY_CATEGORIES', 'Categories:');
define('ENTRY_INCLUDES_SUBCATEGORIES', 'includes subcategories');
define('ENTRY_MANUFACTURER', 'Manufacturer:');
define('ENTRY_KEYWORDS', 'Keywords/Phrases:');
define('ENTRY_PRICE_FROM', 'Price from:');
define('ENTRY_DATE_ADDED_FROM', 'Date Added from:');
define('ENTRY_TO', 'To:');
define('ENTRY_SORT_BY' , 'Sort Result By:');
define('ENTRY_KEYWORDS_TEXT', '&nbsp;<small><font color="#AABBDD">(one or more keywords/phrases)</font></small>');
define('ENTRY_DATE_ADDED_TEXT', '&nbsp;<small><font color="#AABBDD">(eg. 21/05/1970)</font></small>');
define('TEXT_ALL_CATEGORIES', 'All Categories');
define('TEXT_ALL_MANUFACTURERS', 'All Manufacturers');
define('TEXT_CATEGORY_NAME', 'Category Name');
define('TEXT_MANUFACTURER_NAME', 'Manufacturer Name');
define('TEXT_PRODUCT_NAME', 'Product Name');
define('TEXT_PRICE', 'Price');
define('TEXT_PERFORM_ADVANCED_SEARCH', 'Perform Advanced Search');
define('TEXT_SEARCH_IN_DESCRIPTION', 'Search in the description also');
define('TEXT_ADVANCED_SEARCH_TIPS_HEADING', 'Advanced Search Tips');
define('TEXT_ADVANCED_SEARCH_TIPS', 'The search engine allows you to do a keyword search on the Product Model, Name, Description and Manufacturer Name.<br><br>When doing a keyword search, you can separate words and phrases by AND or OR. For example, you can enter <u>Microsoft AND mouse</u>. This search would generate results that have both words in them. However, if you type in <u>mouse OR keyboard</u>, you will get a list of products that have both or either words in them. If words are not separated by AND or OR, search will default the logical operator to AND.<br><br>You can also search for exact matches of words by enclosing them in quotes. For example, if you search for <u>"notebook computer"</u>, you will get a list of products that have that exact string in them.<br><br>Brackets can be used to control the order of the logical operations. For example, you can enter <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('JS_AT_LEAST_ONE_INPUT', '* One of the following fields must be enter:\n    Keywords\n    Date Added From\n    Date Added To\n    Price From\n    Price To\n');
define('JS_INVALID_FROM_DATE', '* Invalid From Date\n');
define('JS_INVALID_TO_DATE', '* Invalid To Date\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* To Date must be greater than or equal to From Date\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Price From must be a number\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Price To must be a number\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Price To must be greater than or equal to Price From\n');
define('JS_INVALID_KEYWORDS', '* Invalid keywords\n');
?>
