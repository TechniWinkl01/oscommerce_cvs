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
  define('MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_OWNER', 'Titular de la Tarjeta:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_TYPE', 'Tipo de Tarjeta:');
  define('MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_OWNER', '* El titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' letras.\n');
  define('MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n');
  define('MODULE_PAYMENT_PSIGATE_TEXT_ERROR_MESSAGE', 'There has been an error processing you credit card, please try again.');
  define('MODULE_PAYMENT_PSIGATE_TEXT_ERROR', 'Error en Tarjeta de Credito!');
?>