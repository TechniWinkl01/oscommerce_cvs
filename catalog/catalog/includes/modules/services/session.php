<?php
/*
  $Id: session.php,v 1.1 2004/04/13 08:02:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Services_session {
    var $title = 'Session',
        $description = 'The session manager for guests, customers, and spider robots.',
        $uninstallable = false,
        $depends,
        $preceeds;

    function start() {
      if (PHP_VERSION < 4.1) {
        global $_COOKIE, $_SERVER;
      }

      global $request_type, $cookie_path, $cookie_domain, $SID, $osC_Session, $cart, $osC_Customer, $navigation, $messageStack;

      if (PHP_VERSION < 4.1) {
        include('includes/classes/session_compatible.php');
      } else {
        include('includes/classes/session.php');
      }
      $osC_Session = new osC_Session;

      if (SERVICE_SESSION_FORCE_COOKIE_USAGE == 'True') {
        tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

        if (isset($_COOKIE['cookie_test'])) {
          $osC_Session->start();
        }
      } elseif (SERVICE_SESSION_BLOCK_SPIDERS == 'True') {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $spider_flag = false;

        if (tep_not_null($user_agent)) {
          $spiders = file('includes/spiders.txt');

          foreach ($spiders as $spider) {
            if (tep_not_null($spider)) {
              if (strpos($user_agent, trim($spider)) !== false) {
                $spider_flag = true;
                break;
              }
            }
          }
        }

        if ($spider_flag == false) {
          $osC_Session->start();
        }
      } else {
        $osC_Session->start();
      }

      $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id
      if ( ($request_type == 'SSL') && (SERVICE_SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($osC_Session->is_started == true) ) {
        if (isset($_SERVER['SSL_SESSION_ID'])) {
          $ssl_session_id = $_SERVER['SSL_SESSION_ID'];

          if ($osC_Session->exists('SESSION_SSL_ID') == false) {
            $osC_Session->set('SESSION_SSL_ID', $ssl_session_id);
          }

          if ($osC_Session->value('SESSION_SSL_ID') != $ssl_session_id) {
            $osC_Session->destroy();

            tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
          }
        }
      }

// verify the browser user agent
      if (SERVICE_SESSION_CHECK_USER_AGENT == 'True') {
        $http_user_agent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');

        if ($osC_Session->exists('SESSION_USER_AGENT') == false) {
          $osC_Session->set('SESSION_USER_AGENT', $http_user_agent);
        } elseif ($osC_Session->value('SESSION_USER_AGENT') != $http_user_agent) {
          $osC_Session->destroy();

          tep_redirect(tep_href_link(FILENAME_LOGIN));
        }
      }

// verify the IP address
      if (SERVICE_SESSION_CHECK_IP_ADDRESS == 'True') {
        $ip_address = tep_get_ip_address();

        if ($osC_Session->exists('SESSION_IP_ADDRESS') == false) {
          $osC_Session->set('SESSION_IP_ADDRESS', $ip_address);
        }

        if ($osC_Session->value('SESSION_IP_ADDRESS') != $ip_address) {
          $osC_Session->destroy();

          tep_redirect(tep_href_link(FILENAME_LOGIN));
        }
      }

// create an instance of the shopping cart
      if ($osC_Session->exists('cart')) {
        $cart =& $osC_Session->value('cart');
      } else {
        $cart = new shoppingCart;
        $osC_Session->set('cart', $cart);
      }

// create an instance of the customer class
      if ($osC_Session->exists('osC_Customer')) {
        $osC_Customer =& $osC_Session->value('osC_Customer');
      } else {
        $osC_Customer = new osC_Customer;
        $osC_Session->set('osC_Customer', $osC_Customer);
      }

// navigation history
      if ($osC_Session->exists('navigation')) {
        $navigation =& $osC_Session->value('navigation');
      } else {
        $navigation = new navigationHistory;
        $osC_Session->set('navigation', $navigation);
      }
      $navigation->add_current_page();

// add messages in the session to the message stack
      $messageStack->loadFromSession();

      return true;
    }

    function stop() {
      global $osC_Session;

      $osC_Session->close();

      return true;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Force Cookie Usage', 'SERVICE_SESSION_FORCE_COOKIE_USAGE', 'False', 'Only start a session when cookies are enabled.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Block Search Engine Spiders', 'SERVICE_SESSION_BLOCK_SPIDERS', 'False', 'Block search engine spider robots from starting a session.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check SSL Session ID', 'SERVICE_SESSION_CHECK_SSL_SESSION_ID', 'False', 'Check the SSL_SESSION_ID on every secure HTTPS page request.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check User Agent', 'SERVICE_SESSION_CHECK_USER_AGENT', 'False', 'Check the browser user agent on every page request.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check IP Address', 'SERVICE_SESSION_CHECK_IP_ADDRESS', 'False', 'Check the IP address on every page request.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Regenerate Session ID', 'SERVICE_SESSION_REGENERATE_ID', 'False', 'Regenerate the session ID when a customer logs on or creates an account (requires PHP >= 4.1).', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_SESSION_FORCE_COOKIE_USAGE', 'SERVICE_SESSION_BLOCK_SPIDERS', 'SERVICE_SESSION_CHECK_SSL_SESSION_ID', 'SERVICE_SESSION_CHECK_USER_AGENT', 'SERVICE_SESSION_CHECK_IP_ADDRESS', 'SERVICE_SESSION_REGENERATE_ID');
    }
  }
?>
