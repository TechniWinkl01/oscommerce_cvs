<!-- localization //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a class="blacklink" href="' . tep_href_link(basename($PHP_SELF), 'selected_box=localization') . '">' . BOX_HEADING_LOCALIZATION . '</a>'
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'localization') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL') . '">' . BOX_LOCALIZATION_CURRENCIES . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_LANGUAGES, '', 'NONSSL') . '">' . BOX_LOCALIZATION_LANGUAGES . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- localization_eof //-->
