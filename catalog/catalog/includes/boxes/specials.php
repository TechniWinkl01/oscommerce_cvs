<!-- specials //-->
          <tr>
            <td>
<?
  if (tep_random_select("select p.products_id, p.products_name, p.products_price, p.products_image, s.specials_new_products_price from products p, specials s where p.products_status = '1' and p.products_id = s.products_id order by s.specials_date_added DESC limit " . MAX_RANDOM_SELECT_SPECIALS)) {

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '" class="blacklink">' . BOX_HEADING_SPECIALS . '</a>'
                                );
    new infoBoxHeading($info_box_contents);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"], 'NONSSL') . '">' . tep_image($random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><s>' . tep_currency_format($random_product['products_price']) . '</s><br><font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($random_product['specials_new_products_price']) . '</font><br><a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">' . BOX_SPECIALS_MORE . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- specials_eof //-->
