<?
/*
German Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): Mathias Kowalkowski (mathias@zoomed.de)
*/

define('TEXT_MAIN', 'Willkommen zu dem \'' . STORE_NAME . '\'! Hier ist ein demonstrations online-shop, es werden keine Produkte verkauft oder in rechnung gestellt. Jede Information &uuml;ber Produkte sind rein erfunden. M&ouml;chten Sie diesen online-shop runterladen, oder in diesem Projekt mitarbeiten, dann gehen Sie bitte zu der <a href="http://theexchangeproject.org"><u>support-seite</u></a>. Ihre Meinung ist herzlich Willkommen.<br><br>Dieser online-shop basiert auf <font color="#ff0000">Preview Release 2.0</a>, und kann auf der support-seite runtergeladen werden.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann kommt was..');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('TOP_BAR_TITLE', 'Online Produkte');
  define('HEADING_TITLE', 'Was haben wir den hier?');
  define('TABLE_HEADING_MODEL', 'Modell');
  define('TABLE_HEADING_PRODUCTS', 'Produkte');
  define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
  define('TABLE_HEADING_PRICE', 'Preis');
  define('TABLE_HEADING_BUY_NOW', 'Kaufen Sie Jetzt');
  define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in diese Kategorie.');
  define('TEXT_NO_PRODUCTS2', 'Es gibt kein Produkt, das von diesem Hersteller vorhanden ist.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
  define('TEXT_SHOW', '<b>Darstellen:</b>');
  define('TEXT_SORT_PRODUCTS', 'Sortierung produkte ');
  define('TEXT_DESCENDINGLY', 'descendingly');
  define('TEXT_ASCENDINGLY', 'ascendingly');
  define('TEXT_BY', ' durch ');
  define('TEXT_BUY', 'Kaufen 1 \'');
  define('TEXT_NOW', '\' jetzt');
} elseif ($category_depth == 'top') {
  define('TOP_BAR_TITLE', 'Willkommen zu dem \'' . STORE_NAME . '\'!');
  define('HEADING_TITLE', 'Was Ist Neu?');
  define('SUB_BAR_TITLE', strftime(DATE_FORMAT_LONG, mktime(0,0,0,2,6,2000)));
} elseif ($category_depth == 'nested') {
  define('TOP_BAR_TITLE', 'Neue Produkte in dieser Kategorie');
  define('HEADING_TITLE', 'Was Ist Neu?');
  define('SUB_BAR_TITLE', 'Kategorien');
}
?>
