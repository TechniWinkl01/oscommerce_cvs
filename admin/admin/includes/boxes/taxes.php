<!-- taxes //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_LOCATION_AND_TAXES,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=taxes')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'taxes') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_COUNTRIES, '', 'NONSSL') . '">' . BOX_TAXES_COUNTRIES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_ZONES, '', 'NONSSL') . '">' . BOX_TAXES_ZONES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_GEO_ZONES, '', 'NONSSL') . '">' . BOX_TAXES_GEO_ZONES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL') . '">' . BOX_TAXES_TAX_CLASSES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_TAX_RATES, '', 'NONSSL') . '">' . BOX_TAXES_TAX_RATES . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- taxes_eof //-->
