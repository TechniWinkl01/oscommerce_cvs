<?php
/*
  $Id: newsletters.php,v 1.1 2005/03/29 23:38:17 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  class osC_Account_Newsletters {

/* Public variables */

    var $page_contents = 'account_newsletters.php';

/* Private variables */

    var $_module = 'newsletters';

/* Class constructor */

    function osC_Account_Newsletters() {
      global $osC_Services, $breadcrumb, $osC_Database, $osC_Customer, $Qnewsletter;

      if ($osC_Services->isStarted('breadcrumb')) {
        $breadcrumb->add(NAVBAR_TITLE_NEWSLETTERS, tep_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
      }

/////////////////////// HPDL /////// Should be moved to the customers class!
      $Qnewsletter = $osC_Database->query('select customers_newsletter from :table_customers where customers_id = :customers_id');
      $Qnewsletter->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qnewsletter->bindInt(':customers_id', $osC_Customer->id);
      $Qnewsletter->execute();

      if ($_GET[$this->_module] == 'save') {
        $this->_process();
      }
    }

/* Public methods */

    function getPageContentsFile() {
      return $this->page_contents;
    }

/* Private methods */

    function _process() {
      global $messageStack, $osC_Database, $osC_Customer, $Qnewsletter;

      if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
        $newsletter_general = $_POST['newsletter_general'];
      } else {
        $newsletter_general = '0';
      }

      if ($newsletter_general != $Qnewsletter->valueInt('customers_newsletter')) {
        $newsletter_general = (($Qnewsletter->value('customers_newsletter') == '1') ? '0' : '1');

        $Qupdate = $osC_Database->query('update :table_customers set customers_newsletter = :customers_newsletter where customers_id = :customers_id');
        $Qupdate->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qupdate->bindInt(':customers_newsletter', $newsletter_general);
        $Qupdate->bindInt(':customers_id', $osC_Customer->id);
        $Qupdate->execute();

        if ($Qupdate->affectedRows() === 1) {
          $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');
        }
      }

      tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  }
?>
