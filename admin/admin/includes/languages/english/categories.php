<?
/*
English Text for The Exchange Project Administration Tool Preview Release 1.1
Last Update: 12/06/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

define('TOP_BAR_TITLE', 'Main Categories');
define('HEADING_TITLE', 'Categories');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES', 'Categories');
define('TABLE_HEADING_SORT_ORDER', 'Sort Order');
define('TABLE_HEADING_IMAGE', 'Image');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Categories ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Categories Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Categories Image:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');
define('TEXT_EDIT_PARENT_ID', 'Parent ID:');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');

define('TEXT_MOVE_INTRO', 'Please select which parent-category you wish to move the selected child-category to');
define('TEXT_MOVE', 'Move <b>%s</b> to:');
define('TEXT_MOVE_NOTE', '<small><b>NOTE:</b></small> Take some caffeine before you move anything!');

define('ERROR_ACTION', 'AN ERROR HAS OCCURED! LAST ACTION : ' . $HTTP_GET_VARS['error']);

define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_SAVE', 'Save');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_MOVE', 'Move');
define('IMAGE_EDIT', 'Edit');
?>