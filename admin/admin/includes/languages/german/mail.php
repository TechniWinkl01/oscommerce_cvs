<?
define('TOP_BAR_TITLE', 'Mail');
if ($HTTP_GET_VARS['action'] == 'sendNewsletter') {
  define('HEADING_TITLE', 'Newsletter versenden');
  define('SUB_BAR_TITLE', 'Versenden Sie automatisch Newsletter mit Ihren neuesten Produkten');
} elseif ($HTTP_GET_VARS['action'] == 'email_user') {
  define('HEADING_TITLE', 'eMail an Kunden versenden');
  define('SUB_BAR_TITLE', 'Wählen Sie einen, Newsletter-Abonnomenten oder alle Kunden aus.');
} elseif ($HTTP_GET_VARS['action'] == 'send_email_to_user') {
  define('HEADING_TITLE', 'eMail wurde versendet');
  define('SUB_BAR_TITLE', 'Jetzt ist es passiert, die eMail wurde versendet!');
} else {
  define('HEADING_TITLE', 'Mail');
  define('SUB_BAR_TITLE', 'EMailfunktionen');
}

define('TEXT_CUSTOMER_NAME', 'Kundenname:');
define('TEXT_SUBJECT', 'Betreff:');
define('TEXT_MESSAGE', 'Nachricht:');
define('TEXT_SELECTCUSTOMER', 'Benutzer auswählen');
define('TEXT_ALLCUSTOMERS', 'Alle Kunden');
define('TEXT_NEWSLETTERCUSTOMERS', 'An alle Newsletter-Abonnenten');
define('TEXT_MSGTO', 'Nachricht an');
define('TEXT_EMAILSSENDED', 'EMail(s) gesendet');
define('TEXT_EMAILFROM', 'info@theexchangeproject.org'); // Ihr Absendername
define('TEXT_NO_EMAILS_TO_SEND', 'Keine eMails zum senden.');
define('TEXT_EMAIL_FROM', 'Absender eMail:');
define('TEXT_SEND_EMAIL', 'eMail senden');
define('TEXT_NO_CUSTOMER_SELECTED', 'Es wurde kein Kunde zum versand einer eMail ausgewählt!');

define('MAIL_FOOTER','================================================================================\nEs wäre schade, wenn Sie den Newsletter nicht mehr erhalten möchten.\nNatürlich können Sie ihn jederzeit unter ' . HTTP_SERVER . DIR_WS_CATALOG . 'account_edit.php im Kundenbereich abbestellen.\n================================================================================\n');

define('HEADING_TITLE_SENDMAIL', 'eMail senden');
?>