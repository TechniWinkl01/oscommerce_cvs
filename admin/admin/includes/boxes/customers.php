<!-- customers //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a class="blacklink" href="' . tep_href_link(basename($PHP_SELF), 'selected_box=customers') . '">' . BOX_HEADING_CUSTOMERS . '</a>'
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'customers') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '">' . BOX_CUSTOMERS_CUSTOMERS . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '">' . BOX_CUSTOMERS_ORDERS . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- customers_eof //-->