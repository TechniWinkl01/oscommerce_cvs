<!-- tools //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a class="blacklink" href="' . tep_href_link(basename($PHP_SELF), 'selected_box=tools') . '">' . BOX_HEADING_TOOLS . '</a>'
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'tools') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, '', 'NONSSL') . '">' . BOX_TOOLS_BACKUP . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_WHOS_ONLINE, '', 'NONSSL') . '">' . BOX_TOOLS_WHOS_ONLINE . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- tools_eof //-->
