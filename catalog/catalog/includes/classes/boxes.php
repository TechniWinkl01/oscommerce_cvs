<?
  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
    var $font_style = FONT_STYLE_GENERAL;

// class constructor
    function tableBox($contents) {
      echo '<table border="' . $this->table_border . '" width="' . $this->table_width . '" cellspacing="' . $this->table_cellspacing . '" cellpadding="' . $this->table_cellpadding . '"';
      if ($this->table_parameters != '') echo ' ' . $this->table_parameters;
      echo '>' . "\n";

      for ($i=0; $i<sizeof($contents); $i++) {
        if ($contents[$i]['form']) echo $contents[$i]['form'] . "\n";

        echo '  <tr';
        if ($this->table_row_parameters != '') echo ' ' . $this->table_row_parameters;
        echo '>' . "\n";

        if (is_array($contents[$i][0])) {
          for ($x=0; $x<sizeof($contents[$i]); $x++) {
            echo '    <td';
            if ($contents[$i][$x]['align'] != 'left') echo ' align="' . $contents[$i][$x]['align'] . '"';
            if ($this->table_data_parameters != '') echo ' ' . $this->table_data_parameters;
            if ($contents[$i][$x]['params']) echo ' ' . $contents[$i][$x]['params'];
            echo '>' . $this->font_style . $contents[$i][$x]['text'] . '</font></td>' . "\n";
          }
        } else {
          echo '    <td';
          if ($contents[$i]['align'] != 'left') echo ' align="' . $contents[$i]['align'] . '"';
          if ($this->table_data_parameters != '') echo ' ' . $this->table_data_parameters;
          if ($contents[$i]['params']) echo ' ' . $contents[$i]['params'];
          echo '>' . $this->font_style . $contents[$i]['text'] . '</font></td>' . "\n";
        }

        echo '  </tr>' . "\n";

        if ($contents[$i]['form']) echo '</form>' . "\n";
      }

      echo '</table>' . "\n";
    }

  }

  class infoBox extends tableBox {

// class constructor
    function infoBox($contents) {
      $this->font_style = FONT_STYLE_INFO_BOX_BODY;

      $this->tableBox($contents);
    }

  }

  class infoBoxHeading extends tableBox {

// class constructor
    function infoBoxHeading($contents) {
      $this->table_data_parameters = 'bgcolor="' . BOX_HEADING_BACKGROUND_COLOR . '" class="boxborder" nowrap';
      $this->font_style = FONT_STYLE_INFO_BOX_HEADING;

      $contents[0]['text'] = '&nbsp;' . $contents[0]['text'];

      $this->tableBox($contents);
    }

  }

  class infoBoxBestSellers extends tableBox {

// class constructor
    function infoBoxBestSellers($contents) {
      $this->font_style = FONT_STYLE_INFO_BOX_BODY;
      $this->table_row_parameters = 'onmouseover="this.style.backgroundColor=\'' . BOX_CONTENT_HIGHLIGHT_COLOR . '\';" onmouseout="this.style.backgroundColor=\'' . BOX_CONTENT_BACKGROUND_COLOR . '\';"';

      $this->tableBox($contents);
    }
  }

  class listBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
    var $font_style = FONT_STYLE_LIST_BOX_BODY;

// class constructor
    function listBox($contents) {
      echo '<table border="' . $this->table_border . '" width="' . $this->table_width . '" cellspacing="' . $this->table_cellspacing . '" cellpadding="' . $this->table_cellpadding . '"';
      if ($table_parameters != '') echo ' ' . $this->table_parameters;
      echo '>' . "\n";

      for ($i=0; $i<sizeof($contents); $i++) {
      
        if ($contents[$i]['form']) echo $contents[$i]['form'] . "\n";

        if ($i == 0) // first row is the headings
          $this->font_style = FONT_STYLE_LIST_BOX_HEADING;
        else {
          $this->font_style = FONT_STYLE_LIST_BOX_BODY;
          if (($i / 2) == floor($i / 2)) {
            $this->table_row_parameters = 'bgcolor="' . TABLE_ROW_BACKGROUND_COLOR . '"';
          } else {
            $this->table_row_parameters = 'bgcolor="' . TABLE_ALT_BACKGROUND_COLOR . '"';
          }
        }
        
        echo '  <tr';
        if ($this->table_row_parameters != '') echo ' ' . $this->table_row_parameters;
        if ($contents[$i]['row_params']) echo ' ' . $contents[$i]['row_params'];
        echo '>' . "\n";

        if (is_array($contents[$i][0])) {
          for ($x=0; $x<sizeof($contents[$i]); $x++) {
            if ($contents[$i][$x]['form']) echo $contents[$i][$x]['form'] . "\n";

            echo '    <td';
            if ($contents[$i][$x]['align'] != 'left') echo ' align="' . $contents[$i][$x]['align'] . '"';
            if ($this->table_data_parameters != '') echo ' ' . $this->table_data_parameters;
            if ($contents[$i][$x]['params']) echo ' ' . $contents[$i][$x]['params'];
            echo '>' . $this->font_style . $contents[$i][$x]['text'] . '</font></td>' . "\n";

            if ($contents[$i][$x]['form']) echo '</form>' . "\n";
          }
        } else {
          echo '    <td';
          if ($contents[$i]['align'] != 'left') echo ' align="' . $contents[$i]['align'] . '"';
          if ($this->table_data_parameters != '') echo ' ' . $this->table_data_parameters;
          if ($contents[$i]['params']) echo ' ' . $contents[$i]['params'];
          echo '>' . $this->font_style . $contents[$i]['text'] . '</font></td>' . "\n";
        }

        echo '  </tr>' . "\n";

        if ($i == 0) {
          if (is_array($contents[$i][0])) {
            $colspan = ' colspan="' . sizeof($contents[$i]) . '"';
          } else {
            $colspan = '';
          }
          echo '<tr>' . "\n";
          echo '  <td' . $colspan . '>' . tep_black_line() . '</td>' . "\n";
          echo '</tr>' . "\n";
        }

        if ($contents[$i]['form']) echo '</form>' . "\n";
      }
      echo '</table>' . "\n";
    }
  }
?>
