<?php
/*
  $Id: specials.php,v 1.1 2004/04/13 08:02:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_specials {
    var $title = 'Specials',
        $description = 'Prepare the products that are on special.',
        $uninstallable = true,
        $depends,
        $preceeds;

    function start() {
      include('includes/functions/specials.php');

      tep_expire_specials();

      return true;
    }

    function stop() {
      return true;
    }

    function install() {
      return false;
    }

    function remove() {
      return false;
    }

    function keys() {
      return false;
    }
  }
?>
