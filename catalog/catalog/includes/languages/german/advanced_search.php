<?php
/*
  $Id: advanced_search.php,v 1.18 2003/02/16 00:42:02 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Erweiterte Suche');
define('HEADING_TITLE', 'Geben Sie Ihre Suchkriterien ein');

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

define('JS_AT_LEAST_ONE_INPUT', '* Eines der folgenden Felder muss ausgefüllt werden:\n    Stichworte\n    Datum hinzugefügt von\n    Datum hinzugefügt bis\n    Preis ab\n    Preis bis\n');
define('JS_INVALID_FROM_DATE', '* Unzulässiges von Datum\n');
define('JS_INVALID_TO_DATE', '* Unzulässiges bis jetzt\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* Das Datum von muss grösser oder gleich bis jetzt sein\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Preis ab, muss eine Zahl sein\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Preis bis, muss eine Zahl sein\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Preis bis muss größer oder gleich Preis ab sein.\n');
define('JS_INVALID_KEYWORDS', '* Suchbegriff unzulässig\n');
?>