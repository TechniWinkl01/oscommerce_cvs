<?php
/*
  $Id: specials.php,v 1.1 2001/09/09 18:50:18 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
// Sets the status of a special product
  function tep_set_specials_status($specials_id, $status) {
    return tep_db_query("update " . TABLE_SPECIALS . " set status = '" . $status . "', date_status_change = now() where specials_id = '" . $specials_id . "'");
  }

////
// Check and expire a banner
  function tep_expire_specials() {
    $specials_query = tep_db_query("select specials_id from " . TABLE_SPECIALS . " where now() >= expires_date");
    if (tep_db_num_rows($specials_query)) {
      while ($specials = tep_db_fetch_array($specials_query)) {
        tep_set_specials_status($specials['specials_id'], '0');
      }
    }
  }
?>