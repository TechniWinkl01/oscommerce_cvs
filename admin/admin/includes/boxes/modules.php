<!-- modules //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_MODULES,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=modules'),
                               'img'  => ($selected_box == 'modules') ? tep_image(DIR_WS_IMAGES . 'icon_opened_box.gif', '11', '11', '0', '') : tep_image(DIR_WS_IMAGES . 'icon_open_box.gif', '11', '11', '0', '')
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'modules') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_PAYMENT_MODULES, '', 'NONSSL') . '">' . BOX_MODULES_PAYMENT . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_SHIPPING_MODULES, '', 'NONSSL') . '">' . BOX_MODULES_SHIPPING . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- modules_eof //-->
