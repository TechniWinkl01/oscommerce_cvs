<?php
/*
  $Id: whos_online.php,v 1.2 2004/11/28 18:39:10 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_whos_online {
    var $title = 'Who\'s Online',
        $description = 'See who is currently online.',
        $uninstallable = true,
        $depends = 'session',
        $preceeds;

    function start() {
      if (PHP_VERSION < 4.1) {
        global $_SERVER;
      }

      global $osC_Session, $osC_Customer, $osC_Database;

      if ($osC_Customer->isLoggedOn()) {
        $wo_customer_id = $osC_Customer->id;
        $wo_full_name = $osC_Customer->full_name;
      } else {
        $wo_customer_id = '';
        $wo_full_name = 'Guest';

        if (SERVICE_WHOS_ONLINE_SPIDER_DETECTION == 'True') {
          $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

          if (tep_not_null($user_agent)) {
            $spiders = file('includes/spiders.txt');

            foreach ($spiders as $spider) {
              if (tep_not_null($spider)) {
                if ((strpos($user_agent, trim($spider))) !== false) {
                  $wo_full_name = $spider;
                  break;
                }
              }
            }
          }
        }
      }

      $wo_session_id = $osC_Session->id;
      $wo_ip_address = tep_get_ip_address();
      $wo_last_page_url = $_SERVER['REQUEST_URI'];

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

      return true;
    }

    function stop() {
      return true;
    }

    function install() {
      global $osC_Database;

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Detect Search Engine Spider Robots', 'SERVICE_WHOS_ONLINE_SPIDER_DETECTION', 'True', 'Detect search engine spider robots (GoogleBot, Yahoo, etc).', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    function remove() {
      global $osC_Database;

      $osC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_WHOS_ONLINE_SPIDER_DETECTION');
    }
  }
?>
