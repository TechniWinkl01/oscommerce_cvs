<?php
/*
  $Id: orders.php,v 1.1 2005/03/29 23:36:44 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  class osC_Account_Orders {

/* Public variables */

    var $page_contents = 'account_history.php';

/* Private variables */

    var $_module = 'orders';

/* Class constructor */

    function osC_Account_Orders() {
      global $osC_Services, $breadcrumb;

      if ($osC_Services->isStarted('breadcrumb')) {
        $breadcrumb->add(NAVBAR_TITLE_MY_ORDERS, tep_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));

        if (is_numeric($_GET[$this->_module])) {
          $breadcrumb->add(sprintf(NAVBAR_TITLE_ORDER_INFORMATION, $_GET[$this->_module]), tep_href_link(FILENAME_ACCOUNT, $this->_module . '=' . $_GET[$this->_module], 'SSL'));
        }
      }

      if (is_numeric($_GET[$this->_module])) {
        $this->page_contents = 'account_history_info.php';
      }

      if (tep_not_null($_GET[$this->_module])) {
        $this->_process();
      }
    }

/* Public methods */

    function getPageContentsFile() {
      return $this->page_contents;
    }

/* Private methods */

    function _process() {
      global $osC_Database, $osC_Customer;

      if (is_numeric($_GET[$this->_module]) === false) {
        tep_redirect(tep_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }

      $Qcheck = $osC_Database->query('select customers_id from :table_orders where orders_id = :orders_id');
      $Qcheck->bindTable(':table_orders', TABLE_ORDERS);
      $Qcheck->bindInt(':orders_id', $_GET[$this->_module]);
      $Qcheck->execute();

      if ($Qcheck->valueInt('customers_id') != $osC_Customer->id) {
        tep_redirect(tep_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }
    }
  }
?>
