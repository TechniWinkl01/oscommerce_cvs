<?php
/*
  $Id: boxes.php,v 1.20 2001/12/26 23:45:13 hpdl Exp $

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
    function tableBox($contents) {
      echo '<table border="' . $this->table_border . '" width="' . $this->table_width . '" cellspacing="' . $this->table_cellspacing . '" cellpadding="' . $this->table_cellpadding . '"';
      if ($this->table_parameters != '') echo ' ' . $this->table_parameters;
      echo '>' . "\n";

      $form_set = false;
      if ($contents['form']) {
        echo $contents['form'] . "\n";
        $form_set = true;
        tep_array_shift($contents);
      }

      for ($i=0; $i<sizeof($contents); $i++) {
        echo '  <tr';
        if ($this->table_row_parameters != '') echo ' ' . $this->table_row_parameters;
        if ($contents[$i]['params']) echo ' ' . $contents[$i]['params'];
        echo '>' . "\n";

        if (is_array($contents[$i][0])) {
          for ($x=0; $x<sizeof($contents[$i]); $x++) {
            if ($contents[$i][$x]['text']) { // 'text' must always be explicit (ie, set) .. used in conjunction with alternate row colours
              if ($contents[$i][$x]['form']) echo $contents[$i][$x]['form'] . "\n";
              echo '    <td';
              if ($contents[$i][$x]['align'] != '') echo ' align="' . $contents[$i][$x]['align'] . '"';
              if ($contents[$i][$x]['params']) {
                echo ' ' . $contents[$i][$x]['params'];
              } elseif ($this->table_data_parameters != '') {
                echo ' ' . $this->table_data_parameters;
              }
              echo '>' . $contents[$i][$x]['text'] . '</td>' . "\n";
              if ($contents[$i][$x]['form']) echo '</form>' . "\n";
            }
          }
        } else {
          echo '    <td';
          if ($contents[$i]['align'] != '') echo ' align="' . $contents[$i]['align'] . '"';
          if ($contents[$i]['params']) {
            echo ' ' . $contents[$i]['params'];
          } elseif ($this->table_data_parameters != '') {
            echo ' ' . $this->table_data_parameters;
          }
          echo '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        echo '  </tr>' . "\n";
      }

      if ($form_set) echo '</form>' . "\n";

      echo '</table>' . "\n";
    }

  }

  class infoBox extends tableBox {
    function infoBox($contents) {
      $this->table_data_parameters = 'class="infoBox"';
      $this->tableBox($contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents) {
      $this->table_data_parameters = 'class="infoBoxHeading"';
      if ($contents[0]['link']) {
        $contents[0]['text'] = '&nbsp;<a class="blacklink" href="' . $contents[0]['link'] . '">' . $contents[0]['text'] . '</a>&nbsp;';
      } else {
        $contents[0]['text'] = '&nbsp;' . $contents[0]['text'] . '&nbsp;';
      }
      $this->tableBox($contents);
    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents);
    }
  }
?>
