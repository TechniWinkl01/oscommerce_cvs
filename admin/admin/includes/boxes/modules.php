<!-- modules //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_MODULES,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=modules')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'modules') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL') . '">' . BOX_MODULES_PAYMENT . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL') . '">' . BOX_MODULES_SHIPPING . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- modules_eof //-->
