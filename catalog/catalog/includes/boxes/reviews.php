<!-- reviews //-->
          <tr>
            <td>
<?
  if (tep_random_select("select r.reviews_id, r.reviews_text, r.reviews_rating, p.products_id, p.products_name, p.products_image from reviews r, reviews_extra re, products p where p.products_status = '1' and r.reviews_id = re.reviews_id and re.products_id = p.products_id order by r.reviews_id DESC limit " . MAX_RANDOM_SELECT_REVIEWS)) {

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="blacklink">' . BOX_HEADING_REVIEWS . '</a>'
                                );
    new infoBoxHeading($info_box_contents);

    $review = htmlspecialchars(substr($random_product['reviews_text'], 0, 60));
    $review = tep_break_string($review, 15);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id'], 'NONSSL') . '">' . tep_image($random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id'], 'NONSSL') . '">' . $review . ' ..</a><br><div align="center">' . tep_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , $random_product['reviews_rating'] . ' of 5 Stars!') . '<br><a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '">' . BOX_REVIEWS_MORE . '</a></div>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- reviews_eof //-->
