<?php
/*
  $Id: cc.php,v 1.8 2001/09/20 19:41:17 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_CC_TEXT_TITLE', 'Tarjeta de Credito');
  define('MODULE_PAYMENT_CC_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_TYPE', 'Tipo de Tarjeta:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER', 'Titular de la Tarjeta:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER', '* El titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' letras.\n');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n');
  define('MODULE_PAYMENT_CC_TEXT_ERROR', 'Error en Tarjeta de Credito!');
?>