<?php
/*
  $Id: authorizenet.php,v 1.11 2002/11/01 05:10:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize.net');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Kreditkarten Test Info:<br><br>CC#: 4111111111111111<br>G&uuml;ltig bis: Any');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Typ:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', 'Kreditkarten-Nr.:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', 'G&uuml;ltig bis:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* Die \'Kreditkarten-Nr.\' muss mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', 'Bei der &Uuml;berp&uuml;fung Ihrer Kreditkarte ist ein Fehler aufgetreten! Bitte versuchen Sie es nochmal.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!');
?>