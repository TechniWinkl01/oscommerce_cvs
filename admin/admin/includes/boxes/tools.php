<!-- tools //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_TOOLS,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'tools') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_BACKUP, '', 'NONSSL') . '">' . BOX_TOOLS_BACKUP . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_WHOS_ONLINE, '', 'NONSSL') . '">' . BOX_TOOLS_WHOS_ONLINE . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_CACHE, '', 'NONSSL') . '">' . BOX_TOOLS_CACHE . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- tools_eof //-->
