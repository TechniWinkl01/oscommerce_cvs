<?php
/*
  $Id: banner.php,v 1.1 2004/04/13 08:02:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_banner {
    var $title = 'Banner',
        $description = 'Banner management features for the catalog.',
        $uninstallable = true,
        $depends,
        $preceeds;

    function start() {
      include('includes/functions/banner.php');

      tep_activate_banners();
      tep_expire_banners();

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
