<?php
/*
  $Id: object_info.php,v 1.3 2002/01/29 18:00:28 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  class objectInfo {

// class constructor
    function objectInfo($object_array) {
      while (list($key, $value) = each($object_array)) {
        if (is_array($value)) {
          $this->$key = $value;
        } elseif (is_int($value)) {
          $this->$key = $value;
        } else {
          $this->$key = tep_db_prepare_input($value);
        }
      }
    }
  }
?>