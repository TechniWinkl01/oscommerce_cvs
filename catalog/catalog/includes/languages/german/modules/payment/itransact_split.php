<?php
/*
  $Id: itransact_split.php,v 1.3 2002/04/17 20:31:18 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// Defines the payment options shown to the customer.  For example, you could change "Checking Account" to "EFT" but it may confuse your customers, since most don't know what EFT or ACH is.  The final values are defined based on what iTransact payment types you have defined in admin.
  if (MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS <> 0) $card_text = "Kreditkarte";
  if (MODULE_PAYMENT_ITRANSACT_SPLIT_EFT <> 0)	$eft_text = "&Uuml;berp&uuml;fungs Account";
  if (MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS <> 0 && MODULE_PAYMENT_ITRANSACT_SPLIT_EFT <> 0) $both_text = " oder ";
  define('MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_TITLE', 'Sicher ' . $card_text . $both_text . $eft_text . ' durch iTransact');
  define('MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_DESCRIPTION', 'Kreditkarten Test Info:<br><br>CC#: 5454545454545454<br>G&uuml;ltig bis: Any<br>&Uuml;berp&uuml;fungs ABA Nummer: 124000054<br>Account Nummer: 12345<br><br><b>&gt;&gt; <a href="https://secure.itransact.com/support/login.html" target="_blank"><u>Gateway Link</u></a></b>');

  define('HEADING_TITLE', 'Ihr Checkout wurde durchgef&uuml;hrt!');
// Number of seconds to delay when taking customer from checkout_process.php to checkout_process_success_itransact.php.  Keep it low.  1-3 is good.
  define('MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_PROCESS_DELAY_SECONDS', '3');
// Text to show to customer on checkout_process.php.
  define('MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_PROCESS_TEXT', "Ihre Transaktion wurde erfolgreich durchgef&uuml;hrt. Klicken Sie auf den Link unterhalb, wenn die nachfolgende Seite nicht in den n&auml;chsten Sekunden " . MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_PROCESS_DELAY_SECONDS . " erscheint.");

// URL to post to on iTransact's secure server for TEP processing.  Don't change this, unless you have specific arrangements with iTransact.
  define('MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_FORM_ACTION', 'https://secure.itransact.com/tep/index.php');
// URL the iTransact system returns customer to after transaction is completed.
  define('MODULE_PAYMENT_ITRANSACT_RETURN_ADDRESS', HTTP_SERVER . DIR_WS_CATALOG . 'checkout_process.php');
// Defines how iTransact returns the customer.  Options are 'post' or 'redirect'.  Post is best.  Redirect will not work correctly with this module, so it's there for future revisions.  You can also comment the line out.  If you do, the customer receives a "Thank You" page on iTransact's server before returning to your store.
  define('MODULE_PAYMENT_ITRANSACT_RETURN_MODE', 'post');

//  Leave the next items as they are.  You really need a secure server to use these. They are here for future revisions.
  define('MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_DIE_MESSAGE', 'Es ist ein interner Fehler aufgetreten! Bitte versuchen Sie es nochmal.');
// define('MODULE_PAYMENT_ITRANSACT_ON_ERROR', '1'); // Leave commented out.
?>