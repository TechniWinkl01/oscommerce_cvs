<?php
/*
  $Id: login.php,v 1.7 2001/11/05 19:39:13 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == 'checkout_payment.php') {
  define('NAVBAR_TITLE', 'Realizar Pedido');
  define('TOP_BAR_TITLE', 'Realizar Pedido');
  define('HEADING_TITLE', 'Comprar aqui es facil.');
  define('TEXT_STEP_BY_STEP', 'Te ayudaremos a conseguirlo paso a paso.');
} else {
  define('NAVBAR_TITLE', 'Entrar');
  define('TOP_BAR_TITLE', 'Entrar en \'' . STORE_NAME . '\'');
  define('HEADING_TITLE', 'Dejame Entrar!');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('ENTRY_EMAIL_ADDRESS2', 'Escriba su direccion eMail');
define('TEXT_NEW_CUSTOMER', 'Soy un nuevo cliente.');
define('TEXT_RETURNING_CUSTOMER', 'Ya he comprado otras veces,<br>&nbsp; y my contraseña es:');
define('TEXT_COOKIE', '¿Guardar informacion en un \'cookie\'?');
define('TEXT_PASSWORD_FORGOTTEN', '¿Ha olvidado su contraseña? Siga este enlace y se la enviamos.');
define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> El \'E-Mail\' y/o \'Contraseña\' no figuran en nuestros datos.');
define('TEXT_LOGIN_ERROR_EMAIL', '<font color="#ff0000"><b>ERROR:</b></font> El \'E-Mail\' ya figura en nuestros datos, use su \'contraseña\' para entrar.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>NOTA:</b></font> El contenido de su &quot;Cesta de Visitante&quot; será añadido a su &quot;Cesta de Asociado&quot; una vez que haya entrado. <a href="javascript:session_win();">[Mas Informacion]</a>');
?>
