<?php
/*
  $Id: cc.php,v 1.8 2001/09/20 19:41:25 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_CC_TEXT_TITLE', 'Kreditkarte');
  define('MODULE_PAYMENT_CC_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_CC_TEXT_CREDIR_CARD_TYPE', 'Typ:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER', 'Name des Eigentümers:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER', 'Kreditkartenr.:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES', 'Gültig bis:');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER', '* Der \'Name des Eigentümers\' muß mindestens aus ' . CC_OWNER_MIN_LENGTH . ' Buchstaben bestehen.\n');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER', '* Die \'Kreditkartenr.\' muß mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n');
  define('MODULE_PAYMENT_CC_TEXT_ERROR', 'Fehler bei der &Uuml;berp&uuml;fung der Kreditkarte!');
?>