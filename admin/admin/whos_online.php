<?php
/*
  $Id: whos_online.php,v 1.38 2004/08/15 18:13:19 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $selected_box = 'tools';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $page_contents = 'whos_online.php';

  require('templates/default.php');

  require('includes/application_bottom.php');
?>
