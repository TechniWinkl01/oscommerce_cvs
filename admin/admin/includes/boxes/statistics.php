<!-- statistics //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a class="blacklink" href="' . tep_href_link(basename($PHP_SELF), 'selected_box=statistics') . '">' . BOX_HEADING_STATISTICS . '</a>'
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'statistics') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL') . '">' . BOX_STATISTICS_PRODUCTS_VIEWED . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL') . '">' . BOX_STATISTICS_PRODUCTS_PURCHASED . '</a><br>' .
                                            '&nbsp;<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL') . '">' . BOX_STATISTICS_ORDERS_TOTAL . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- statistics_eof //-->