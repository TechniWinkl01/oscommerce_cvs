<!-- manufacturers //-->
<?
  if (DISPLAY_MANUFACTURERS_BOX) {
?>
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_MANUFACTURERS
                              );
  new infoBoxHeading($info_box_contents);

  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from manufacturers order by manufacturers_name");

  if (tep_db_num_rows($manufacturers_query) <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
// Display a list
    $manufaucters_list = '';
    while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_values['manufacturers_id'], 'NONSSL') . '">' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</a><br>';
    }

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $manufacturers_list
                                );
  } else {
// Display a drop-down
    $select_box = '<select name="manufacturers_id" onChange="this.form.submit();" style="font-size:11px;font-family:Verdana,Arial;">';
    $select_box .= '<option value="">' . PULL_DOWN_DEFAULT . '</option>';
    while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
      $select_box .= '<option value="' . $manufacturers_values['manufacturers_id'] . '"';
      if ($HTTP_GET_VARS['manufacturers_id'] == $manufacturers_values['manufacturers_id']) $select_box .= ' SELECTED';
      $select_box .= '>' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
    }
    $select_box .= "</select>";
    if (SID) $select_box .= tep_hide_fields(array(tep_session_name()));

    $info_box_contents = array();
    $info_box_contents[] = array('form' => '<form name="manufacturers" method="get" action="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">',
                                 'align' => 'left',
                                 'text'  => $select_box
                                );
  }

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?
  }
?>
<!-- manufacturers_eof //-->