<?
/*
German Text for The Exchange Project Preview Release 2.0
Last Update: 01/12/2000
Author(s): Mathias Kowalkowski (mathias@zoomed.de)
*/

define('EMAIL_TEXT_SUBJECT', 'Bestellung');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_PRODUCTS', 'Artikel');
define('EMAIL_TEXT_SUBTOTAL', 'Zwischensumme:');
define('EMAIL_TEXT_TAX', 'MwSt.');
define('EMAIL_TEXT_SHIPPING', 'Versandkosten:');
define('EMAIL_TEXT_TOTAL', 'Summe:        ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Lieferanschrift');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Zahlweise');
define('EMAIL_TEXT_CASH_ON_DELIVERY', 'Nachnahme');
define('EMAIL_TEXT_CREDIT_CARD', 'Kreditkarte');
define('EMAIL_TEXT_PAYPAL', 'PayPal');

$email_order = STORE_NAME . "\n" . '------------------------------------------------------' . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_INVOICE_URL . " " . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'NONSSL') . "\n";
$email_order .= EMAIL_TEXT_DATE_ORDERED . ' ' . $date_formatted . "\n\n" . EMAIL_TEXT_PRODUCTS . "\n" . '------------------------------------------------------' . "\n" . $products_ordered . '------------------------------------------------------' . "\n" . EMAIL_TEXT_SUBTOTAL . ' ' . tep_currency_format($cart->show_total()) . "\n" . EMAIL_TEXT_TAX . tep_currency_format($total_tax) . "\n";
if (!SHIPPING_FREE) {
  $email_order .= EMAIL_TEXT_SHIPPING . ' ' . tep_currency_format($shipping_cost) . "     via " . $shipping_method . "\n";
}
$email_order .= EMAIL_TEXT_TOTAL . ' ' . tep_currency_format($cart->show_total() + $total_tax + $shipping_cost) . "\n\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . '------------------------------------------------------' . "\n";
$email_order .= tep_address_label($customer_id, $sendto, 0, '', "\n");
$email_order.="\n\n" . EMAIL_TEXT_PAYMENT_METHOD . "\n" . '------------------------------------------------------' . "\n";

switch($payment) {
  case 'cod' : // Cash On Delivery
    $email_order .= EMAIL_TEXT_CASH_ON_DELIVERY . "\n\n";
    break;
  case 'cc' : // Credit Card
    $email_order .= EMAIL_TEXT_CREDIT_CARD . ' ' . $cc_type . "\n\n";
    break;
  case 'paypal' : // PayPal
    $email_order .= EMAIL_TEXT_PAYPAL . "\n\n";
    break;
}

define('EMAIL_ORDER', $email_order);
?>
