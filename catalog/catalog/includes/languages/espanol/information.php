<?
/*
Spanish Text for The Exchange Project Preview Release 2.2
Last Update: 04/17/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

if ($HTTP_GET_VARS['action'] == 'conditions') {
  define('NAVBAR_TITLE', 'Conditions of Use');
  define('TOP_BAR_TITLE', 'Conditions of Use');
  define('HEADING_TITLE', 'Conditions of Use');

  define('TEXT_INFORMATION', 'Put here your Conditions of Use informations.');
}
if ($HTTP_GET_VARS['action'] == 'privacy') {
  define('NAVBAR_TITLE', 'Privacy Notice');
  define('TOP_BAR_TITLE', 'Privacy Notice');
  define('HEADING_TITLE', 'Privacy Notice');

  define('TEXT_INFORMATION', 'Put here your Privacy Notice informations.');
}

if ($HTTP_GET_VARS['action'] == 'shipping') {
  define('NAVBAR_TITLE', 'Shipping & Returns');
  define('TOP_BAR_TITLE', 'Shipping & Returns');
  define('HEADING_TITLE', 'Shipping & Returns');

  define('TEXT_INFORMATION', 'Put here your Shipping & Returns informations.');
}

define('IMAGE_MAIN_MENU', 'Main Menu');
?>