<?php
/*
  $Id: errorStack.php,v 1.1 2002/01/08 17:55:13 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $errorStack = new errorStack();
  $errorStack->add('Error: Error 1', 'error');
  $errorStack->add('Error: Error 2', 'warning');
  if ($errorStack->size > 0) echo $errorStack->output();
*/

  class errorStack extends table {
    var $size = 0;

    function errorStack() {
      $this->errors = array();
    }

    function add($message, $type = 'error') {
      if ($type == 'error') {
        $this->errors[] = array('text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message);
      } elseif ($type == 'warning') {
        $this->errors[] = array('text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message);
      } else {
        $this->errors[] = array('text' => $message);
      }

      $this->size++;
    }

    function reset() {
      $this->errors = array();
      $this->size = 0;
    }

    function output() {
      $this->table_data_parameters = 'class="errorBox"';
      return $this->table($this->errors);
    }
  }
?>