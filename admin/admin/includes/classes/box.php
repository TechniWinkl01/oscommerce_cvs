<?php
/*
  $Id: box.php,v 1.8 2004/07/22 23:07:09 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class box extends tableBlock {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function infoBox($heading, $contents, $id = '', $visible = '') {
      $this->table_parameters = 'class="infoBoxHeading"';
      $this->heading = $this->tableBlock($heading);

      $this->table_parameters = 'class="infoBoxContent"';
      $this->contents = $this->tableBlock($contents);

      $infobox = $this->heading . $this->contents;

      if (!empty($id)) {
        $infobox = '<div id="' . $id . '"' . (($visible == 'hidden') ? ' style="display: none;"' : '') . '>' . $infobox . '</div>';
      }

      return $infobox;
    }

    function menuBox($heading, $contents, $toggleBlock = '') {
      global $selected_box;

      $this->table_data_parameters = 'class="menuBoxHeading"';
      if (isset($heading[0]['link'])) {
        $heading[0]['text'] = '&nbsp;<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>&nbsp;';
      } else {
        $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
      }
      $this->heading = $this->tableBlock($heading);

      $this->table_parameters = 'class="menuBoxContent"';
      if (!empty($toggleBlock)) {
        $this->table_parameters .= ' id="' . $toggleBlock . '"';

        if ($toggleBlock != $selected_box) {
          $this->table_parameters .= ' style="display: none;"';
        }
      }
      $this->table_data_parameters = '';
      $this->contents = $this->tableBlock($contents);

      return $this->heading . $this->contents;
    }
  }
?>
