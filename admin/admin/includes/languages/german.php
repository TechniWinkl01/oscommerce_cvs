<?
/*
German Text for The Exchange Project Administration Tool Preview Release 2.0
Last Update: 02/12/2000
Author(s): Mathias Kowalkowski (mathias@zoomed.de)
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'de_DE'
// on FreeBSD 4.0 I use 'de_DE.ISO_8859-1'
// this may not work under win32 environments..
setlocale('LC_TIME', 'de_DE.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

// the CURRENCY_* constants will be used to format the currency to the selected locale..
// this is used with the tep_currency_format() function..
define('CURRENCY_VALUE', 'DEM'); // currency value for exchange rate

// page title
define('TITLE', 'The Exchange Project');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Supportseite');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Katalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');

// text for gender
define('MALE', 'm&auuml;nnlich');
define('FEMALE', 'weiblich');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt/mm/jjjj');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Konfiguration');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Katalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Kategorien / Artikel');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Produktmerkmale');
define('BOX_CATALOG_MANUFACTURERS', 'Hersteller');
define('BOX_CATALOG_REVIEWS', 'Berichte');
define('BOX_CATALOG_SPECIALS', 'Sonderangebote');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'erwartete Artikel');

// categories box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_ORDERS', 'Bestellungen');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Land / Steuer');
define('BOX_TAXES_COUNTRIES', 'Land');
define('BOX_TAXES_ZONES', 'Zonen');
define('BOX_TAXES_TAX_CLASSES', 'Steuerklassen');
define('BOX_TAXES_TAX_RATES', 'Steuers&auml;tze');

// whats_new box text in includes/boxes/statistics.php
define('BOX_HEADING_STATISTICS', 'Statistiken');
define('BOX_STATISTICS_PRODUCTS_VIEWED', 'besuchte Artikel');
define('BOX_STATISTICS_PRODUCTS_PURCHASED', 'gekaufte Artikel');
define('BOX_STATISTICS_ORDERS_TOTAL', 'Kunden-Bestellstatistik');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Lokalisation');
define('BOX_LOCALIZATION_CURRENCIES', 'W&auml;hrungen');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Werkzeuge');
define('BOX_TOOLS_BACKUP', 'Datensicherung');

// javascript messages
define('JS_ERROR', 'Während der Eingabe sind Fehler aufgetreten!\nBitte korrigieren Sie folgendes:\n\n');

define('JS_REVIEW_TEXT', '* Der \'Bericht\' muss mindestens ' . REVIEW_TEXT_MIN_LENGTH . ' Zeichen lang sein.\n');
define('JS_REVIEW_RATING', '* Sie müssen das Produkte benoten.\n');

define('JS_CATEGORIES_NAME', '* Die neue Kategorie muss einen Namen haben\n');
define('JS_SORT_ORDER', '* Eine numerische Sortierreihenfolge wird für diese Kategorie benötigt\n');
define('JS_CATEGORIES_IMAGE', '* Ein Bild wird für diese Kategorie benötigt\n');
define('JS_CATEGORY_DELETE_CONFIRM', '*** WARNUNG ***\n\nMöchten Sie diese Kategorie und alle mit ihr verknüpften Artikel entfernen?');

define('JS_OPTIONS_OPTION_NAME', '* Die neue Option muss einen Namen haben\n');
define('JS_OPTIONS_VALUE_NAME', '* Die neue Option muss einen Wert haben\n');
define('JS_OPTIONS_VALUE_PRICE', '* Sie müssen diesem Wert einen Preis zuordnen\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Sie müssen einen Prefix angeben (+/-)\n');

define('JS_MANUFACTURERS_NAME', '* Der neue Hersteller muss einen Namen haben\n');
define('JS_MANUFACTURERS_LOCATION', '* Der neue Hersteller muss eine Position haben (0=links, 1=rechts)\n');
define('JS_MANUFACTURERS_IMAGE', '* Der neue Hersteller muss ein Bild haben\n');
define('JS_MANUFACTURERS_DELETE_CONFIRM', '*** WARNUNG ***\n\nMöchten Sie diesen Hersteller und alle mit ihm verknüpften Artikel entfernen?');

define('JS_PRODUCTS_NAME', '* Der neue Artikel muss einen Namen haben\n');
define('JS_PRODUCTS_DESCRIPTION', '* Der neue Artikel muss eine Beschreibung haben\n');
define('JS_PRODUCTS_PRICE', '* Der neue Artikel muss einen Preis haben\n');
define('JS_PRODUCTS_WEIGHT', '* Der neue Artikel muss eine Gewichtsangabe haben\n');
define('JS_PRODUCTS_QUANTITY', '* Sie müssen dem neuen Artikel eine verfügbare Anzahl zuordnen\n');
define('JS_PRODUCTS_MODEL', '* Sie müssen dem neuen Artikel eine Modellvariante zuordnen\n');
define('JS_PRODUCTS_IMAGE', '* Sie müssen dem Artikel ein Bild zuordnen\n');

define('JS_PRODUCTS_EXPECTED_NAME', '* Der \'Product\'-Eintrag muss ausgefüllt werden\n');
define('JS_PRODUCTS_EXPECTED_DATE', '* Das Datum des Eintreffens muss in diesem Format sein : xx/xx/xxxx (Tag/Monat/Jahr).\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Es muss ein neuer Preis für diesen Artikel festgelegt werden\n');

define('JS_GENDER', '* Das \'Geschlecht\' muss ausgewählt werden.\n');
define('JS_FIRST_NAME', '* Der \'Vorname\' muss mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_LAST_NAME', '* Der \'Nachname\' muss mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_DOB', '* Das \'Geburtsdatum\' muss folgendes Format haben: xx/xx/xxxx (Tag/Jahr/Monat).\n');
define('JS_EMAIL_ADDRESS', '* Die \'E-Mail Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_ADDRESS', '* Die \'Strasse\' muss mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_POST_CODE', '* Die \'Postleitzahl\' muss mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_CITY', '* Die \'Stadt\' muss mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_STATE', '* Das \'Bundesland\' muss ausgewählt werden.\n');
define('JS_STATE_SELECT', '-- Wählen Sie oberhalb --');
define('JS_ZONE', '* Das \'Bundesland\' muss aus der Liste für dieses Land ausgewählt werden.');
define('JS_COUNTRY', '* Das \'Land\' muss ausgewählt werden.\n');
define('JS_TELEPHONE', '* Die \'Telefonnummer\' muss aus mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_PASSWORD', '* Das \'Passwort\' sowie die \'Passwortbestätigung\' müssen übereinstimmen und aus mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Auftragsnummer ' . $HTTP_GET_VARS['error'] . ' existiert nicht!');

define('CATEGORY_PERSONAL', '<b>[ Pers&ouml;nliche Daten ]</b>');
define('CATEGORY_ADDRESS', '<b>[ Adresse ]</b>');
define('CATEGORY_CONTACT', '<b>[ Kontakt ]</b>');
define('CATEGORY_PASSWORD', '<b>[ Passwort ]</b>');
define('ENTRY_GENDER', 'Geschlecht:');
define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_FIRST_NAME', 'Vorname:');
define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_LAST_NAME', 'Nachname:');
define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');
define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(eg. 21/05/1970) <font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Adresse:');
define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_STREET_ADDRESS', 'Strasse:');
define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_SUBURB', 'weitere Anschrift:');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postleitzahl:');
define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_CITY', 'Stadt:');
define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_STATE_TEXT', '');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_PASSWORD', 'Passwort:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Passwortbest&auml;tigung:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich</font></small>');
define('PASSWORD_HIDDEN', '--VERSTECKT--');

// images
define('IMAGE_BACK', 'Zur&uuml;ck');
define('IMAGE_CANCEL', 'Abbruch');
define('IMAGE_CONFIRM', 'Best&auml;tigen');
define('IMAGE_COPY', 'Kopieren');
define('IMAGE_COPY_TO', 'Kopieren nach');
define('IMAGE_DELETE', 'L&ouml;schen');
define('IMAGE_EDIT', 'Editieren');
define('IMAGE_INSERT', 'Einf&uuml;gen');
define('IMAGE_MODIFY', '&Auml;ndern');
define('IMAGE_MOVE', 'Verschieben');
define('IMAGE_NEW_CATEGORY', 'Neue Kategorie');
define('IMAGE_NEW_PRODUCT', 'Neuer Artikel');
define('IMAGE_NEW_TAX_CLASS', 'Neue Steuer-Klasse');
define('IMAGE_NEW_ZONE', 'Neue Zone');
define('IMAGE_NEW_COUNTRY', 'Neue Land');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_PREVIEW', 'Vorschau');
define('IMAGE_SAVE', 'Speichern');
define('IMAGE_SEARCH', 'Suchen');
define('IMAGE_SELECT', 'Ausw&auml;hlen');
define('IMAGE_UPDATE', 'Aktualisieren');

define('IMAGE_ICON_INFO', 'Information');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seite:');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Berichten)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Herstellern)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sonderartikeln)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Artikeln)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> erwarteten Artikeln)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kunden)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellungen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerklassen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuers&auml;tzen)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerzonen)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Land)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'erste Seite');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'vorherige Seite');
define('PREVNEXT_TITLE_NEXT_PAGE', 'n&auml;chste Seite');
define('PREVNEXT_TITLE_LAST_PAGE', 'letzte Seite');
define('PREVNEXT_TITLE_PAGE_NO', 'Seite %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Vorhergehende %d Seiten');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Nächste %d Seiten');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;ERSTE');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt; vorherige]');
define('PREVNEXT_BUTTON_NEXT', '[n&auml;chste &gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LETZTE&gt;&gt;');
?>
