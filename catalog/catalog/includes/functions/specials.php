<?php
/*
  $Id: specials.php,v 1.7 2004/02/16 07:23:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

////
// Sets the status of a special product
  function tep_set_specials_status($specials_id, $status) {
    global $osC_Database;

    $Qspecials = $osC_Database->query('update :table_specials set status = :status, date_status_change = now() where specials_id = :specials_id');
    $Qspecials->bindRaw(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindInt(':status', $status);
    $Qspecials->bindInt(':specials_id', $specials_id);
    $Qspecials->execute();
  }

////
// Auto expire products on special
  function tep_expire_specials() {
    global $osC_Database;

    $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 1 and now() >= expires_date and expires_date > 0');
    $Qspecials->bindRaw(':table_specials', TABLE_SPECIALS);
    $Qspecials->execute();

    while ($Qspecials->next()) {
      tep_set_specials_status($Qspecials->valueInt('specials_id'), '0');
    }

    $Qspecials->freeResult();
  }
?>
