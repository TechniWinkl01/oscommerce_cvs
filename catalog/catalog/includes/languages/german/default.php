<?
/*
German Text for The Exchange Project Preview Release 2.2
Last Update: 17/05/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

define('TEXT_GREETING_PERSONAL', 'Schön das Sie wieder da sind <span class="greetUser">%s!</span>');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Wenn Sie nicht %s sind, melden Sie sich bitte <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '"><u>hier</u></a> mit Ihrem Kundenkonto an.</small>');
define('TEXT_GREETING_GUEST', 'Herzlich Willkommen <span class="greetUser">Gast!</span> Möchtne Sie sich <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '"><u>anmelden</u></a>? Oder wollen Sie ein <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '"><u>Kundenkonto</u></a> eröffnen?');

define('TEXT_MAIN', 'Dies ist eine Vorführ-Online-Shop, es werden hier weder Produkte verkauft noch verschickt. Die Informationen über in diese Online-Shop vorhande Produkte sind rein erfunden. M&ouml;chten Sie diesen Online-Shop runterladen, oder an diesem Projekt mitarbeiten, so besuchen Sie bitte die <a href="http://theexchangeproject.org"><u>Supportseite</u></a>. Kommentare und Anregungen nehmen wir gerne entgegen.<br><br>Dieser Online-Shop basiert auf <font color="#ff0000"><b>' . PROJECT_VERSION . '</b></font>, und kann auf der Supportseite heruntergeladen werden.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann ist was verfügbar');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('TOP_BAR_TITLE', 'Produkte');
  define('HEADING_TITLE', 'Was haben wir den hier?');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Artikelnr.');
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
  define('TEXT_SORT_PRODUCTS', 'Sortierung Produkte ');
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
