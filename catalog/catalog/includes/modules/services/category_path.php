<?php
/*
  $Id: category_path.php,v 1.1 2004/04/13 08:02:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_category_path {
    var $title = 'Category Path',
        $description = 'Parses the category path.',
        $uninstallable = false,
        $depends,
        $preceeds;

    function start() {
      if (PHP_VERSION < 4.1) {
        global $_GET;
      }

      global $cPath, $cPath_array, $current_category_id;

      if (isset($_GET['cPath'])) {
        $cPath = $_GET['cPath'];
      } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
        $cPath = tep_get_product_path($_GET['products_id']);
      } else {
        $cPath = '';
      }

      if (!empty($cPath)) {
        $cPath_array = tep_parse_category_path($cPath);
        $cPath = implode('_', $cPath_array);

        $current_category_id = end($cPath_array);
      } else {
        $current_category_id = 0;
      }

      include('includes/classes/category_tree.php');

      return true;
    }

    function stop() {
      return true;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Number Of Products In Each Category', 'SHOW_COUNTS', 'true', 'Recursively count how many products are in each category.', '6', '0', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SHOW_COUNTS');
    }
  }
?>
