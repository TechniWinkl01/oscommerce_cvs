<?php
/*
  $Id: boxes.php,v 1.24 2002/01/11 20:52:03 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . $this->table_border . '" width="' . $this->table_width . '" cellspacing="' . $this->table_cellspacing . '" cellpadding="' . $this->table_cellpadding . '"';
      if ($this->table_parameters != '') $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0; $i<sizeof($contents); $i++) {
        if ($contents[$i]['form']) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if ($this->table_row_parameters != '') $tableBox_string .= ' ' . $this->table_row_parameters;
        if ($contents[$i]['params']) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (is_array($contents[$i][0])) {
          for ($x=0; $x<sizeof($contents[$i]); $x++) {
            if ($contents[$i][$x]['text']) {
              $tableBox_string .= '    <td';
              if ($contents[$i][$x]['align'] != 'left') $tableBox_string .= ' align="' . $contents[$i][$x]['align'] . '"';
              if ($contents[$i][$x]['params']) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif ($this->table_data_parameters != '') {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if ($contents[$i][$x]['form']) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if ($contents[$i][$x]['form']) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if ($contents[$i]['align'] != 'left') $tableBox_string .= ' align="' . $contents[$i]['align'] . '"';
          if ($contents[$i]['params']) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif ($this->table_data_parameters != '') {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if ($contents[$i]['form']) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output) echo $tableBox_string;

      return $tableBox_string;
    }
  }

  class infoBox extends tableBox {
    function infoBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '3';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
      $info_box_contents[] = array(array('align' => 'left', 'text' => tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '1', '1')));
      for ($i=0; $i<sizeof($contents); $i++) {
        $info_box_contents[] = array(array('align' => $contents[$i]['align'], 'form' => $contents[$i]['form'], 'params' => 'class="boxText"', 'text' => $contents[$i]['text']));
      }
      $info_box_contents[] = array(array('align' => 'left', 'text' => tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '1', '1')));
      return $this->tableBox($info_box_contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';

      if ($left_corner) {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif') . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '11', '14');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('align' => 'left', 'params' => 'height="14" class="infoBoxHeading"', 'text' => $left_corner),
                                   array('align' => 'left', 'params' => 'width="100%" height="14" class="infoBoxHeading"', 'text' => '<b>' . $contents[0]['text'] . '</b>'),
                                   array('align' => 'left', 'params' => 'height="14" class="infoBoxHeading"', 'text' => $right_corner));
      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBox extends tableBox {
    function contentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = 'class="infoBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      $info_box_contents[] = array(array('align' => 'left', 'params' => 'height="14" class="infoBoxHeading"', 'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif')),
                                   array('align' => 'left', 'params' => 'height="14" class="infoBoxHeading" width="100%"', 'text' => '<b>' . $contents[0]['text'] . '</b>'),
                                   array('align' => 'left', 'params' => 'height="14" class="infoBoxHeading"', 'text' => tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif')));
      $this->tableBox($info_box_contents, true);
    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }
?>
