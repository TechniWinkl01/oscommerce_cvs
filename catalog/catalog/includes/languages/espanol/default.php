<?
/*
Spanish Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): David Garcia Watkins (dgw@q-logic.org)
*/

define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which new products are available to purchase?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '"><u>log yourself in</u></a> with your account information.</small>');
define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '"><u>log yourself in</u></a>? Or would you prefer to <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '"><u>create an account</u></a>?');

define('TEXT_MAIN', 'Este es un catalogo de demostracion, <b>cualquier producto comprado aqui NO sera enviado ni cobrado</b>. Cualquier informacion de estos productos debe ser tratada como ficticia.<br><br>Si desea descargar este catalogo de demostracion, o desea contribuir al proyecto, por favor visite <a href="http://theexchangeproject.org"><u>la pagina de soporte</u></a>. Esta tienda esta basada en <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nuevos Productos En %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Proximamente');
define('TABLE_HEADING_DATE_EXPECTED', 'Lanzamiento');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('TOP_BAR_TITLE', 'Productos');
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
  define('TEXT_SORT_PRODUCTS', 'Ordenar Productos ');
  define('TEXT_DESCENDINGLY', 'Descendentemente');
  define('TEXT_ASCENDINGLY', 'Ascendentemente');
  define('TEXT_BY', ' por ');
  define('TEXT_BUY', 'Compre 1 \'');
  define('TEXT_NOW', '\' ahora');
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