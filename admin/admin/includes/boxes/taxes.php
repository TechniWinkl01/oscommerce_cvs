<!-- customers //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a class="blacklink" href="' . tep_href_link(basename($PHP_SELF), 'selected_box=taxes') . '">' . BOX_HEADING_LOCATION_AND_TAXES . '</a>'
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'taxes') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, '', 'NONSSL') . '">' . BOX_TAXES_COUNTRIES . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES, '', 'NONSSL') . '">' . BOX_TAXES_ZONES . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL') . '">' . BOX_TAXES_TAX_CLASSES . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_TAX_RATES, '', 'NONSSL') . '">' . BOX_TAXES_TAX_RATES . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- customers_eof //-->
