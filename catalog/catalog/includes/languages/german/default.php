<?
/*
German Text for The Exchange Project Preview Release 1.1
Last Update: 14/05/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

define('TEXT_MAIN', 'Willkommen zu dem \'' . STORE_NAME . '\'! Hier ist ein demonstrations online-shop, es werden keine Produkte verkauft oder in rechnung gestellt. Jede Information &uuml;ber Produkte sind rein erfunden. M&ouml;chten Sie diesen online-shop runterladen, oder in diesem Projekt mitarbeiten, dann gehen Sie bitte zu der <a href="http://theexchangeproject.org"><u>support-seite</u></a>. Ihre Meinung ist herzlich Willkommen.<br><br>Dieser online-shop basiert auf \'Preview Release 1.1\', und kann auf der support-seite runtergeladen werden.<br><br>Es werden keine E-Mails aus diesem online-shop mehr verschickt.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann kommt was..');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if ($category_depth == 'top') {
  define('TOP_BAR_TITLE', 'Willkommen zu dem \'' . STORE_NAME . '\'!');
  define('HEADING_TITLE', 'Was Ist Neu?');
  define('SUB_BAR_TITLE', strftime(DATE_FORMAT_LONG, mktime(0,0,0,2,6,2000)));
} elseif ($category_depth == 'nested') {
  define('TOP_BAR_TITLE', 'Neue Produkte in dieser Kategorie');
  define('HEADING_TITLE', 'Was Ist Neu?');
  define('SUB_BAR_TITLE', 'Kategorien');
} elseif ($category_depth == 'products') {
  define('TOP_BAR_TITLE', 'Online Produkte');
  define('HEADING_TITLE', 'Was haben wir den hier?');
  define('TABLE_HEADING_MODEL', 'Modell');
  define('TABLE_HEADING_PRODUCTS', 'Produkte');
  define('TABLE_HEADING_PRICE', 'Preis');
  define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in diese Kategorie.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
}
?>
