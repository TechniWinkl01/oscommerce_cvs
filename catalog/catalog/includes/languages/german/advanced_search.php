<?php
/*
  $Id: advanced_search.php,v 1.19 2003/06/05 23:23:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Erweiterte Suche');
define('NAVBAR_TITLE_2', 'Suchergebnisse');

define('HEADING_TITLE_1', 'Geben Sie Ihre Suchkriterien ein');
define('HEADING_TITLE_2', 'Artikel, welche den Suchkriterien entsprechen');

define('HEADING_SEARCH_CRITERIA', 'Geben Sie Ihre Stichworte ein');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Auch in den Beschreibungen suchen');
define('ENTRY_CATEGORIES', 'Kategorien:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Unterkategorien mit einbeziehen');
define('ENTRY_MANUFACTURERS', 'Hersteller:');
define('ENTRY_PRICE_FROM', 'Preis ab:');
define('ENTRY_PRICE_TO', 'Preis bis:');
define('ENTRY_DATE_FROM', 'hinzugef&uuml;gt von:');
define('ENTRY_DATE_TO', 'hinzugef&uuml;gt bis:');

define('TEXT_SEARCH_HELP_LINK', '<u>Hilfe zur erweiterten Suche</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Alle Kategorien');
define('TEXT_ALL_MANUFACTURERS', 'Alle Hersteller');

define('HEADING_SEARCH_HELP', 'Hilfe zur erweiterten Suche');
define('TEXT_SEARCH_HELP', 'Die Suchfunktion erm&ouml;glicht Ihnen die Suche in den Produktnamen, Produktbeschreibungen, Herstellern und Artikelnummern.<br><br>Sie haben die M&ouml;glichkeit logische Operatoren wie "AND" (Und) und "OR" (oder) zu verwenden.<br><br>Als Beispiel k&ouml;nnten Sie also angeben: <u>Microsoft AND Maus</u>.<br><br>Desweiteren k&ouml;nnen Sie Klammern verwenden um die Suche zu verschachteln, also z.B.:<br><br><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br><br>Mit Anf&uuml;hrungszeichen k&ouml;nnen Sie mehrere Worte zu einem Suchbegriff zusammenfassen.');
define('TEXT_CLOSE_WINDOW', '<u>Fenster schliessen</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Artikelnummer');
define('TABLE_HEADING_PRODUCTS', 'Bezeichnung');
define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
define('TABLE_HEADING_QUANTITY', 'Anzahl');
define('TABLE_HEADING_PRICE', 'Einzelpreis');
define('TABLE_HEADING_WEIGHT', 'Gewicht');
define('TABLE_HEADING_BUY_NOW', 'jetzt bestellen');

define('TEXT_NO_PRODUCTS', 'Es wurden keine Artikel gefunden, die den Suchkriterien entsprechen.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Atleast one of the fields in the search form must be entered.');
define('ERROR_INVALID_FROM_DATE', 'Unzul�ssiges von Datum');
define('ERROR_INVALID_TO_DATE', 'Unzul�ssiges bis jetzt');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Das Datum von muss gr�sser oder gleich bis jetzt sein');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Preis ab, muss eine Zahl sein');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'Preis bis, muss eine Zahl sein');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Preis bis muss gr��er oder gleich Preis ab sein.');
define('ERROR_INVALID_KEYWORDS', 'Suchbegriff unzul�ssig');
?>