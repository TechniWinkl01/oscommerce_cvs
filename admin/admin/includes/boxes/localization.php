<!-- localization //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_LOCALIZATION,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=localization')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'localization') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL') . '">' . BOX_LOCALIZATION_CURRENCIES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_LANGUAGES, '', 'NONSSL') . '">' . BOX_LOCALIZATION_LANGUAGES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL') . '">' . BOX_LOCALIZATION_ORDERS_STATUS . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- localization_eof //-->
