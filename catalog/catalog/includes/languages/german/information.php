<?
/*
German Text for The Exchange Project Preview Release 2.2
Last Update: 04/17/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

if ($HTTP_GET_VARS['action'] == 'conditions') {
  define('NAVBAR_TITLE', 'Allgemeine Gesch�ftsbedingungen');
  define('TOP_BAR_TITLE', 'Allgemeine Gesch�ftsbedingungen');
  define('HEADING_TITLE', 'Allgemeine Gesch�ftsbedingungen');

  define('TEXT_INFORMATION', 'F�gen Sie hier Ihre allgemeinen Gesch�ftsbedingungen ein.');
}
if ($HTTP_GET_VARS['action'] == 'privacy') {
  define('NAVBAR_TITLE', 'Privatsph�re und Datenschutz');
  define('TOP_BAR_TITLE', 'Privatsph�re und Datenschutz');
  define('HEADING_TITLE', 'Privatsph�re und Datenschutz');

  define('TEXT_INFORMATION', 'F�gen Sie hier Ihre Informationen �ber Privatsph�re und Datenschutz ein.');
}

if ($HTTP_GET_VARS['action'] == 'shipping') {
  define('NAVBAR_TITLE', 'Liefer- und Versandkosten');
  define('TOP_BAR_TITLE', 'Liefer- und Versandkosten');
  define('HEADING_TITLE', 'Liefer- und Versandkosten');

  define('TEXT_INFORMATION', 'F�gen Sie hier Ihre Informationen �ber Liefer- und Versandkosten ein.');
}

define('IMAGE_MAIN_MENU', 'Startseite');
?>