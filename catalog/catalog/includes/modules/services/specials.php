<?php
/*
  $Id: specials.php,v 1.2 2004/10/31 09:46:19 mevans Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_specials {
    var $title = 'Specials',
        $description = 'Enable Product Specials.',
        $uninstallable = true,
        $depends,
        $preceeds;

    function start() {
    	global $osC_Specials;
      include('includes/classes/specials.php');

      $osC_Specials = new osC_Specials();
      return true;
    }

    function stop() {
      return true;
    }

    function install() {
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 'Maximum number of products on special to display', '6', '0', now())");
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Selection of Products on Special', 'MAX_RANDOM_SELECT_SPECIALS', '10', 'How many records to select from to choose one random product special to display', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MAX_DISPLAY_SPECIAL_PRODUCTS', 'MAX_RANDOM_SELECT_SPECIALS');
    }
  }
?>
