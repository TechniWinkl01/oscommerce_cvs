<?php
/*
  $Id: object_info.php,v 1.1 2001/12/23 22:15:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  class objectInfo {

// class constructor
    function objectInfo($object_array) {
      while (list($key, $value) = each($object_array)) {
        $this->$key = $value;
      }
    }
  }
?>