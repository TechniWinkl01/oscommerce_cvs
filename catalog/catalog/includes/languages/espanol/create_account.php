<?php
/*
  $Id: create_account.php,v 1.14 2003/12/17 16:16:16 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Crear una Cuenta');

define('HEADING_TITLE', 'Datos de Mi Cuenta');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTA:</b></font></small> Si ya ha pasado por este proceso y tiene una cuenta, por favor <a href="%s"><u>entre</u></a> en ella.');

define('TABLE_HEADING_PRIVACY_CONDITIONS', 'Privacy Statement');
define('TEXT_PRIVACY_CONDITIONS_DESCRIPTION', 'Please acknowledge you agree with our privacy statement by ticking the following box. The privacy statement can be read <a href="' . tep_href_link(FILENAME_PRIVACY, '', 'SSL') . '"><u>here</u></a>.');
define('TEXT_PRIVACY_CONDITIONS_CONFIRM', 'I have read and agreed to your privacy statement.');

define('EMAIL_SUBJECT', 'Bienvenido a ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Estimado %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Estimado %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Estimado %s,' . "\n\n");
define('EMAIL_WELCOME', 'Le damos la bienvenida a <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Ahora puede disfrutar de los <b>servicios</b> que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><b>Carrito Permanente</b> - Cualquier producto a�adido a su carrito permanecera en el hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><b>Libro de Direcciones</b> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumplea�os directamente a la persona que cumple a�os.' . "\n" . '<li><b>Historia de Pedidos</b> - Vea la relacion de compras que ha realizado con nosotros.' . "\n" . '<li><b>Comentarios</b> - Comparta su opinion sobre los productos con otros clientes.' . "\n\n");
define('EMAIL_CONTACT', 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Nota:</b> Esta direccion fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuniquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
?>
