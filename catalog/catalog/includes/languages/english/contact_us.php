<?
/*
English Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

if ($HTTP_GET_VARS['action'] == 'success') {
  define('SUB_BAR_TITLE', 'Enquiry Sent');
} else {
  define('SUB_BAR_TITLE', 'Do you have any questions?');
}
define('TOP_BAR_TITLE', 'Contact Us');
define('HEADING_TITLE', 'Contact Us');
define('NAVBAR_TITLE', 'Contact Us');
define('TEXT_SUCCESS', 'Your enquiry has been successfully sent to the Store Owner.');
define('EMAIL_SUBJECT', 'Enquiry from ' . STORE_NAME);

define('ENTRY_NAME', 'Full Name:');
define('ENTRY_EMAIL', 'E-Mail Address:');
define('ENTRY_ENQUIRY', 'Enquiry:');

define('IMAGE_SUBMIT', 'Submit');
?>