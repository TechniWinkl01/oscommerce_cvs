<?php
/*
  $Id: moneyorder.php,v 1.4 2002/05/31 19:02:03 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Scheck/Vorkasse');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Zahlbar an:<br><br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . 'Ihre Bestellung wird nicht versandt, bis wir das Geld erhalten haben!');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Zahlbar an:\n\n" . STORE_NAME_ADDRESS . "\n\n" . 'Ihre Bestellung wir nicht versandt, bis wird das Geld erhalten haben!');
?>
