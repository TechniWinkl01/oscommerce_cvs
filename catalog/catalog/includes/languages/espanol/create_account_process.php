<?php
/*
  $Id: create_account_process.php,v 1.4 2001/05/26 16:47:28 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Crear una Cuenta');
define('NAVBAR_TITLE_2', 'Proceso');
define('TOP_BAR_TITLE', 'Crear una Cuenta');
define('HEADING_TITLE', 'Informacion de Mi Cuenta');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTA:</b></font></small> Si ya tiene una cuenta con nosotros, use esta pagina para <a href="' . tep_href_link(FILENAME_LOGIN, 'origin=checkout_address', 'NONSSL') . '"><u>Entrar</u></a>.');
define('PLEASE_SELECT', 'Seleccione');

define('EMAIL_WELCOME', '*** Nota: Esta direccion fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuniquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n" . 'Estimado %s %s,' . "\n\n" . 'Le damos la bienvenida a  ' . STORE_NAME . '! Ahora puede disfrutar de los servicios que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '* Carrito Permanente - Cualquier producto añadido a su carrito permanecera en el hasta que lo elimine, o hasta que realice la compra..' . "\n" . '* Libro de Direcciones - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumpleaños directamente a la persona que cumple años..' . "\n" . '* Historia de Pedidos - Vea la relacion de compras que ha realizado con nosotros..' . "\n" . '* Comentarios - Comparta su opinion sobre los productos con otros clientes..' . "\n\n" . 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WELCOME_SUBJECT', 'Bienvenido a ' . STORE_NAME . '!');
?>
