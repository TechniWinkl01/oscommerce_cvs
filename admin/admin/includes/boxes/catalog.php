<!-- catalog //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_CATALOG,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=catalog')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'catalog') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '">' . BOX_CATALOG_CATEGORIES_PRODUCTS . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">' . BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '">' . BOX_CATALOG_MANUFACTURERS . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '">' . BOX_CATALOG_REVIEWS . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">' . BOX_CATALOG_SPECIALS . '</a><br>' .
                                            '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- catalog_eof //-->
