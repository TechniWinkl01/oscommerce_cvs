<?php
/*
  $Id: create_account.php,v 1.4 2001/06/03 18:08:57 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Crear una Cuenta');
define('TOP_BAR_TITLE', 'Crear una Cuenta');
define('HEADING_TITLE', 'Informacion de Mi Cuenta');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTA:</b></font></small> Si ya tiene una cuenta con nosotros, por favor use esta pagina para <a href="' . tep_href_link(FILENAME_LOGIN, 'origin=checkout_address&connection=' . $HTTP_GET_VARS['connection'], 'NONSSL') . '"><u>Entrar</u></a>.');
?>