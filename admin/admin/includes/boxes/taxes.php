<!-- customers //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LOCATION_AND_TAXES
                              );
  new navigationBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'nowrap',
                               'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES, '', 'NONSSL') . '">' . BOX_TAXES_ZONES . '</a><br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL') . '">' . BOX_TAXES_TAX_CLASSES . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->