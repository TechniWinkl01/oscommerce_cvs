<?php
/*
  $Id: login.php,v 1.12 2002/06/03 13:19:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', 'Realizar Pedido');
  define('HEADING_TITLE', 'Comprar aqui es facil.');
  define('TEXT_STEP_BY_STEP', 'Te ayudaremos a conseguirlo paso a paso.');
} else {
  define('NAVBAR_TITLE', 'Entrar');
  define('HEADING_TITLE', 'Dejame Entrar!');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_NEW_CUSTOMER', 'Nuevo Cliente');
define('TEXT_NEW_CUSTOMER', 'Soy un nuevo cliente.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.');

define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('TEXT_RETURNING_CUSTOMER', 'He comprado otras veces.');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_PASSWORD', 'Password:');

define('TEXT_PASSWORD_FORGOTTEN', '¿Ha olvidado su contraseña? Siga este enlace y se la enviamos.');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> El \'E-Mail\' y/o \'Contraseña\' no figuran en nuestros datos.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>NOTA:</b></font> El contenido de su &quot;Cesta de Visitante&quot; será añadido a su &quot;Cesta de Asociado&quot; una vez que haya entrado. <a href="javascript:session_win();">[Mas Informacion]</a>');
?>
