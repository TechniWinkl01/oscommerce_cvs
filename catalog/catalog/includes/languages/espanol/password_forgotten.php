<?php
/*
  $Id: password_forgotten.php,v 1.3 2001/05/26 16:47:37 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Entrar');
define('NAVBAR_TITLE_2', 'Constrase�a Olvidada');
define('TOP_BAR_TITLE', 'Contrase�a Olvidada');
define('HEADING_TITLE', 'He olvidado mi Contrase�a!');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>NOTA:</b></font> Ese E-Mail no figura en nuestros datos, intentelo de nuevo.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Recuperar Contrase�a');
define('EMAIL_PASSWORD_REMINDER_BODY', 'Ha solicitado recuperar su Contrase�a desde ' . $REMOTE_ADDR . '.' . "\n\n" . 'Su contrase�a para \'' . STORE_NAME . '\' es:' . "\n\n" . '   %s' . "\n\n");
?>