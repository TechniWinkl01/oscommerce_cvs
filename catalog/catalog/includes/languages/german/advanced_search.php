<?php
/*
  $Id: advanced_search.php,v 1.15 2002/04/17 15:57:07 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Erweiterte Suche');
define('HEADING_TITLE', 'geben Sie Ihre Suchkriterien ein');

define('ENTRY_CATEGORIES', 'Kategorien:');
define('ENTRY_INCLUDES_SUBCATEGORIES', 'Unterkategorien mit einbeziehen');
define('ENTRY_MANUFACTURER', 'Hersteller:');
define('ENTRY_KEYWORDS', 'Stichworte:');
define('ENTRY_PRICE_FROM', 'Preis ab:');
define('ENTRY_DATE_ADDED_FROM', 'hinzugef&uuml;gt von:');
define('ENTRY_TO', 'bis:');
define('ENTRY_KEYWORDS_TEXT', '&nbsp;<small><font color="#AABBDD">(Worte durch Leerzeichen trennen)</font></small>');
define('ENTRY_DATE_ADDED_TEXT', '&nbsp;<small><font color="#AABBDD">(z.B. 21/05/1970)</font></small>');
define('TEXT_ALL_CATEGORIES', 'ALLE KATEGORIEN');
define('TEXT_ALL_MANUFACTURERS', 'ALLE HERSTELLER');
define('TEXT_CATEGORY_NAME', 'Kategorie Name');
define('TEXT_MANUFACTURER_NAME', 'Hersteller Name');
define('TEXT_PRODUCT_NAME', 'Produkt Name');
define('TEXT_PRICE', 'Preis');
define('TEXT_SEARCH_IN_DESCRIPTION', 'Auch in Beschreibungen suchen');
define('TEXT_ADVANCED_SEARCH_TIPS_HEADING', 'Hilfe zur erweiterten Suche');
define('TEXT_ADVANCED_SEARCH_TIPS', 'Die Suchmaschine erm&ouml;glicht Ihnen die Suche in den Produktnamen, Produktbeschreibungen, Herstellern und Modellen.<br><br>Sie haben die M&ouml;glichkeit logische Operatoren wie "AND" (Und) und "OR" (oder) zu verwenden.<br><br>Als Beispiel k&ouml;nnten Sie also angeben: <u>Microsoft AND Maus</u>.<br><br>Desweiteren k&ouml;nnen Sie Klammern verwenden um die Suche zu verschachteln, also z.B.:<br><br><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br><br>Mit Anf&uuml;hrungszeichen können Sie mehrere Worte zu einem Suchbegriff zusammenfassen.');

define('JS_AT_LEAST_ONE_INPUT', '* Eines der folgenden Felder muß ausgefüllt werden:\n    Stichworte\n    Datum hinzugefügt von\n    Datum hinzugefügt bis\n    Preis ab\n    Preis bis\n');
define('JS_INVALID_FROM_DATE', '* Unzulässiges von Datum\n');
define('JS_INVALID_TO_DATE', '* Unzulässiges bis jetzt\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* Das Datum von muss grösser oder gleich bis jetzt sein\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Preis ab, muß eine Zahl sein\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Preis bis, muß eine Zahl sein\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Preis bis muß größer oder gleich Preis ab sein.\n');
define('JS_INVALID_KEYWORDS', '* Suchbegriff unzulässig\n');
?>