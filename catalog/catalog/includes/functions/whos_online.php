<?php
/*
  $Id: whos_online.php,v 1.13 2004/02/16 07:24:07 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  function tep_update_whos_online() {
    global $osC_Session, $osC_Customer, $osC_Database;

    if ($osC_Customer->isLoggedOn()) {
      $wo_customer_id = $osC_Customer->id;
      $wo_full_name = $osC_Customer->full_name;
    } else {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';
    }

    $wo_session_id = $osC_Session->id;
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = getenv('REQUEST_URI');

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

// remove entries that have expired
    $Qwhosonline = $osC_Database->query('delete from :table_whos_online where time_last_click < :time_last_click');
    $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwhosonline->bindValue(':time_last_click', $xx_mins_ago);
    $Qwhosonline->execute();

    $Qwhosonline = $osC_Database->query('select count(*) as count from :table_whos_online where session_id = :session_id');
    $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
    $Qwhosonline->bindValue(':session_id', $wo_session_id);
    $Qwhosonline->execute();

    if ($Qwhosonline->valueInt('count') > 0) {
      $Qwhosonline = $osC_Database->query('update :table_whos_online set customer_id = :customer_id, full_name = :full_name, ip_address = :ip_address, time_last_click = :time_last_click, last_page_url = :last_page_url where session_id = :session_id');
      $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
      $Qwhosonline->bindInt(':customer_id', $wo_customer_id);
      $Qwhosonline->bindValue(':full_name', $wo_full_name);
      $Qwhosonline->bindValue(':ip_address', $wo_ip_address);
      $Qwhosonline->bindValue(':time_last_click', $current_time);
      $Qwhosonline->bindValue(':last_page_url', $wo_last_page_url);
      $Qwhosonline->bindValue(':session_id', $wo_session_id);
      $Qwhosonline->execute();
    } else {
      $Qwhosonline = $osC_Database->query('insert into :table_whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values (:customer_id, :full_name, :session_id, :ip_address, :time_entry, :time_last_click, :last_page_url)');
      $Qwhosonline->bindRaw(':table_whos_online', TABLE_WHOS_ONLINE);
      $Qwhosonline->bindInt(':customer_id', $wo_customer_id);
      $Qwhosonline->bindValue(':full_name', $wo_full_name);
      $Qwhosonline->bindValue(':session_id', $wo_session_id);
      $Qwhosonline->bindValue(':ip_address', $wo_ip_address);
      $Qwhosonline->bindValue(':time_entry', $current_time);
      $Qwhosonline->bindValue(':time_last_click', $current_time);
      $Qwhosonline->bindValue(':last_page_url', $wo_last_page_url);
      $Qwhosonline->execute();
    }

    $Qwhosonline->freeResult();
  }
?>
