<!-- statistics //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_STATISTICS
                              );
  new navigationBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL') . '">' . BOX_STATISTICS_PRODUCTS_VIEWED . '</a><br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL') . '">' . BOX_STATISTICS_PRODUCTS_PURCHASED . '</a><br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL') . '">' . BOX_STATISTICS_ORDERS_TOTAL . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- statistics_eof //-->