<?
define('TOP_BAR_TITLE', 'Mail');
if ($HTTP_GET_VARS['action'] == 'sendNewsletter') {
  define('HEADING_TITLE', 'Send newsletter');
  define('SUB_BAR_TITLE', 'Send newsletter with your latest products');
} elseif ($HTTP_GET_VARS['action'] == 'email_user') {
  define('HEADING_TITLE', 'Send eMail to costomer(s)');
  define('SUB_BAR_TITLE', 'Select a customer, newsletter subscribers or all customers to send a eMail.');
} elseif ($HTTP_GET_VARS['action'] == 'send_email_to_user') {
  define('HEADING_TITLE', 'eMail was sent');
  define('SUB_BAR_TITLE', 'Well, your eMail was sent!');
} else {
  define('HEADING_TITLE', 'Mail');
  define('SUB_BAR_TITLE', 'eMail functions');
}

define('TEXT_CUSTOMER_NAME', 'Customer name:');
define('TEXT_SUBJECT', 'Subject:');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_SELECTCUSTOMER', 'Select customer');
define('TEXT_ALLCUSTOMERS', 'All customers');
define('TEXT_NEWSLETTERCUSTOMERS', 'To all newsletter subscribers');
define('TEXT_MSGTO', 'Message to');
define('TEXT_EMAILSSENDED', 'eMail(s) sent');
define('TEXT_EMAILFROM', 'info@theexchangeproject.org'); // Your default sendername
define('TEXT_NO_EMAILS_TO_SEND', 'No eMails to sent.');
define('TEXT_EMAIL_FROM', 'From eMail:');
define('TEXT_SEND_EMAIL', 'send eMail');
define('TEXT_NO_CUSTOMER_SELECTED', 'No customer(s) selected to send mails!');

define('MAIL_FOOTER','================================================================================\nWe are very sadly, if you doesn\'t want more newsletter us,\nbut you can unsubscribe it at ' . HTTP_SERVER . DIR_WS_CATALOG . 'account_edit.php.\n================================================================================\n');

define('HEADING_TITLE_SENDMAIL', 'send eMail');
?>