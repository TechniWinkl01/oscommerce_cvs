<?
/*
German Text for The Exchange Project Preview Release 2.2
Last Update: 04/17/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

if ($HTTP_GET_VARS['action'] == 'conditions') {
  define('NAVBAR_TITLE', 'Allgemeine Geschäftsbedingungen');
  define('TOP_BAR_TITLE', 'Allgemeine Geschäftsbedingungen');
  define('HEADING_TITLE', 'Allgemeine Geschäftsbedingungen');

  define('TEXT_INFORMATION', 'Fügen Sie hier Ihre allgemeinen Geschäftsbedingungen ein.');
}
if ($HTTP_GET_VARS['action'] == 'privacy') {
  define('NAVBAR_TITLE', 'Privatsphäre und Datenschutz');
  define('TOP_BAR_TITLE', 'Privatsphäre und Datenschutz');
  define('HEADING_TITLE', 'Privatsphäre und Datenschutz');

  define('TEXT_INFORMATION', 'Fügen Sie hier Ihre Informationen über Privatsphäre und Datenschutz ein.');
}

if ($HTTP_GET_VARS['action'] == 'shipping') {
  define('NAVBAR_TITLE', 'Liefer- und Versandkosten');
  define('TOP_BAR_TITLE', 'Liefer- und Versandkosten');
  define('HEADING_TITLE', 'Liefer- und Versandkosten');

  define('TEXT_INFORMATION', 'Fügen Sie hier Ihre Informationen über Liefer- und Versandkosten ein.');
}

define('IMAGE_MAIN_MENU', 'Startseite');
?>