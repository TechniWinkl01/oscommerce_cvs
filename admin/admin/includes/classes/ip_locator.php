<?php
/*
  $Id: ip_locator.php,v 1.1 2004/08/15 18:06:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_IP_Locator {

/* Private methods */

    var $_module = false;

/* Class constructor */

    function osC_IP_Locator() {
      if (defined('MODULE_IP_LOCATOR')) {
        $this->_loadModule(MODULE_IP_LOCATOR);
      }
    }

/* Public methods */

    function isLoaded() {
      if ($this->_module !== false) {
        return true;
      }

      return false;
    }

    function getData($ip_address) {
      if ($this->isLoaded()) {
        $module_name = $this->_module;

        $data = $GLOBALS[$module_name]->getData($ip_address);

        if (is_array($data)) {
          return $data;
        }
      }

      return false;
    }

    function unload() {
      if ($this->isLoaded()) {
        $module_name = $this->_module;

        $GLOBALS[$module_name]->unload();

        unset($GLOBALS[$module_name]);

        $this->_module = false;
      }
    }

/* Private methods */

    function _loadModule($module) {
      $module = basename($module);

      if (file_exists('includes/modules/' . $module . '.php')) {
        if (include('includes/modules/' . $module . '.php')) {
          $module_name = 'osC_IP_Locator_' . $module;

          $GLOBALS[$module_name] = new $module_name();

          if ($GLOBALS[$module_name]->isLoaded()) {
            $this->_module = $module_name;
          }
        }
      }
    }
  }
?>
