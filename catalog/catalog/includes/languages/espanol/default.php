<?php
/*
  $Id: default.php,v 1.13 2001/12/20 14:14:15 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TEXT_MAIN', 'Este es un catalogo de demostracion, <b>cualquier producto comprado aqui NO sera enviado ni cobrado</b>. Cualquier informacion de estos productos debe ser tratada como ficticia.<br><br>Si desea descargar este catalogo de demostracion, o desea contribuir al proyecto, por favor visite <a href="http://theexchangeproject.org"><u>la pagina de soporte</u></a>. Esta tienda esta basada en <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nuevos Productos En %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Proximamente');
define('TABLE_HEADING_DATE_EXPECTED', 'Lanzamiento');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('HEADING_TITLE', 'A ver que tenemos aqui');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Modelo');
  define('TABLE_HEADING_PRODUCTS', 'Productos');
  define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
  define('TABLE_HEADING_QUANTITY', 'Cantidad');
  define('TABLE_HEADING_PRICE', 'Precio');
  define('TABLE_HEADING_WEIGHT', 'Peso');
  define('TABLE_HEADING_BUY_NOW', 'Compre Ahora');
  define('TEXT_NO_PRODUCTS', 'No hay productos en esta categoria.');
  define('TEXT_NO_PRODUCTS2', 'No hay productos de este fabricante.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Numero de Productos: ');
  define('TEXT_SHOW', '<b>Mostrar:</b>');
  define('TEXT_BUY', 'Compre 1 \'');
  define('TEXT_NOW', '\' ahora');
  define('TEXT_ALL', 'All');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', '¿Que hay de nuevo por aqui?');
  define('SUB_BAR_TITLE', strftime(DATE_FORMAT_LONG));
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', '¿Que hay de nuevo por aqui?');
  define('SUB_BAR_TITLE', 'Categorias');
}
?>