<!-- localization //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LOCALIZATION,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=localization'),
                               'img'  => ($selected_box == 'localization') ? tep_image(DIR_WS_IMAGES . 'icon_opened_box.gif', '', '11', '11') : tep_image(DIR_WS_IMAGES . 'icon_open_box.gif', '', '11', '11')
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
