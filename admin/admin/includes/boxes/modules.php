<!-- modules //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_MODULES
                              );
  new navigationBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'nowrap',
                               'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_PAYMENT_MODULES, '', 'NONSSL') . '">' . BOX_MODULES_PAYMENT . '</a><br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_SHIPPING_MODULES, '', 'NONSSL') . '">' . BOX_MODULES_SHIPPING . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- modules_eof //-->
