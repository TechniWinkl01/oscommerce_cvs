<?php
/*
  $Id: psigate.php,v 1.1 2002/03/01 01:08:19 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_PSIGATE_TEXT_TITLE', 'PSiGate');
  define('MODULE_PAYMENT_PSIGATE_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_OWNER', 'Name des Eigentümers:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_NUMBER', 'Kreditkartenr.:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_EXPIRES', 'Gültig bis:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_TYPE', 'Typ:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_OWNER', '* Der \'Name des Eigentümers\' muß mindestens aus ' . CC_OWNER_MIN_LENGTH . ' Buchstaben bestehen.\n');
  define('MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_NUMBER', '* Die \'Kreditkartenr.\' muß mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n');
  define('MODULE_PAYMENT_PSIGATE_TEXT_ERROR_MESSAGE', 'There has been an error processing you credit card, please try again.');
  define('MODULE_PAYMENT_PSIGATE_TEXT_ERROR', 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!');
?>