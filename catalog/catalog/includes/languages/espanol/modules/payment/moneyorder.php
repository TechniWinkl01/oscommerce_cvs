<?php
/*
  $Id: moneyorder.php,v 1.5 2002/11/01 05:36:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Cheque/Transferencia Bancaria');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Pagadero a:<br><br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . '&nbsp;Su pedido se enviará en cuanto se reciba el pago.');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Pagadero a:\n\n" . STORE_NAME_ADDRESS . "\n\n" . 'Su pedido se enviará en cuanto se reciba el pago.');
?>