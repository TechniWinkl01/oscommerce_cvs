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
define('ENTRY_KEYWORDS', 'Palabras Clave/Frase:');
define('ENTRY_PRICE_FROM', 'Precio Desde:');
define('ENTRY_DATE_ADDED_FROM', 'Fecha de Alta Desde:');
define('ENTRY_TO', 'Hasta:');
define('ENTRY_SORT_BY' , 'Resultado Ordenado Por:');
define('ENTRY_KEYWORDS_TEXT', '&nbsp;<small><font color="#AABBDD">(una o más palabras clave/frase)</font></small>');
define('ENTRY_DATE_ADDED_TEXT', '&nbsp;<small><font color="#AABBDD">(ej. 21/05/1970)</font></small>');
define('TEXT_ALL_CATEGORIES', 'TODAS LAS CATEGORÍAS');
define('TEXT_ALL_MANUFACTURERS', 'TODOS LOS FABRICANTES');
define('TEXT_CATEGORY_NAME', 'Nombre De la Categoría');
define('TEXT_MANUFACTURER_NAME', 'Nombre Del Fabricante');
define('TEXT_PRODUCT_NAME', 'Nombre Del Producto');
define('TEXT_PRICE', 'Precio');
define('TEXT_PERFORM_ADVANCED_SEARCH', 'Realice La Búsqueda Avanzada');
define('TEXT_ADVANCED_SEARCH_TIPS', '&nbsp;<b>Advanced Search Tips</b></font><font face="'. SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '"><br><br>The search engine allows you to do a keyword search on the Product Model, Name, Description and Manufacturer Name.<br><br>When doing a keyword search, you can separate words and phrases by AND or OR. For example, you can enter <u>Microsoft AND mouse</u>. This search would generate results that have both words in them. However, if you type in <u>mouse OR keyboard</u>, you will get a list of products that have both or either words in them. If words are not separated by AND or OR, search will default the logical operator to ' . strtoupper(ADVANCED_SEARCH_DEFAULT_OPERATOR) . '.<br><br>You can also search for exact matches of words by enclosing them in quotes. For example, if you search for <u>"notebook computer"</u>, you will get a list of products that have that exact string in them.<br><br>Brackets can be used to control the order of the logical operations. For example, you can enter <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('JS_AT_LEAST_ONE_INPUT', '* Uno de los siguientes campos debe ser introducido:\n    Palabras Clave\n    Fecha de Alta Desde\n    Fecha de Alta Hasta\n    Precio Desde\n    Precio Hasta\n');
define('JS_INVALID_FROM_DATE', '* La Fecha de Alta Desde es invalida\n');
define('JS_INVALID_TO_DATE', '* La Fecha de Alta Hasta es invalida\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* Fecha de Alta Hasta debe ser mayor que Fecha de Alta Desde\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* El Precio Desde debe ser númerico\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* El Precio Hasta debe ser númerico\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Precio Hasta debe ser mayor o igual que Precio Desde\n');
define('JS_INVALID_KEYWORDS', '* Invalid keywords\n');
?>
