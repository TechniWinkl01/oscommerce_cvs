<?
/*
Spanish Text for The Exchange Project Administration Tool Preview Release 2.0
Last Update: 11/19/2000
Author(s): David Garcia Watkins (dgw@q-logic.org)
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'es_ES'
// on FreeBSD 4.0 I use 'es_ES.ISO_8859-1'
// this may not work under win32 environments..
setlocale('LC_TIME', 'es_ES.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

// the CURRENCY_* constants will be used to format the currency to the selected locale.. this will be used with the 
// tep_number_format() function.. although the function is here, it is not in use yet..
define('CURRENCY_BEFORE', ''); // currency character(s) before value
define('CURRENCY_AFTER', 'Pts'); // currency character(s) after value
define('CURRENCY_DECIMAL', '.'); // currency decimal point character
define('CURRENCY_THOUSANDS', ','); // currency thousands separator character
define('CURRENCY_VALUE', 1); // currency value to whats stored in the database(products_price).. this will be set to Euro (not USD)..
// for example:
// products_price = 30.00 (in Euro)
// currency_value = 0.9551 (US to Euro)
// products price with locale currency = currency_value * products_price

// page title
define('TITLE', 'The Exchange Project');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administracion');
define('HEADER_TITLE_SUPPORT_SITE', 'Soporte');
define('HEADER_TITLE_ONLINE_DEMO', 'Demostracion');
define('HEADER_TITLE_ADMINISTRATION', 'Administracion');

// text for gender
define('MALE', 'Varon');
define('FEMALE', 'Mujer');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// categories box text in all boxes
define('BOX_HEADING_SEARCH', 'Buscar');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuracion');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalogo');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categorias / Productos');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Atributos');
define('BOX_CATALOG_MANUFACTURERS', 'Fabricantes');
define('BOX_CATALOG_REVIEWS', 'Comentarios');
define('BOX_CATALOG_SPECIALS', 'Ofertas');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Proximamente');

// categories box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Clientes');
define('BOX_CUSTOMERS_CUSTOMERS', 'Clientes');
define('BOX_CUSTOMERS_ORDERS', 'Pedidos');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Zonas / Impuestos');
define('BOX_TAXES_ZONES', 'Zonas');

// whats_new box text in includes/boxes/statistics.php
define('BOX_HEADING_STATISTICS', 'Estadisticas');
define('BOX_STATISTICS_PRODUCTS_VIEWED', 'Productos Mas Vistos');
define('BOX_STATISTICS_PRODUCTS_PURCHASED', 'Productos Mas Comprados');
define('BOX_STATISTICS_ORDERS_TOTAL', 'Total Pedidos por Cliente');

// javascript messages
define('JS_ERROR', 'Ha habido errores procesando su formulario!\nPor favor, haga las siguiente modificaciones:\n\n');

define('JS_REVIEW_TEXT', '* El texto de un \'Comentario\' debe tener al menos ' . REVIEW_TEXT_MIN_LENGTH . ' letras.\n');
define('JS_REVIEW_RATING', '* Debe evaluar el producto.\n');

define('JS_CATEGORIES_NAME', '* La categoria necesita un nombre\n');
define('JS_SORT_ORDER', '* Se necesita un orden numerico para la categoria\n');
define('JS_CATEGORIES_IMAGE', '* Se necesita una imagen para la categoria\n');
define('JS_CATEGORY_DELETE_CONFIRM', '*** ADVERTENCIA ***\n\n¿Realmente quiere eliminar esta categoria Y los productos que contenga?');

define('JS_OPTIONS_OPTION_NAME', '* La opcion necesita un nombre\n');
define('JS_OPTIONS_VALUE_NAME', '* El valor de la opcion necesita un nombre\n');
define('JS_OPTIONS_VALUE_PRICE', '* El atributo necesita un precio\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* El atributo necesita un prefijo para el precio\n');

define('JS_MANUFACTURERS_NAME', '* El fabricante necesita un nombre\n');
define('JS_MANUFACTURERS_LOCATION', '* Necesita especificar la posicion del fabricante (0=izquierda, 1=derecha)\n');
define('JS_MANUFACTURERS_IMAGE', '* El fabricante necesita una imagen\n');
define('JS_MANUFACTURERS_DELETE_CONFIRM', '*** ADVERTENCIA ***\n\n¿Realmente quiere eliminar este fabricante Y todos los productos relacionados con el?');

define('JS_PRODUCTS_NAME', '* El producto necesita un nombre\n');
define('JS_PRODUCTS_DESCRIPTION', '* El producto necesita una descripcion\n');
define('JS_PRODUCTS_PRICE', '* El producto necesita un precio\n');
define('JS_PRODUCTS_WEIGHT', '* Debe especificar el peso del producto\n');
define('JS_PRODUCTS_QUANTITY', '* Debe especificar la cantidad\n');
define('JS_PRODUCTS_MODEL', '* Debe especificar el modelo\n');
define('JS_PRODUCTS_IMAGE', '* Debe suministrar una imagen\n');

define('JS_PRODUCTS_EXPECTED_NAME', '* El campo \'Producto\' debe tener un valor\n');
define('JS_PRODUCTS_EXPECTED_DATE', '* La fecha debe estar en este formato: xx/xx/xxxx (dia/mes/año).\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Debe rellenar el precio\n');

define('JS_GENDER', '* Debe elegir un \'Sexo\'.\n');
define('JS_FIRST_NAME', '* El \'Nombre\' debe tener al menos ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.\n');
define('JS_LAST_NAME', '* El \'Apellido\' debe tener al menos ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.\n');
define('JS_DOB', '* La \'Fecha de Nacimiento\' debe tener el formato: xx/xx/xxxx (dia/mes/año).\n');
define('JS_EMAIL_ADDRESS', '* El \'E-Mail\' debe tener al menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.\n');
define('JS_ADDRESS', '* El \'Domicilio\' debe tener al menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.\n');
define('JS_POST_CODE', '* El \'Codigo Postal\' debe tener al menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras.\n');
define('JS_CITY', '* La \'Ciudad\' debe tener al menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.\n');
define('JS_STATE', '* Debe indicar la \'Provincia\'.\n');
define('JS_STATE_SELECT', '-- Seleccione Arriba --');
define('JS_ZONE', '* La \'Provincia\' se debe seleccionar de la lista para este pais.');
define('JS_COUNTRY', '* Debe seleccionar un \'Pais\'.\n');
define('JS_TELEPHONE', '* El \'Telefono\' debe tener al menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.\n');
define('JS_PASSWORD', '* La \'Contraseña\' y \'Confirmacion\' deben ser iguales y tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.\n');

define('CATEGORY_PERSONAL', '<b>[ Personal ]</b>');
define('CATEGORY_ADDRESS', '<b>[ Domicilio ]</b>');
define('CATEGORY_CONTACT', '<b>[ Contacto ]</b>');
define('CATEGORY_PASSWORD', '<b>[ Contraseña ]</b>');
define('ENTRY_GENDER', 'Sexo:');
define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_FIRST_NAME', 'Nombre:');
define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_LAST_NAME', 'Apellidos:');
define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_DATE_OF_BIRTH', 'Fecha de Nacimiento:');
define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(ej. 21/05/1970) <font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_STREET_ADDRESS', 'Direccion:');
define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_SUBURB', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Codigo Postal:');
define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_CITY', 'Poblacion:');
define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_STATE', 'Provincia:');
define('ENTRY_STATE_TEXT', '');
define('ENTRY_COUNTRY', 'Pais:');
define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_TELEPHONE_NUMBER', 'Telefono:');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_FAX_NUMBER', 'Fax:');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_PASSWORD', 'Contraseña:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirmacion:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('PASSWORD_HIDDEN', '--OCULTO--');

// images
define('IMAGE_BACK', 'Atras');
define('IMAGE_CANCEL', 'Cancelar');
define('IMAGE_CONFIRM', 'Confirmar');
define('IMAGE_COPY', 'Copiar');
define('IMAGE_COPY_TO', 'Copiar A');
define('IMAGE_DELETE', 'Eliminar');
define('IMAGE_EDIT', 'Editar');
define('IMAGE_INSERT', 'Insertar');
define('IMAGE_MODIFY', 'Modificar');
define('IMAGE_MOVE', 'Mover');
define('IMAGE_NEW_CATEGORY', 'Nueva Categoria');
define('IMAGE_NEW_PRODUCT', 'Nuevo Producto');
define('IMAGE_NEW_TAX_CLASS', 'Nuevo Tipo de Impuesto');
define('IMAGE_NEW_ZONE', 'Nueva Zona');
define('IMAGE_PREVIEW', 'Ver');
define('IMAGE_SAVE', 'Grabar');
define('IMAGE_SEARCH', 'Buscar');
define('IMAGE_SELECT', 'Seleccionar');
define('IMAGE_UPDATE', 'Actualizar');

define('IMAGE_ICON_INFO', 'Info');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Paginas:');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> comentarios)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> fabricantes)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> ofertas)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos esperados)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> clientes)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> pedidos)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> tipos de impuesto)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> zonas)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Principio');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Anterior');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Siguinete');
define('PREVNEXT_TITLE_LAST_PAGE', 'Final');
define('PREVNEXT_TITLE_PAGE_NO', 'Pagina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Anteriores %d Paginas');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Siguientes %d Paginas');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;PRINCIPIO');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt; Anterior]');
define('PREVNEXT_BUTTON_NEXT', '[Siguiente &gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'FINAL&gt;&gt;');
?>
