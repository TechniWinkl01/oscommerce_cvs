<!-- statistics //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_STATISTICS,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=statistics'),
                               'img'  => ($selected_box == 'statistics') ? tep_image(DIR_WS_IMAGES . 'icon_opened_box.gif', '', '11', '11') : tep_image(DIR_WS_IMAGES . 'icon_open_box.gif', '', '11', '11')
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
