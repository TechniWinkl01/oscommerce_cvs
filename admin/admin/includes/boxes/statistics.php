<!-- statistics //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_STATISTICS,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=statistics')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'statistics') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL') . '">' . BOX_STATISTICS_PRODUCTS_VIEWED . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL') . '">' . BOX_STATISTICS_PRODUCTS_PURCHASED . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL') . '">' . BOX_STATISTICS_ORDERS_TOTAL . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- statistics_eof //-->
