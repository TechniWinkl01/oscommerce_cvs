<?
/*
Spanish Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): David Garcia Watkins (dgw@q-logic.org)
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'es_ES'
// on FreeBSD 4.0 I use 'es_ES.ISO_8859-1'
// Ive succeded under win32 with 'sp'...
setlocale('LC_TIME', 'es_ES.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y');  // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

// the CURRENCY_* constants will be used to format the currency to the selected locale.. this will be used with the 
// tep_currency_format() function..
define('CURRENCY_BEFORE', ''); // currency character(s) before value
define('CURRENCY_AFTER', 'Pts'); // currency character(s) after value
define('CURRENCY_DECIMAL', '.'); // currency decimal point character
define('CURRENCY_DECIMAL_PLACES', 0);
define('CURRENCY_THOUSANDS', ','); // currency thousands separator character
define('CURRENCY_VALUE', 'ESP'); // currency value for exchange rate
// for example:
// products_price = 30.00
// currency_value = 1.1036 (Euro)
// products price with locale currency = CURRENCY_BEFORE . (currency_value * products_price) . CURRENCY_AFTER

// page title
define('TITLE', 'The Exchange Project');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Crear Cuenta');
define('HEADER_TITLE_MY_ACCOUNT', 'Mi Cuenta');
define('HEADER_TITLE_CART_CONTENTS', 'Ver Cesta');
define('HEADER_TITLE_CHECKOUT', 'Realizar Pedido');
 define('HEADER_TITLE_CONTACT_US', 'Contactenos');
define('HEADER_TITLE_TOP', 'Inicio');
define('HEADER_TITLE_CATALOG', 'Catalogo');
define('HEADER_TITLE_LOGOFF', 'Salir');
define('HEADER_TITLE_LOGIN', 'Entrar');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'peticiones desde');

// text for gender
define('MALE', 'Varon');
define('FEMALE', 'Mujer');
define('MALE_ADDRESS', 'Sr.');
define('FEMALE_ADDRESS', 'Sra.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Categorias');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Fabricantes');
define('BOX_MANUFACTURERS_SELECT_ONE', 'Seleccione uno:');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Novedades');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Busqueda Rapida');
define('BOX_SEARCH_TEXT', 'Use palabras clave para encontrar el producto que busca.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Búsqueda Avanzada');

// add_a_quickie box text in includes/boxes/add_a_quickie.php
define('BOX_HEADING_ADD_PRODUCT_ID', 'Compra Rapida');
define('BOX_ADD_PRODUCT_ID_TEXT', 'Introduzca el Identificador del producto que desea añadir a la cesta.');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Ofertas');
define('BOX_SPECIALS_MORE', 'Mas Ofertas..');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Comentarios');
define('BOX_REVIEWS_MORE', 'Mas Comentarios..');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Cesta de la Compra');
define('BOX_SHOPPING_CART_EMPTY', '..esta vacia!');
define('BOX_SHOPPING_CART_SUBTOTAL', 'Subtotal:');
define('BOX_SHOPPING_CART_VIEW_CONTENTS', 'Ver Contenido');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Los Mas Vendidos');
define('BOX_HEADING_BESTSELLERS_IN', 'Los Mas Vendidos en <br>&nbsp;&nbsp;');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Idiomas');
define('BOX_LANGUAGES_ENGLISH', 'English');
define('BOX_LANGUAGES_DEUTSCH', 'Deutsch');
define('BOX_LANGUAGES_ESPANOL', 'Español');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Seleccionar');
define('TYPE_BELOW', 'Escriba Debajo');

// checkout procedure text
define('CHECKOUT_BAR_CART_CONTENTS', 'cesta');
define('CHECKOUT_BAR_DELIVERY_ADDRESS', 'entrega');
define('CHECKOUT_BAR_PAYMENT_METHOD', 'pago');
define('CHECKOUT_BAR_CONFIRMATION', 'confirmacion');
define('CHECKOUT_BAR_FINISHED', 'finalizado!');

// javascript messages
define('JS_ERROR', 'Hay errores en su formulario!\nPor favor, haga las siguiente correciones:\n\n');

define('JS_REVIEW_TEXT', '* Su \'Comentario\' debe tener al menos ' . REVIEW_TEXT_MIN_LENGTH . ' letras.\n');
define('JS_REVIEW_RATING', '* Debe evaluar el producto sobre el que opina.\n');

define('JS_GENDER', '* Debe indicar su \'Sexo\'.\n');
define('JS_FIRST_NAME', '* Su \'Nombre\' debe tener al menos ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.\n');
define('JS_LAST_NAME', '* Sus \'Apellidos\' deben tener al menos ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.\n');
define('JS_DOB', '* La \'Fecha de nacimiento\' debe tener el formato: xx/xx/xxxx (dia/mes/año).\n');
define('JS_EMAIL_ADDRESS', '* Su \'E-Mail\' debe tener al menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.\n');
define('JS_ADDRESS', '* Su \'Direccion\' debe tener al menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.\n');
define('JS_POST_CODE', '* Su \'Codigo Postal\' debe tener al menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras.\n');
define('JS_CITY', '* La \'Poblacion\' debe tener al menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.\n');
define('JS_STATE', '* Debe indicar la \'Provincia\'.\n');
define('JS_STATE_SELECT', '-- Seleccione Arriba --');
define('JS_ZONE', '* La \'Provincia\' se debe seleccionar de la lista para este pais.');
define('JS_COUNTRY', '* Debe seleccionar su \'Pais\'.');
define('JS_TELEPHONE', '* El \'Telefono\' debe tener al menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.\n');
define('JS_PASSWORD', '* La \'Contraseña\' y la \'Confirmacion\' deben ser iguales y tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.\n');

define('CATEGORY_PERSONAL', '<b>[ Personal ]</b>');
define('CATEGORY_ADDRESS', '<b>[ Direccion ]</b>');
define('CATEGORY_CONTACT', '<b>[ Contacto ]</b>');
define('CATEGORY_PASSWORD', '<b>[ Contraseña ]</b>');
define('ENTRY_GENDER', 'Sexo:');
define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_FIRST_NAME', 'Nombre:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_LAST_NAME', 'Apellidos:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_DATE_OF_BIRTH', 'Fecha de Nacimiento:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<small><font color="#FF0000">(p.ej. 21/05/1970)</font></small>');
define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(eg. 21/05/1970) <font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">Su Email no parece correcto!</font></small>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<small><font color="#FF0000">email ya existe!</font></small>');
define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_STREET_ADDRESS', 'Direccion:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_SUBURB', 'Suburbio');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Codigo Postal:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_CITY', 'Poblacion:');
define('ENTRY_CITY_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_STATE', 'Provincia:');
define('ENTRY_STATE_ERROR', '');
define('ENTRY_STATE_TEXT', '');
define('ENTRY_COUNTRY', 'Pais:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_TELEPHONE_NUMBER', 'Telefono:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_FAX_NUMBER', 'Fax:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_PASSWORD', 'Contraseña:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirme Contraseña:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('ENTRY_PASSWORD_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>');
define('PASSWORD_HIDDEN', '--OCULTO--');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Paginas de Resultados:'); 
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos)'); 
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> pedidos)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Principio');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Anterior');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Siguiente');
define('PREVNEXT_TITLE_LAST_PAGE', 'Final');
define('PREVNEXT_TITLE_PAGE_NO', 'Pagina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Anteriores %d Paginas');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Siguientes %d Paginas');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;PRINCIPIO');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;Anterior]');
define('PREVNEXT_BUTTON_NEXT', '[Siguiente&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'FINAL&gt;&gt;');
?>
