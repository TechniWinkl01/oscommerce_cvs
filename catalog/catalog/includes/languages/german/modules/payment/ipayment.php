<?php
/*
  $Id: ipayment.php,v 1.5 2002/11/01 05:35:32 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_IPAYMENT_TEXT_TITLE', 'iPayment');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_DESCRIPTION', 'Kreditkarten Test Info:<br><br>CC#: 4111111111111111<br>G&uuml;ltig bis: Any');
  define('IPAYMENT_ERROR_HEADING', 'Folgender Fehler wurde von iPayment w&auml;hrend des Prozesses gemeldet:');
  define('IPAYMENT_ERROR_MESSAGE', 'Bitte kontrollieren Sie die Daten Ihrer Kreditkarte!');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_OWNER', 'Kreditkarteninhaber');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_NUMBER', 'Kreditkarten-Nr.:');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_EXPIRES', 'G&uuml;ltig bis:');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_CHECKNUMBER', 'Karten-Pr&uuml;fnummer<font size=1><br>&nbsp;&nbsp;(Auf der Kartenr&uuml;ckseite im Unterschriftsfeld)</font>');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION', '(located at the back of the credit card)');

  define('MODULE_PAYMENT_IPAYMENT_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_IPAYMENT_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
?>