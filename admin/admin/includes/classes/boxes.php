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
      if ($table_parameters != '') echo ' ' . $this->table_parameters;
      echo '>' . "\n";

      for ($i=0; $i<sizeof($contents); $i++) {
        echo '  <tr';
        if ($this->table_row_parameters != '') echo ' ' . $this->table_row_paramters;
        echo '>' . "\n";

        echo '    <td';
        if ($contents[$i]['align'] != 'left') echo ' align="' . $contents[$i]['align'] . '"';
        if ($this->table_data_parameters != '') echo ' ' . $this->table_data_parameters;
        if ($contents[$i]['params']) echo ' ' . $contents[$i]['params'];
        echo '>' . $this->font_style . $contents[$i]['text'] . '</font></td>' . "\n";

        echo '  </tr>' . "\n";
      }

      echo '</table>' . "\n";
    }

  }

  class infoBox extends tableBox {

// class constructor
    function infoBox($contents) {
      $this->table_data_parameters = 'bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '"';
      $this->font_style = FONT_STYLE_INFO_BOX_BODY;

      $this->tableBox($contents);
    }

  }

  class infoBoxHeading extends infoBox {

// class constructor
    function infoBoxHeading($contents) {
      $this->table_cellpadding = '0';
      $this->table_data_parameters = 'nowrap';
      $this->font_style = FONT_STYLE_INFO_BOX_HEADING;

      $this->tableBox($contents);
    }

  }

  class navigationBoxHeading extends tableBox {

// class constructor
    function navigationBoxHeading($contents) {
      $this->table_data_parameters = 'bgcolor="' . BOX_HEADING_BACKGROUND_COLOR . '" class="boxborder" nowrap';
      $this->font_style = FONT_STYLE_NAVIGATION_BOX_HEADING;

      $contents[0]['text'] = '&nbsp;' . $contents[0]['text'];

      $this->tableBox($contents);
    }

  }
?>
