<?php
/*
  $Id: box.php,v 1.1 2002/01/08 17:55:13 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                                 'text'  => BOX_HEADING_TOOLS,
                                 'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));
  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

  class box extends table {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function infobox($heading, $contents) {
      $this->table_data_parameters = 'class="infoBoxHeading"';
      $this->heading = $this->table($heading);

      $this->table_data_parameters = 'class="infoBox"';
      if ($contents[0]['link']) {
        $contents[0]['text'] = '&nbsp;<a href="' . $contents[0]['link'] . '" class="blacklink">' . $contents[0]['text'] . '</a>&nbsp;';
      } else {
        $contents[0]['text'] = '&nbsp;' . $contents[0]['text'] . '&nbsp;';
      }
      $this->contents = $this->table($contents);

      return $this->heading . $this->contents;
    }
  }
?>