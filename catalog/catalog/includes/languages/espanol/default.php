<?
/*
Spanish Text for The Exchange Project Preview Release 2.0
Last Update: 11/11/2000
Author(s): David Garcia Watkins (dgw@q-logic.org)
*/

define('TEXT_MAIN', 'Bienvenido a \'' . STORE_NAME . '\'! Este es un catalogo de demostracion, <b>cualquier producto comprado aqui NO sera enviado ni cobrado</b>. Cualquier informacional de los productos del catalogo debe ser tratada como ficticia.<br><br>Si desea descargar este catalogo de demostracion, o desea contribuir al proyecto, por favor visite la <a href="http://www.theexchangeproject.org"><u>web de soporte</u></a>. <br><br>Se han deshabilitado todos los emails de este catalogo.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nuevos Productos En %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Proximamente');
define('TABLE_HEADING_DATE_EXPECTED', 'Lanzamiento');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('TOP_BAR_TITLE', 'Productos');
  define('HEADING_TITLE', 'A ver que tenemos aqui');
  define('TABLE_HEADING_MODEL', 'Modelo');
  define('TABLE_HEADING_PRODUCTS', 'Productos');
  define('TABLE_HEADING_PRICE', 'Precio');
  define('TEXT_NO_PRODUCTS', 'No hay productos en esta categoria.');
  define('TEXT_NO_PRODUCTS2', 'No hay productos de este fabricante.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Numero de Productos: ');
  define('TEXT_SHOW', '<b>Mostrar:</b>');
} elseif ($category_depth == 'top') {
  define('TOP_BAR_TITLE', 'Bienvenido a \'' . STORE_NAME . '\'!');
  define('HEADING_TITLE', '¿Que hay de nuevo por aqui?');
  define('SUB_BAR_TITLE', strftime(DATE_FORMAT_LONG, mktime(0,0,0,11,11,2000)));
} elseif ($category_depth == 'nested') {
  define('TOP_BAR_TITLE', 'Nuevos Productos en esta categoria');
  define('HEADING_TITLE', '¿Que hay de nuevo por aqui?');
  define('SUB_BAR_TITLE', 'Categorias');
}
?>