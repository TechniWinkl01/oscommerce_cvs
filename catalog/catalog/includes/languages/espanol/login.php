<?
/*
Spanish Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): David Garcia Watkins (dgw@q-logic.org)
*/

if ($HTTP_GET_VARS['origin'] == 'checkout_payment.php') {
  define('NAVBAR_TITLE', 'Order');
  define('TOP_BAR_TITLE', 'Order');
  define('HEADING_TITLE', 'Ordering online is easy.');
  define('TEXT_STEP_BY_STEP', 'We\'ll walk you through the process, step by step.');
} else {
  define('NAVBAR_TITLE', 'Entrar');
  define('TOP_BAR_TITLE', 'Entrar en \'' . STORE_NAME . '\'');
  define('HEADING_TITLE', 'Dejame Entrar!');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('ENTRY_EMAIL_ADDRESS2', 'Enter your e-mail address:');
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer,<br>&nbsp;and my password is:');
define('TEXT_COOKIE', '¿Guardar informacion en un \'cookie\'?');
define('TEXT_PASSWORD_FORGOTTEN', '¿Ha olvidado su contraseña? Siga este enlace y se la enviamos.');
define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Ese \'E-Mail\' y/o \'Contraseña\' no figuran en nuestros datos.');
define('TEXT_LOGIN_ERROR_EMAIL', '<font color="#ff0000"><b>ERROR:</b></font> You\'r \'E-Mail Address\' was not found on our database, please use your \'Password\' for login.');

define('IMAGE_NEXT', 'Next');
?>
