<?php
/*
  $Id: specials.php,v 1.2 2004/11/28 20:49:26 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Specials {

/* Private variables */

    var $_specials = array();

/* Class constructor */

    function osC_Specials() {
    }

/* Public methods */

    function activateAll() {
      global $osC_Database;

      $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 0 and now() >= start_date and start_date > 0 and now() < expires_date');
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->_setStatus($Qspecials->valueInt('specials_id'), true);
      }

      $Qspecials->freeResult();
    }

    function expireAll() {
      global $osC_Database;

      $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 1 and now() >= expires_date and expires_date > 0');
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->_setStatus($Qspecials->valueInt('specials_id'), false);
      }

      $Qspecials->freeResult();
    }

    function isActive($id) {
      global $osC_Database;

      if (isset($this->_specials[$id]) === false) {
        $Qcheck = $osC_Database->query('select products_id from :table_specials where products_id = :products_id and status = 1');
        $Qcheck->bindTable(':table_specials', TABLE_SPECIALS);
        $Qcheck->bindInt(':products_id', $id);
        $Qcheck->execute();

        if ($Qcheck->numberOfRows() > 0) {
          $this->_specials[$id] = true;
        } else {
          $this->_specials[$id] = false;
        }

        $Qcheck->freeResult();
      }

      if ($this->_specials[$id] !== false) {
        return true;
      }

      return false;
    }

    function getPrice($id) {
      global $osC_Database;

      if ( (isset($this->_specials[$id]) === false) || (isset($this->_specials[$id]) && is_bool($this->_specials[$id])) ) {
        $Qspecial = $osC_Database->query('select specials_new_products_price from :table_specials where products_id = :products_id and status = 1');
        $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
        $Qspecial->bindInt(':products_id', $id);
        $Qspecial->execute();

        if ($Qspecial->numberOfRows() > 0) {
          $this->_specials[$id] = $Qspecial->valueDecimal('specials_new_products_price');
        }

        $Qspecial->freeResult();
      }

      return $this->_specials[$id];
    }

/* Private methods */

    function _setStatus($id, $status) {
      global $osC_Database;

      $Qstatus = $osC_Database->query('update :table_specials set status = :status, date_status_change = now() where specials_id = :specials_id');
      $Qstatus->bindTable(':table_specials', TABLE_SPECIALS);
      $Qstatus->bindInt(':status', ($status === true) ? '1' : '0');
      $Qstatus->bindInt(':specials_id', $id);
      $Qstatus->execute();

      $Qstatus->freeResult();
    }
  }
?>