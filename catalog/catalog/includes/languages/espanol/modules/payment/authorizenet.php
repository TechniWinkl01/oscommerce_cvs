<?php
/*
  $Id: authorizenet.php,v 1.10 2002/11/01 05:10:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize.net');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Tipo:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', 'Ha ocurrido un error procesando su tarjeta de credito. Por favor, intentelo de nuevo.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', 'Error en Tarjeta de Credito!');
?>