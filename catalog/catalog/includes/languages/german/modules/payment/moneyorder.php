<?php
/*
  $Id: moneyorder.php,v 1.3 2002/05/31 18:53:44 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Scheck/Vorkasse');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Zahlbar an:<br><br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . 'Ihre Bestellung wird nicht versandt, bis wir das Geld erhalten haben!');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', 'Zahlbar an:<br><br>' . STORE_NAME_ADDRESS . '<br><br>' . 'Ihre Bestellung wir nicht versandt, bis wird das Geld erhalten haben!');
?>
