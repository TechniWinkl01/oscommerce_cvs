<!-- whats_new //-->
          <tr>
            <td>
<?
  $products = @tep_db_query('select products_id from products limit 0,1');
  if (@tep_db_num_rows($products) > 0) {
    tep_random_select("select products.products_id, products.products_name, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location from products, products_to_manufacturers, manufacturers where products.products_status='1' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by products.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_HEADING_WHATS_NEW
                                );
    new infoBoxHeading($info_box_contents);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id'], 'NONSSL') . '">' . tep_image($random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id'], 'NONSSL') . '">' . $random_product['products_name'] . '</a>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- whats_new //-->
