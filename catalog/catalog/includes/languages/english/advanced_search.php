<?
/*
English Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

define('NAVBAR_TITLE', 'Advanced Search');
define('TOP_BAR_TITLE', 'Advanced Search');
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
define('TEXT_ADVANCED_SEARCH_TIPS', '&nbsp;<b>Advanced Search Tips</b></font>' . FONT_STYLE_SMALL_TEXT . '<br><br>The search engine allows you to do a keyword search on the Product Model, Name, Description and Manufacturer Name.<br><br>When doing a keyword search, you can separate words and phrases by AND or OR. For example, you can enter <u>Microsoft AND mouse</u>. This search would generate results that have both words in them. However, if you type in <u>mouse OR keyboard</u>, you will get a list of products that have both or either words in them. If words are not separated by AND or OR, search will default the logical operator to ' . strtoupper(ADVANCED_SEARCH_DEFAULT_OPERATOR) . '.<br><br>You can also search for exact matches of words by enclosing them in quotes. For example, if you search for <u>"notebook computer"</u>, you will get a list of products that have that exact string in them.<br><br>Brackets can be used to control the order of the logical operations. For example, you can enter <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('JS_AT_LEAST_ONE_INPUT', '* One of the following fields must be enter:\n    Keywords\n    Date Added From\n    Date Added To\n    Price From\n    Price To\n');
define('JS_INVALID_FROM_DATE', '* Invalid From Date\n');
define('JS_INVALID_TO_DATE', '* Invalid To Date\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* To Date must be greater than or equal to From Date\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Price From must be a number\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Price To must be a number\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Price To must be greater than or equal to Price From\n');
define('JS_INVALID_KEYWORDS', '* Invalid keywords\n');
?>
