<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action'] == 'backup') {
    // Force download
    Header("Content-disposition: filename=backup.sql");
    Header("Content-type: application/octetstream");
    // Loop trough tables
    $tables = tep_db_query('show tables');
    while ($table = tep_db_fetch_array($tables)) {
      $table = $table[0];
      // Schema for creating the table
      if ($HTTP_GET_VARS['drop'] == 'yes')
        $schema = "drop table if exists $table;\n";
      $schema = "create table $table (\n";
      $table_list = '(';
      $fields = tep_db_query("show fields from $table");
      while ($field = tep_db_fetch_array($fields)) {
        $schema .= '  ' . $field['Field'] . ' ' . $field['Type'];
        if ($field['Default']) {
          $schema .= ' default \'' . $field['Default'] . '\'';
        }
        if ($field['Null'] != 'YES') {
          $schema .= ' not null';
        }
        if (isset($field['Extra'])) {
          $schema .= ' ' . $field['Extra'];
        }
        $schema .= ",\n";
        $table_list .= $field['Field'] . ', ';
      }
      $schema = ereg_replace(",\n$", "", $schema);
      $table_list = ereg_replace(", $", "", $table_list) . ')';
      // Add the keys
      $index = array();
      $keys = tep_db_query("show keys from $table");
      while ($key = tep_db_fetch_array($keys)) {
        $kname = $key['Key_name'];
        if(($kname != "PRIMARY") && ($key['Non_unique'] == 0)) {
          $kname = "UNIQUE|$kname";
        }
        if(!isset($index[$kname])) {
          $index[$kname] = array();
        }
        $index[$kname][] = $key['Column_name'];
      }
      while (list($x, $columns) = @each($index)) {
        $schema .= ",\n";
        if($x == "PRIMARY") {
          $schema .= "  PRIMARY KEY (" . implode($columns, ", ") . ")";
        } elseif (substr($x, 0, 6) == "UNIQUE") {
          $schema .= "  UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
        } else {
          $schema .= "  KEY $x (" . implode($columns, ", ") . ")";
        }
      }
      $schema .= "\n);";
      echo "$schema\n";
      // Dump the data
      $rows = tep_db_query("select * from $table");
      while ($row = tep_db_fetch_array($rows)) {
        $schema_insert = "INSERT INTO $table $table_list VALUES (";
        $num_fields = sizeof($row) / 2;
        for ($i = 0; $i < $num_fields; $i ++) {
          if (!isset($row[$i]))
            $schema_insert .= " NULL,";
          elseif($row[$i] != "")
            $schema_insert .= " '".addslashes($row[$i])."',";
          else
            $schema_insert .= " '',";
        }
        $schema_insert = ereg_replace(",$", "", $schema_insert);
        $schema_insert .= ")";
        echo trim($schema_insert) . ";\n";
      }
      echo "\n";
    }

  } else {
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td class="pageHeading" align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><? echo TEXT_MAIN; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
          </tr>
          <tr><form action="<? echo tep_href_link(FILENAME_BACKUP, '', 'NONSSL'); ?>" method="get">
            <td class="main" align="center"><input type="checkbox" name="drop" value="yes">&nbsp;<? echo TEXT_DROP_TABLES; ?></td>
          </tr>
          <tr>
            <td class="main" align="center"><input type="hidden" name="action" value="backup"><? echo tep_image_submit('images/button_backup.gif', IMAGE_BACKUP); ?></td>
          </form></tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?
  }
?>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
