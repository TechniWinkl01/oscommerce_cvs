<?
/*
German Text for The Exchange Project Preview Release 2
Last Update: 13/05/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'de_DE'
// on FreeBSD 4.0 I use 'de_DE.ISO_8859-1'
// this may not work under win32 environments..
setlocale('LC_TIME', 'de_DE.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('CURRENCY_BEFORE', 'DM ');
define('CURRENCY_AFTER', '');
define('CURRENCY_DECIMAL', ',');
define('CURRENCY_THOUSANDS', '.');
define('CURRENCY_VALUE', 1.9558);

// page title
define('TITLE', 'The Exchange Project');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Konto Erstellen');
define('HEADER_TITLE_MY_ACCOUNT', 'Mein Konto');
define('HEADER_TITLE_CART_CONTENTS', 'Einkaufswagen Inhalt');
define('HEADER_TITLE_CHECKOUT', 'Kasse');
define('HEADER_TITLE_TOP', 'Top');
define('HEADER_TITLE_CATALOG', 'Katalog');
define('HEADER_TITLE_LOGOFF' , 'Abmelden');
define('HEADER_TITLE_LOGIN' , 'Anmelden');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'hits seit');

// text for gender
define('MALE', 'Mann');
define('FEMALE', 'Frau');
define('MALE_ADDRESS', 'Herr');
define('FEMALE_ADDRESS', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt/mm/jjjj');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Kategorien');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Neuigkeiten');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Schnellsuche');
define('BOX_SEARCH_TEXT', 'Verwenden Sie keyworter, um Ihr Produkt zu finden.');

// add_a_quickie box text in includes/boxes/add_a_quickie.php
define('BOX_HEADING_ADD_PRODUCT_ID', 'Schnelleinkauf!');
define('BOX_ADD_PRODUCT_ID_TEXT', 'Eingabe der gew&uuml;nschten Produkt-Nr.');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Angebote');
define('BOX_SPECIALS_MORE', 'Mehr Angebote..');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Meinungen');
define('BOX_REVIEWS_MORE', 'Mehr Meinungen..');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Einkaufswagen');
define('BOX_SHOPPING_CART_EMPTY', '..nix drin!');
define('BOX_SHOPPING_CART_SUBTOTAL', 'Zwischensumme:');
define('BOX_SHOPPING_CART_VIEW_CONTENTS', 'Inhalt sehen');

// checkout procedure text
define('CHECKOUT_BAR_CART_CONTENTS', 'einkaufswagen inhalt');
define('CHECKOUT_BAR_DELIVERY_ADDRESS', 'zustelleradresse');
define('CHECKOUT_BAR_PAYMENT_METHOD', 'zahlungsart');
define('CHECKOUT_BAR_CONFIRMATION', 'best&auml;tigung');
define('CHECKOUT_BAR_FINISHED', 'fertig');

// javascript messages
define('JS_ERROR', 'Gefragte Daten fehlen!\nBitte richtig ausfüllen.\n\n');

define('JS_CC_OWNER', '* Der \'Name des Eigentümers\' muß mindestens aus ' . CC_OWNER_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_CC_NUMBER', '* Die \'Kredit Karten Nr.\' muß mindestens aus ' . CC_NUMBER_MIN_LENGTH . ' Zahlen bestehen.\n');
define('JS_CC_EXPIRES', '* Das Gültigkeitsdatum muß mindestens aus ' . CC_EXPIRY_MIN_LENGTH . ' Zahlen bestehen.\n');

define('JS_REVIEW_TEXT', '* Der Text muß mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_REVIEW_RATING', '* Geben Sie ihre Bewertung.\n');

define('JS_GENDER', '* Anredeform festlegen.\n');
define('JS_FIRST_NAME', '* Der \'Vornname\' muß mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_LAST_NAME', '* Der \'Nachname\' muß mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_DOB', '* Die \'Geburtsdaten\' mit xx/xx/xxxx (datum/monat/jahr) eingeben.\n');
define('JS_EMAIL_ADDRESS', '* Die Eingabe der \'E-Mail Adresse\' muß mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_ADDRESS', '* Die \'Strasse/Nr.\' muß mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_POST_CODE', '* Die \'Postleitzahl\' muß mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_CITY', '* Der \'Stadtname\' muß mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_TELEPHONE', '* Die \'Telefonnummer\' muß mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen enthalten.\n');
define('JS_PASSWORD', '* Das \'Passwort\' und die \'Bestätigung\' muß übereinstimmen und mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben enthalten.\n');

define('CATEGORY_PERSONAL', '<b>[ Pers&ouml;nlich ]</b>');
define('CATEGORY_ADDRESS', '<b>[ Adresse ]</b>');
define('CATEGORY_CONTACT', '<b>[ Kontakt ]</b>');
define('CATEGORY_PASSWORD', '<b>[ Passwort ]</b>');
define('ENTRY_GENDER', 'Geschlecht:');
define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_FIRST_NAME', 'Vorname:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_LAST_NAME', 'Nachname:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<small><font color="#FF0000">(z.B. 21/05/1970)</font></small>');
define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(z.B. 21/05/1970) <font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Adresse:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<small><font color="#FF0000">Diese E-Mail Adresse existiert schon!</font></small>');
define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_STREET_ADDRESS', 'Strasse/Nr.:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_SUBURB', 'Wohnort:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postleitzahl:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_CITY', 'Stadt:');
define('ENTRY_CITY_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_STATE_ERROR', '');
define('ENTRY_STATE_TEXT', '');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen</font></small>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_PASSWORD', 'Passwort:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Best&auml;tigung:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('ENTRY_PASSWORD_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#AABBDD">ben&ouml;tigt</font></small>');
define('PASSWORD_HIDDEN', '--VERSTECKT--');
?>
