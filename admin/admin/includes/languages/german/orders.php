<?php
/*
  $Id: orders.php,v 1.14 2002/01/27 15:07:42 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Bestellungen');
define('HEADING_TITLE_SEARCH', 'Bestell-Nr.:');
define('HEADING_TITLE_STATUS', 'Status:');

define('TABLE_HEADING_COMMENTS', 'Kommentar');
define('TABLE_HEADING_CUSTOMERS', 'Kunde');
define('TABLE_HEADING_ORDER_TOTAL', 'Gesamtwert');
define('TABLE_HEADING_PAYMENT_METHOD', 'Zahlungsweise');
define('TABLE_HEADING_DATE_PURCHASED', 'Bestelldatum');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_QUANTITY', 'Anzahl');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikel-Nr.');
define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_TAX', 'MwSt.');
define('TABLE_HEADING_TOTAL', 'Gesamtsumme');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Preis (exkl.)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Preis (inkl.)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (exkl.)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inkl.)');

define('TABLE_HEADING_NEW_VALUE', 'neuer Status');
define('TABLE_HEADING_OLD_VALUE', 'alter Status');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'hinzugef&uuml;gt am:');

define('ENTRY_CUSTOMER', 'Kunde:');
define('ENTRY_STREET_ADDRESS', 'Strasse:');
define('ENTRY_SUBURB', 'zus. Anschrift:');
define('ENTRY_CITY', 'Stadt:');
define('ENTRY_POST_CODE', 'PLZ:');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_TELEPHONE', 'Telefon:');
define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');
define('ENTRY_DELIVERY_TO', 'Lieferanschrift:');
define('ENTRY_PAYMENT_METHOD', 'Zahlungsweise:');
define('ENTRY_CREDIT_CARD_TYPE', 'Kreditkartentyp:');
define('ENTRY_CREDIT_CARD_OWNER', 'Kreditkarteninhaber:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Kerditkartennnummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Kreditkarte l&auml;uft ab am:');
define('ENTRY_SUB_TOTAL', 'Zwischensumme:');
define('ENTRY_TAX', 'MwSt.:');
define('ENTRY_SHIPPING', 'Versandkosten:');
define('ENTRY_TOTAL', 'Gesamtsumme:');
define('ENTRY_DATE_PURCHASED', 'Bestelldatum:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'letzte Aktualisierung am:');
define('ENTRY_NOTIFY_CUSTOMER', 'Kunde benachrichtigen:');

define('TEXT_ALL_ORDERS', 'Alle Bestellungen');
define('TEXT_NO_ORDER_HISTORY', 'Keine Bestellhistorie verf&uuml;gbar');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Bestellung aktualisiert');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestell-Nr.:');
define('EMAIL_TEXT_INVOICE_URL', 'Ausf&uuml;hrliche Rechnung:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Ihre Bestellung ist aktualisiert worden und weist den folgenden Stauts auf:' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Sollten Sie Fragen haben, so antworten Sie bitte auf diese eMail.' . "\n");

define('SUCCESS_ORDER_UPDATED', 'Hinweis: Die Bestellung wurde erfolgreich aktualisiert.');
?>