<!-- configuration //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CONFIGURATION
                              );
  new navigationBoxHeading($info_box_contents);

  $cfg_groups = '';
  $configuration_groups_query = tep_db_query('select configuration_group_id as cgID, configuration_group_title as cgTitle from configuration_group order by sort_order');
  while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
    $cfg_groups .= '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '">' . $configuration_groups['cgTitle'] . '</a><br>';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'nowrap',
                               'text'  => $cfg_groups
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- configuration_eof //-->