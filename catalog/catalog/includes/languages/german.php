<?php
/*
  $Id: german.php,v 1.43 2001/06/04 14:17:10 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat try 'de_DE'
// on FreeBSD try 'de_DE.ISO_8859-1'
// on Windows try 'de'
setlocale('LC_TIME', 'de_DE.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

// page title
define('TITLE', 'The Exchange Project');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Neuer Zugang');
define('HEADER_TITLE_MY_ACCOUNT', 'Mein Konto');
define('HEADER_TITLE_CART_CONTENTS', 'Warenkorb');
define('HEADER_TITLE_CHECKOUT', 'Kasse');
define('HEADER_TITLE_CONTACT_US', 'Kontakt');
define('HEADER_TITLE_TOP', 'Startseite');
define('HEADER_TITLE_CATALOG', 'Katalog');
define('HEADER_TITLE_LOGOFF', 'Abmelden');
define('HEADER_TITLE_LOGIN', 'Anmelden');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'Besucher seit');

// text for gender
define('MALE', 'm&auml;nnlich');
define('FEMALE', 'weiblich');
define('MALE_ADDRESS', 'Herr');
define('FEMALE_ADDRESS', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt/mm/jjjj');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Kategorien');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Hersteller');
define('BOX_MANUFACTURERS_SELECT_ONE', 'Wählen Sie aus:');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Neue Artikel');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Schnellsuche');
define('BOX_SEARCH_TEXT', 'Verwenden Sie Schl&uuml;sselw&ouml;rter, um ein Produkt zu finden.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'erweiterte Suche');

// add_a_quickie box text in includes/boxes/add_a_quickie.php
define('BOX_HEADING_ADD_PRODUCT_ID', 'Schnelleinkauf');
define('BOX_ADD_PRODUCT_ID_TEXT', 'Eingabe des gew&uuml;nschten Produktcodes.');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Angebote');
define('BOX_SPECIALS_MORE', 'Mehr Angebote..');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Bewertungen');
define('BOX_REVIEWS_MORE', 'Mehr Bewertungen..');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Warenkorb');
define('BOX_SHOPPING_CART_EMPTY', 'ist leer.');
define('BOX_SHOPPING_CART_SUBTOTAL', 'Zwischensumme:');
define('BOX_SHOPPING_CART_VIEW_CONTENTS', 'Inhalt einsehen');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Bestseller');
define('BOX_HEADING_BESTSELLERS_IN', 'Bestseller<br>&nbsp;&nbsp;');

// languages box test in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Sprachen');
define('BOX_LANGUAGES_ENGLISH', 'English');
define('BOX_LANGUAGES_DEUTSCH', 'Deutsch');
define('BOX_LANGUAGES_ESPANOL', 'Spanisch');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'W&auml;hrungen');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Information');
define('BOX_INFORMATION_PRIVACY', 'Privatsph&auml;re<br>&nbsp;und Datenschutz');
define('BOX_INFORMATION_CONDITIONS', 'Unsere AGBs');
define('BOX_INFORMATION_SHIPPING', 'Liefer- und<br>&nbsp;Versandkosten');
define('BOX_INFORMATION_CONTACT', 'Kontakt');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Weiterempfehlen');
define('BOX_TELL_A_FRIEND_TEXT', 'Empfehlen Sie diesen Artikel einfach per EMail weiter.');

// checkout procedure text
define('CHECKOUT_BAR_CART_CONTENTS', 'Warenkorbinhalt');
define('CHECKOUT_BAR_DELIVERY_ADDRESS', 'Lieferanschrift');
define('CHECKOUT_BAR_PAYMENT_METHOD', 'Zahlungsweise');
define('CHECKOUT_BAR_CONFIRMATION', 'Best&auml;tigung');
define('CHECKOUT_BAR_FINISHED', 'fertig');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Bitte w&auml;hlen');
define('PLEASE_SELECT', 'Bitte w&auml;hlen');
define('TYPE_BELOW', 'bitte unten eingeben');

// javascript messages
define('JS_ERROR', 'Notwendige Angaben fehlen!\nBitte richtig ausfüllen.\n\n');

define('JS_REVIEW_TEXT', '* Der Text muß mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_REVIEW_RATING', '* Geben Sie ihre Bewertung ein.\n');

define('JS_GENDER', '* Anredeform festlegen.\n');
define('JS_FIRST_NAME', '* Der \'Vornname\' muß mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_LAST_NAME', '* Der \'Nachname\' muß mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_DOB', '* Die \'Geburtsdaten\' im Format xx/xx/xxxx (Tag/Monat/Jahr) eingeben.\n');
define('JS_EMAIL_ADDRESS', '* Die \'E-Mail Adresse\' muß mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_ADDRESS', '* Die \'Strasse/Nr.\' muß mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_POST_CODE', '* Die \'Postleitzahl\' muß mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_CITY', '* Die \'Stadt\' muß mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_STATE', '* Das \'Bundesland\' muß mindestens aus ' . ENTRY_STATE_LENGTH . ' Buchstaben bestehen.\n');
define('JS_STATE_SELECT', '-- wählen sie oberhalb aus --');
define('JS_ZONE', ' * Das Bundesland muss aus der Liste für dieses Land ausgewählt werden.');
define('JS_COUNTRY', '* Das \'Land\' muss ausgewählt werden.');
define('JS_TELEPHONE', '* Die \'Telefonnummer\' muss mindestens aus ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen bestehen.\n');
define('JS_PASSWORD', '* Das \'Passwort\' und die \'Bestätigung\' müssen übereinstimmen und mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben enthalten.\n');

define('CATEGORY_PERSONAL', '<b>[ Pers&ouml;nliche Daten ]</b>');
define('CATEGORY_ADDRESS', '<b>[ Adresse ]</b>');
define('CATEGORY_CONTACT', '<b>[ Kontakt ]</b>');
define('CATEGORY_PASSWORD', '<b>[ Passwort ]</b>');
define('CATEGORY_OPTIONS', '<b>[ Optionen ]</b>');
define('ENTRY_GENDER', 'Geschlecht:');
define('ENTRY_GENDER_ERROR', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_GENDER_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_FIRST_NAME', 'Vorname:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_FIRST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_LAST_NAME', 'Nachname:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_LAST_NAME_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<small><font color="#FF0000">(z.B. 21/05/1970)</font></small>');
define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(z.B. 21/05/1970) <font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Adresse:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">Ung&uuml;ltige E-Mail Adresse!</font></small>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<small><font color="#FF0000">Diese E-Mail Adresse existiert schon!</font></small>');
define('ENTRY_EMAIL_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_STREET_ADDRESS', 'Strasse/Nr.:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_SUBURB', 'Stadtteil:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postleitzahl:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_POST_CODE_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_CITY', 'Ort:');
define('ENTRY_CITY_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_CITY_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_STATE_ERROR', '');
define('ENTRY_STATE_TEXT', '');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_COUNTRY_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen</font></small>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'abonniert');
define('ENTRY_NEWSLETTER_NO', 'nicht abonniert');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Passwort:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Best&auml;tigung:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('ENTRY_PASSWORD_ERROR', '&nbsp;<small><font color="#FF0000">mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben</font></small>');
define('ENTRY_PASSWORD_TEXT', '&nbsp;<small><font color="#AABBDD">notwendige Eingabe</font></small>');
define('PASSWORD_HIDDEN', '--VERSTECKT--');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seiten:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'angezeigte Artikel: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'angezeigte Bestellungen: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');

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

define('IMAGE_BUTTON_ADD_ADDRESS', 'Neue Adresse');
define('IMAGE_BUTTON_ADD_QUICK', 'Schnelleinkauf');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Adressbuch');
define('IMAGE_BUTTON_BACK', 'Zurück');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Adresse ändern');
define('IMAGE_BUTTON_CHECKOUT', 'Kasse');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Bestellung bestätigen');
define('IMAGE_BUTTON_CONTINUE', 'Weiter');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Daten ändern');
define('IMAGE_BUTTON_HISTORY', 'Bestellübersicht');
define('IMAGE_BUTTON_IN_CART', 'In den Warenkorb');
define('IMAGE_BUTTON_QUICK_FIND', 'Schnellsuche');
define('IMAGE_BUTTON_REVIEWS', 'Bewertungen');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Versandoptionen');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Weiterempfehlen');
define('IMAGE_BUTTON_UPDATE_CART', 'Warenkorb aktualisieren');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Bewertung schreiben');

define('TEXT_GREETING_PERSONAL', 'Sch&ouml;n das Sie wieder da sind <span class="greetUser">%s!</span>');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Wenn Sie nicht %s sind, melden Sie sich bitte <a href="%s"><u>hier</u></a> mit Ihrem Kundenkonto an.</small>');
define('TEXT_GREETING_GUEST', 'Herzlich Willkommen <span class="greetUser">Gast!</span> Möchtne Sie sich <a href="%s"><u>anmelden</u></a>? Oder wollen Sie ein <a href="%s"><u>Kundenkonto</u></a> eröffnen?');

define('TEXT_SORT_PRODUCTS', 'Sortierung der Artikel ist ');
define('TEXT_DESCENDINGLY', 'absteigend');
define('TEXT_ASCENDINGLY', 'aufsteigend');
define('TEXT_BY', ' durch ');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');
?>