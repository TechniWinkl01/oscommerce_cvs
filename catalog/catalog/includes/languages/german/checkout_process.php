<?
/*
German Text for The Exchange Project Preview Release 1.1
Last Update: 14/05/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

define('EMAIL_TEXT_SUBJECT', 'Bestellung');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellung Nummer:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestellung Datum:');
define('EMAIL_TEXT_PRODUCTS', 'Produkte');
define('EMAIL_TEXT_SUBTOTAL', 'Zwischensumme:');
define('EMAIL_TEXT_TAX', 'Mwst.');
define('EMAIL_TEXT_SHIPPING', 'Versandkosten:');
define('EMAIL_TEXT_TOTAL', 'Summe:        ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Lieferadresse');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Zahlungsart');
define('EMAIL_TEXT_CASH_ON_DELIVERY', 'Nachnahme');
define('EMAIL_TEXT_CREDIT_CARD', 'Kredit Karte');

$email_order = STORE_NAME . "\n" . '------------------------------------------------------' . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . $date_formatted . "\n\n" . EMAIL_TEXT_PRODUCTS . "\n" . '------------------------------------------------------' . "\n" . $products_ordered . '------------------------------------------------------' . "\n" . EMAIL_TEXT_SUBTOTAL . ' $' . $subtotal . "\n" . EMAIL_TEXT_TAX . ' (' . TAX_VALUE . '%): $' . $tax . "\n";
if (!SHIPPING_FREE) {
  $email_order.=EMAIL_TEXT_SHIPPING . ' $' . number_format($shipping_cost, 2) . "     via " . $shipping_method . "\n";
}
$email_order.=EMAIL_TEXT_TOTAL . ' $' . $total . "\n\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . '------------------------------------------------------' . "\n" . $delivery_name . "\n" . $delivery_values['street_address'] . "\n";
if ($delivery_values['suburb'] != '') {
  $email_order.="\n" . $delivery_values['suburb'];
}
$email_order.="\n" . $delivery_values['city'] . ', ' . $delivery_values['postcode'];
if ($delivery_values['state'] != '') {
  $email_order.="\n" . $delivery_values['state'] . ', ' . $delivery_country;
} else {
  $email_order.="\n" . $delivery_country;
}
$email_order.="\n\n" . EMAIL_TEXT_PAYMENT_METHOD . "\n" . '------------------------------------------------------' . "\n";
if ($HTTP_POST_VARS['payment'] == 'cod') {
  $email_order.=EMAIL_TEXT_CASH_ON_DELIVERY . "\n\n";
} else {
  $email_order.=EMAIL_TEXT_CREDIT_CARD . ' ' . $HTTP_POST_VARS['cc_type'] . "\n\n";
}

define('EMAIL_ORDER', $email_order);
?>
