<!-- configuration //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CONFIGURATION,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=configuration'),
                               'img'   => ($selected_box == 'configuration') ? tep_image(DIR_WS_IMAGES . 'icon_opened_box.gif', '11', '11', '0', '') : tep_image(DIR_WS_IMAGES . 'icon_open_box.gif', '11', '11', '0', '')
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'configuration') {
    $cfg_groups = '';
    $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
    while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
      $cfg_groups .= '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '">' . $configuration_groups['cgTitle'] . '</a><br>';
    }
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => $cfg_groups
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- configuration_eof //-->
