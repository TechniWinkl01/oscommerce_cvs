<?php
/*
  $Id: application.php,v 1.2 2002/06/27 14:58:24 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// Check if register_globals is enabled.
// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');

  define('CR', "\n");

  define('BOX_BGCOLOR_HEADING', '#bbc3d3');
  define('BOX_BGCOLOR_CONTENTS', '#f8f8f9');
  define('BOX_SHADOW', '#b6b7cb');

  require('includes/functions/general.php');
  require('includes/functions/database.php');
  require('includes/functions/output.php');
  require('includes/functions/html_output.php');
?>