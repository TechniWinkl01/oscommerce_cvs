<?php
/*
  $Id: authorizenet.php,v 1.8 2001/09/20 19:41:18 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize.net');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Tipo:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', 'Ha ocurrido un error procesando su tarjeta de credito. Por favor, intentelo de nuevo.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', 'Error en Tarjeta de Credito!');
?>