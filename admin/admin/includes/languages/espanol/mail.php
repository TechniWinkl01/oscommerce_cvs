<?
define('TOP_BAR_TITLE', 'Mail');
if ($HTTP_GET_VARS['action'] == 'sendNewsletter') {
  define('HEADING_TITLE', 'Enviar Boletin');
  define('SUB_BAR_TITLE', 'Enviar Boletin con los ultimos productos');
} elseif ($HTTP_GET_VARS['action'] == 'email_user') {
  define('HEADING_TITLE', 'Enviar eMail a cliente(s)');
  define('SUB_BAR_TITLE', 'Seleccione un cliente, clientes suscritos o todos los clientes para enviarles un eMail.');
} elseif ($HTTP_GET_VARS['action'] == 'send_email_to_user') {
  define('HEADING_TITLE', 'eMail enviado');
  define('SUB_BAR_TITLE', 'El eMail ha sido enviado!');
} else {
  define('HEADING_TITLE', 'eMail');
  define('SUB_BAR_TITLE', 'Funciones de eMail');
}

define('TEXT_CUSTOMER_NAME', 'Nombre del Cliente:');
define('TEXT_SUBJECT', 'Asunto:');
define('TEXT_MESSAGE', 'Mensaje:');
define('TEXT_SELECTCUSTOMER', 'Seleccionar Cliente');
define('TEXT_ALLCUSTOMERS', 'Todos los Clientes');
define('TEXT_NEWSLETTERCUSTOMERS', 'Todos los Suscritos');
define('TEXT_MSGTO', 'Mensaje para');
define('TEXT_EMAILSSENDED', 'eMail(s) enviados');
define('TEXT_EMAILFROM', 'info@theexchangeproject.org'); // Your default sendername
define('TEXT_NO_EMAILS_TO_SEND', 'No hay eMails que enviar.');
define('TEXT_EMAIL_FROM', 'Desde eMail:');
define('TEXT_SEND_EMAIL', 'A eMail');
define('TEXT_NO_CUSTOMER_SELECTED', 'No hay cliente(s) seleccionado(s) para enviar eMail(s)!');

define('MAIL_FOOTER','================================================================================\nSi no desea recibir mas este boletin vaya a ' . HTTP_SERVER . DIR_WS_CATALOG . 'account_edit.php.\n================================================================================\n');

define('HEADING_TITLE_SENDMAIL', 'Enviar eMail');
?>