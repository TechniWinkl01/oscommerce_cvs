<?
/*
German Text for The Exchange Project Preview Release 2.0
Last Update: 04/05/2001
Author(s): Mathias Kowalkowski (m.kowalkowski@comnet-gmbh.net)
*/

define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which new products are available to purchase?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '"><u>log yourself in</u></a> with your account information.</small>');
define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '"><u>log yourself in</u></a>? Or would you prefer to <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '"><u>create an account</u></a>?');

define('TEXT_MAIN', 'Dies ist eine Vorführ-Online-Shop, es werden hier weder Produkte verkauft noch verschickt. Die Informationen über in diese Online-Shop vorhande Produkte sind rein erfunden. M&ouml;chten Sie diesen Online-Shop runterladen, oder an diesem Projekt mitarbeiten, so besuchen Sie bitte die <a href="http://theexchangeproject.org"><u>Supportseite</u></a>. Kommentare und Anregungen nehmen wir gerne entgegen.<br><br>Dieser Online-Shop basiert auf <font color="#ff0000"><b>' . PROJECT_VERSION . '</b></font>, und kann auf der Supportseite heruntergeladen werden.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann ist was verfügbar');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('TOP_BAR_TITLE', 'Online Produkte');
  define('HEADING_TITLE', 'Was haben wir den hier?');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Modell');
  define('TABLE_HEADING_PRODUCTS', 'Produkte');
  define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
  define('TABLE_HEADING_QUANTITY', 'Anzahl');
  define('TABLE_HEADING_PRICE', 'Preis');
  define('TABLE_HEADING_WEIGHT', 'Gewicht');
  define('TABLE_HEADING_BUY_NOW', 'Kaufen Sie Jetzt');
  define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in diese Kategorie.');
  define('TEXT_NO_PRODUCTS2', 'Es gibt kein Produkt, das von diesem Hersteller vorhanden ist.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
  define('TEXT_SHOW', '<b>Darstellen:</b>');
  define('TEXT_SORT_PRODUCTS', 'Sortierung produkte ');
  define('TEXT_DESCENDINGLY', 'absteigend');
  define('TEXT_ASCENDINGLY', 'aufsteigend');
  define('TEXT_BY', ' durch ');
  define('TEXT_BUY', 'Kaufen 1 \'');
  define('TEXT_NOW', '\' jetzt');
} elseif ($category_depth == 'top') {
  define('TOP_BAR_TITLE', 'Willkommen zu dem \'' . STORE_NAME . '\'!');
  define('HEADING_TITLE', 'Was ist neu?');
  define('SUB_BAR_TITLE', strftime(DATE_FORMAT_LONG, mktime(0,0,0,2,6,2000)));
} elseif ($category_depth == 'nested') {
  define('TOP_BAR_TITLE', 'Neue Produkte in dieser Kategorie');
  define('HEADING_TITLE', 'Was ist neu?');
  define('SUB_BAR_TITLE', 'Kategorien');
}
?>
