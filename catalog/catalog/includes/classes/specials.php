<?php
/*
  $Id: specials.php,v 1.1 2004/10/31 09:46:15 mevans Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Specials {

// class constructor
    function osC_Specials() {

      $this->expireSpecials();
      $this->activateSpecials();
    }

    function expireSpecials() {
      global $osC_Database, $osC_Session;

      $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 1 and now() >= expires_date and expires_date > 0');
      $Qspecials->bindRaw(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->setStatus($Qspecials->valueInt('specials_id'), '0');
      }
    
      $Qspecials->freeResult();
    }

    function activateSpecials() {
      global $osC_Database, $osC_Session;

      $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 0 and now() >= start_date and start_date > 0 and now() < expires_date');
      $Qspecials->bindRaw(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->setStatus($Qspecials->valueInt('specials_id'), '1');
      }
    
      $Qspecials->freeResult();
    }

    function setStatus($specials_id, $status) {
      global $osC_Database;

      $QspecialsStatus = $osC_Database->query('update :table_specials set status = :status, date_status_change = now() where specials_id = :specials_id');
      $QspecialsStatus->bindRaw(':table_specials', TABLE_SPECIALS);
      $QspecialsStatus->bindInt(':status', $status);
      $QspecialsStatus->bindInt(':specials_id', $specials_id);
      $QspecialsStatus->execute();
    }

    function getPrice($product_id) {
      global $osC_Database;

      $Qspecial = $osC_Database->query('select specials_new_products_price from :table_specials where products_id = :products_id and status = 1');
      $Qspecial->bindRaw(':table_specials', TABLE_SPECIALS);
      $Qspecial->bindInt(':products_id', $product_id);
      $Qspecial->execute();

      if ($Qspecial->numberOfRows()) {
        $special_price = $Qspecial->valueDecimal('specials_new_products_price');
      } else {
        $special_price = false;
      }

      $Qspecial->freeResult();

      return $special_price;
    }
  }
?>