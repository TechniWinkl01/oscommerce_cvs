<?php
/*
  $Id: manufacturers.php,v 1.7 2001/09/19 11:12:03 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Manufacturers');
define('HEADING_TITLE', 'Manufacturers');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_MANUFACTURERS', 'Manufacturers');
define('TABLE_HEADING_IMAGE', 'Image');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_MANUFACTURERS', 'Manufacturers:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_NEW_INTRO', 'Please fill out the following information for the new manufacturer');
define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_MANUFACTURERS_ID', 'Manufacturers ID:');
define('TEXT_EDIT_MANUFACTURERS_NAME', 'Manufacturers Name:');
define('TEXT_EDIT_MANUFACTURERS_IMAGE', 'Manufacturers Image:');
define('TEXT_EDIT_MANUFACTURERS_URL', 'Manufacturers URL:');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this manufacturer?');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this manufacturer!');

define('ERROR_ACTION', 'AN ERROR HAS OCCURED! LAST ACTION : ' . $HTTP_GET_VARS['error']);
?>