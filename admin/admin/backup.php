<?php
/*
  $Id: backup.php,v 1.9 2001/09/19 09:11:33 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action'] == 'backup') {
    // increase timeout to 3 minutes
    tep_set_time_limit(180);
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
      $table_list = array();
      $fields = tep_db_query("show fields from $table");
      while ($field = tep_db_fetch_array($fields)) {
        $schema .= '  ' . $field['Field'] . ' ' . $field['Type'];
        if (strlen($field['Default']) > 0) {
          $schema .= ' default \'' . $field['Default'] . '\'';
        }
        if ($field['Null'] != 'YES') {
          $schema .= ' not null';
        }
        if (isset($field['Extra'])) {
          $schema .= ' ' . $field['Extra'];
        }
        $schema .= ",\n";
        $table_list[] = $field['Field'];
      }
      $schema = ereg_replace(",\n$", "", $schema);
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
      $rows = tep_db_query("select " . implode(',', $table_list) . " from $table");
      while ($row = tep_db_fetch_array($rows)) {
        $schema_insert = "INSERT INTO $table (" . implode(',', $table_list) . ") VALUES (";
        $num_fields = sizeof($table_list);
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
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td class="pageHeading" align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<?php echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_MAIN; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
          </tr>
          <tr><form action="<?php echo tep_href_link(FILENAME_BACKUP, '', 'NONSSL'); ?>" method="get">
            <td class="main" align="center"><input type="checkbox" name="drop" value="yes">&nbsp;<?php echo TEXT_DROP_TABLES; ?></td>
          </tr>
          <tr>
            <td class="main" align="center"><input type="hidden" name="action" value="backup"><?php echo tep_image_submit('images/button_backup.gif', IMAGE_BACKUP); ?></td>
          </form></tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
  }
?>