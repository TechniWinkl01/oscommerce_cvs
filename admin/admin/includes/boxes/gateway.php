<!-- gateway //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_GATEWAY,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=gateway')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'gateway') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_GATEWAY_ITRANSACT, '', 'NONSSL') . '">' . BOX_GATEWAY_ITRANSACT . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- itransact_eof //-->
