<?php
/*
  $Id: default.php,v 1.19 2002/01/14 10:34:18 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', 'Dies ist eine Vorf&uuml;hr-Online-Shop, es werden hier weder Produkte verkauft noch verschickt. Die Informationen über die in diesem Online-Shop vorhandenen Produkte sind frei erfunden. M&ouml;chten Sie diesen Online-Shop runterladen, oder an diesem Projekt mitarbeiten, so besuchen Sie bitte die <a href="http://oscommerce.com"><u>Supportseite</u></a>. Kommentare und Anregungen nehmen wir gerne entgegen.<br><br>Dieser Online-Shop basiert auf <font color="#ff0000"><b>' . PROJECT_VERSION . '</b></font>, und kann auf der Supportseite heruntergeladen werden.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann ist was verfügbar');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('HEADING_TITLE', 'Was haben wir denn hier?');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Artikelnr.');
  define('TABLE_HEADING_PRODUCTS', 'Produkte');
  define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
  define('TABLE_HEADING_QUANTITY', 'Anzahl');
  define('TABLE_HEADING_PRICE', 'Preis');
  define('TABLE_HEADING_WEIGHT', 'Gewicht');
  define('TABLE_HEADING_BUY_NOW', 'Kaufen Sie Jetzt');
  define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in dieser Kategorie.');
  define('TEXT_NO_PRODUCTS2', 'Es gibt kein Produkt, das von diesem Hersteller stammt.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
  define('TEXT_SHOW', '<b>Darstellen:</b>');
  define('TEXT_BUY', 'Jetzt 1 \'');
  define('TEXT_NOW', '\' Kaufen!');
  define('TEXT_ALL', 'Alle');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'Was ist neu?');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Kategorien');
}
?>
