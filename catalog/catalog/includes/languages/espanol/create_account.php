<?php
/*
  $Id: create_account.php,v 1.3 2001/05/26 16:47:27 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Crear una Cuenta');
define('TOP_BAR_TITLE', 'Crear una Cuenta');
define('HEADING_TITLE', 'Informacion de Mi Cuenta');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTA:</b></font></small> Si ya tiene una cuenta con nosotros, por favor use esta pagina para <a href="' . tep_href_link(FILENAME_LOGIN, 'origin=checkout_address&connection=' . $HTTP_GET_VARS['connection'], 'NONSSL') . '"><u>Entrar</u></a>.');
define('PLEASE_SELECT', 'Seleccione');
?>