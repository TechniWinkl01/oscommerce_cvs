<!-- tools //-->
          <tr>
            <td>
<?php
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
                                 'text'  => '<a href="' . tep_href_link(FILENAME_FILE_MANAGER) . '">' . BOX_TOOLS_FILE_MANAGER . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_BACKUP) . '">' . BOX_TOOLS_BACKUP . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_WHOS_ONLINE) . '">' . BOX_TOOLS_WHOS_ONLINE . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_CACHE) . '">' . BOX_TOOLS_CACHE . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_MAIL) . '">' . BOX_TOOLS_MAIL . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_SERVER_INFO) . '">' . BOX_TOOLS_SERVER_INFO . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE) . '">' . BOX_TOOLS_DEFINE_LANGUAGE . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- tools_eof //-->
