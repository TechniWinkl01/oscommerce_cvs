<?
/*
English Text for The Exchange Project Administration Tool Preview Release 1.1
Last Update: 12/06/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

define('TOP_BAR_TITLE', 'Customers');
if ($HTTP_GET_VARS['action'] == 'add_customers') {
  define('HEADING_TITLE', 'New Customer');
} elseif ($HTTP_GET_VARS['action'] == 'update') {
  define('HEADING_TITLE', 'Update Customer');
} elseif ($HTTP_GET_VARS['action'] == 'delete') {
  define('HEADING_TITLE', 'Delete Customer');
} else {
  define('HEADING_TITLE', 'Customers');
}

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_COUNTRY', 'Country');
define('TABLE_HEADING_ACTION', 'Action');
?>