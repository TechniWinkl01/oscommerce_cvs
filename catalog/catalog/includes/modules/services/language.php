<?php
/*
  $Id: language.php,v 1.1 2004/04/13 08:02:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_language {
    var $title = 'Language',
        $description = 'Include the default or selected language files.',
        $uninstallable = false,
        $depends = 'session',
        $preceeds;

    function start() {
      if (PHP_VERSION < 4.1) {
        global $_GET;
      }

      global $osC_Session, $lng;

      if (($osC_Session->exists('language') == false) || isset($_GET['language'])) {
        include('includes/classes/language.php');
        $lng = new language;

        if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
          $lng->set_language($_GET['language']);
        } else {
          $lng->get_browser_language();
        }

        $osC_Session->set('language', $lng->language['directory']);
        $osC_Session->set('languages_id', $lng->language['id']);
      }

      require('includes/languages/' . $osC_Session->value('language') . '.php');

      header("Content-Type: text/html; charset=" . CHARSET);

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
