<?php
/*
  $Id: advanced_search.php,v 1.17 2003/06/05 23:23:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'B�squeda Avanzada');
define('NAVBAR_TITLE_2', 'Resultados de la B�squeda');

define('HEADING_TITLE_1', 'B�squeda Avanzada');
define('HEADING_TITLE_2', 'Productos que satisfacen los criterios de b�squeda');

define('HEADING_SEARCH_CRITERIA', 'B�squeda Avanzada');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Buscar tambien en la descripcion');
define('ENTRY_CATEGORIES', 'Categor�as:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Incluir Subcategor�as');
define('ENTRY_MANUFACTURERS', 'Fabricante:');
define('ENTRY_PRICE_FROM', 'Desde precio:');
define('ENTRY_PRICE_TO', 'a precio:');
define('ENTRY_DATE_FROM', 'De fecha de alta:');
define('ENTRY_DATE_TO', 'a alta:');

define('TEXT_SEARCH_HELP_LINK', '<u>Ayuda</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Todas');
define('TEXT_ALL_MANUFACTURERS', 'Todos');

define('HEADING_SEARCH_HELP', 'Consejos para Busqueda Avanzada');
define('TEXT_SEARCH_HELP', 'El motor de busqueda le permite hacer una busqueda por palabras clave en el modelo, nombre y descripcion del producto y en el nombre del fabricante.<br><br>Cuando haga una busqueda por palabras o frases clave, puede separar estas con los operadores logicos AND y OR. Por ejemplo, puede hacer una busqueda por <u>microsoft AND raton</u>. Esta busqueda daria como resultado los productos que contengan ambas palabras. Por el contrario, si teclea  <u>raton OR teclado</u>, conseguira una lista de los productos que contengan las dos o solo una de las palabras. Si no se separan las palabras o frases clave con AND o con OR, la busqueda se hara usando por defecto el operador logico AND.<br><br>Puede realizar busquedas exactas de varias palabras encerrandolas entre comillas. Por ejemplo, si busca <u>"ordenador portatil"</u>, obtendras una lista de productos que tengan exactamente esa cadena en ellos.<br><br>Se pueden usar paratensis para controlar el orden de las operaciones logicas. Por ejemplo, puede introducir <u>microsoft and (teclado or raton or "visual basic")</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Cerrar Ventana</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Descripcion');
define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
define('TABLE_HEADING_QUANTITY', 'Cantidad');
define('TABLE_HEADING_PRICE', 'Precio');
define('TABLE_HEADING_WEIGHT', 'Peso');
define('TABLE_HEADING_BUY_NOW', 'Compre Ahora');

define('TEXT_NO_PRODUCTS', 'No hay productos que corresponden con los criterios de b�squeda.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Atleast one of the fields in the search form must be entered.');
define('ERROR_INVALID_FROM_DATE', 'La Fecha de Alta Desde es invalida');
define('ERROR_INVALID_TO_DATE', 'La Fecha de Alta Hasta es invalida');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Fecha de Alta Hasta debe ser mayor que Fecha de Alta Desde');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'El Precio Desde debe ser n�merico');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'El Precio Hasta debe ser n�merico');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Precio Hasta debe ser mayor o igual que Precio Desde');
define('ERROR_INVALID_KEYWORDS', 'Palabras clave incorrectas');
?>
