<?php
/*
  $Id: tell_a_friend.php,v 1.9 2003/06/10 18:20:40 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Enviar a un Amigo');

define('HEADING_TITLE', 'Enviar informacion sobre \'%s\' un amigo');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Tus Datos');
define('FORM_TITLE_FRIEND_DETAILS', 'Los Datos De Tu Amigo');
define('FORM_TITLE_FRIEND_MESSAGE', 'Tu Mensaje');

define('FORM_FIELD_CUSTOMER_NAME', 'Tu Nombre:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Tu Email:');
define('FORM_FIELD_FRIEND_NAME', 'El Nombre De Tu Amigo:');
define('FORM_FIELD_FRIEND_EMAIL', 'El Email De Tu Amigo:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Tu email sobre <b>%s</b> ha sido enviado con exito a <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', 'Tu amigo %s te quiere recomendar "%s"');
define('TEXT_EMAIL_INTRO', 'Hola %s!' . "\n\n" . 'Tu amigo %s, pensó que estarias interesado en %s de %s.');
define('TEXT_EMAIL_LINK', 'Para ver el producto usa el siguiente enlace:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Atentamente,' . "\n\n" . '%s');

define('ERROR_TO_NAME', 'Error: Your friends name must not be empty.');
define('ERROR_TO_ADDRESS', 'Error: Your friends e-mail address must be a valid e-mail address.');
define('ERROR_FROM_NAME', 'Error: Your name must not be empty.');
define('ERROR_FROM_ADDRESS', 'Error: Your e-mail address must be a valid e-mail address.');
?>
