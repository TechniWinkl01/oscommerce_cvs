<?
/*
Spanish Text for The Exchange Project Preview Release 2.2
Last Update: 04/17/2001
Author(s): Marcel Bossert-Schwab (webmaster@wernich.de)
*/

if ($HTTP_GET_VARS['action'] == 'conditions') {
  define('NAVBAR_TITLE', 'Condiciones de Uso');
  define('TOP_BAR_TITLE', 'Condiciones de Uso');
  define('HEADING_TITLE', 'Condiciones de Uso');

  define('TEXT_INFORMATION', 'Ponga aqui sus condiciones de uso.');
}
if ($HTTP_GET_VARS['action'] == 'privacy') {
  define('NAVBAR_TITLE', 'Confidencialidad');
  define('TOP_BAR_TITLE', 'Confidencialidad');
  define('HEADING_TITLE', 'Confidencialidad');

  define('TEXT_INFORMATION', 'Ponga aqui informacion sobre el tratamiento de los datos.');
}

if ($HTTP_GET_VARS['action'] == 'shipping') {
  define('NAVBAR_TITLE', 'Envios y Devoluciones');
  define('TOP_BAR_TITLE', 'Envios y Devoluciones');
  define('HEADING_TITLE', 'Envios y Devoluciones');

  define('TEXT_INFORMATION', 'Ponga aqui informacion sobre los Envios y Devoluciones');
}

define('IMAGE_MAIN_MENU', 'Pagina Principal');
?>
