<?
/*
Spanish Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): David Garcia Watkins (dgw@q-logic.org)
*/

define('NAVBAR_TITLE', 'Búsqueda Avanzada');
define('TOP_BAR_TITLE', 'Búsqueda Avanzada');
define('HEADING_TITLE', 'Seleccione Los Criterios De la Búsqueda');

define('ENTRY_CATEGORIES', 'Categorías:');
define('ENTRY_INCLUDES_SUBCATEGORIES', 'Incluir Subcategorías');
define('ENTRY_MANUFACTURER', 'Fabricante:');
define('ENTRY_KEYWORDS', 'Palabras o Frases Clave:');
define('ENTRY_PRICE_FROM', 'Precio Desde:');
define('ENTRY_DATE_ADDED_FROM', 'Fecha de Alta Desde:');
define('ENTRY_TO', 'Hasta:');
define('ENTRY_SORT_BY' , 'Resultado Ordenado Por:');
define('ENTRY_KEYWORDS_TEXT', '&nbsp;<small><font color="#AABBDD">(una o más palabras o frases clave)</font></small>');
define('ENTRY_DATE_ADDED_TEXT', '&nbsp;<small><font color="#AABBDD">(ej. 21/05/1970)</font></small>');
define('TEXT_ALL_CATEGORIES', 'Todas');
define('TEXT_ALL_MANUFACTURERS', 'Todos');
define('TEXT_CATEGORY_NAME', 'Nombre De la Categoría');
define('TEXT_MANUFACTURER_NAME', 'Nombre Del Fabricante');
define('TEXT_PRODUCT_NAME', 'Nombre Del Producto');
define('TEXT_PRICE', 'Precio');
define('TEXT_PERFORM_ADVANCED_SEARCH', 'Realice La Búsqueda Avanzada');
define('TEXT_ADVANCED_SEARCH_TIPS', '&nbsp;<b>Consejos para Busqueda Avanzada</b></font><font face="'. SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '"><br><br>El motor de busqueda le permite hacer una busqueda por palabras clave en el modelo, nombre y descripcion del producto y en el nombre del fabricante.<br><br>Cuando haga una busqueda por palabras o frases clave, puede separar estas con los operadores logicos AND y OR. Por ejemplo, puede hacer una busqueda por <u>microsoft AND raton</u>. Esta busqueda daria como resultado los productos que contengan ambas palabras. Por el contrario, si teclea  <u>raton OR teclado</u>, conseguira una lista de los productos que contengan las dos o solo una de las palabras. Si no se separan las palabras o frases clave con AND o con OR, la busqueda se hara usando por defecto el operador logico ' . strtoupper(ADVANCED_SEARCH_DEFAULT_OPERATOR) . '.<br><br>Puede realizar busquedas exactas de varias palabras encerrandolas entre comillas. Por ejemplo, si busca <u>"ordenador portatil"</u>, obtendras una lista de productos que tengan exactamente esa cadena en ellos.<br><br>Se pueden usar paratensis para controlar el orden de las operaciones logicas. Por ejemplo, puede introducir <u>microsoft and (teclado or raton or "visual basic")</u>.');
define('JS_AT_LEAST_ONE_INPUT', '* Uno de los siguientes campos debe ser introducido:\n    Palabras Clave\n    Fecha de Alta Desde\n    Fecha de Alta Hasta\n    Precio Desde\n    Precio Hasta\n');
define('JS_INVALID_FROM_DATE', '* La Fecha de Alta Desde es invalida\n');
define('JS_INVALID_TO_DATE', '* La Fecha de Alta Hasta es invalida\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* Fecha de Alta Hasta debe ser mayor que Fecha de Alta Desde\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* El Precio Desde debe ser númerico\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* El Precio Hasta debe ser númerico\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Precio Hasta debe ser mayor o igual que Precio Desde\n');
define('JS_INVALID_KEYWORDS', '* Palabras clave incorrectas\n');
?>
