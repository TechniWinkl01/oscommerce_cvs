<!-- reviews //-->
<?
  tep_random_select("select reviews.reviews_id, reviews.reviews_text, reviews.reviews_rating, reviews_extra.date_added, products.products_id, products.products_name, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location, customers.customers_firstname, customers.customers_lastname from reviews, reviews_extra, products, manufacturers, products_to_manufacturers, customers where reviews.reviews_id = reviews_extra.reviews_id and reviews_extra.products_id = products.products_id and reviews_extra.customers_id = customers.customers_id and reviews_extra.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by reviews.reviews_id DESC limit " . MAX_RANDOM_SELECT_REVIEWS);

  $products_name = tep_products_name($random_product['manufacturers_location'], $random_product['manufacturers_name'], $random_product['products_name']);
  
  $review = htmlspecialchars(substr($random_product['reviews_text'], 0, 60));
  $review = tep_break_string($review, 15);
?>
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_REVIEWS, '', 'NONSSL');?>" class="blacklink"><?=BOX_HEADING_REVIEWS;?></a>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><div align="center"><a href="<?=tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id'], 'NONSSL');?>"><?=tep_image($random_product['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0', $products_name);?></a></div><a href="<?=tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id'], 'NONSSL');?>"><?=$review;?> ..</a><br><div align="center"><img src="<?=DIR_IMAGES;?>stars_<?=$random_product['reviews_rating'];?>.gif" width="59" height="12" border="0" alt=" <?=$random_product['reviews_rating'];?> of 5 Stars! "><br><a href="<?=tep_href_link(FILENAME_REVIEWS, '', 'NONSSL');?>"><?=BOX_REVIEWS_MORE;?></a></div></font></td>
          </tr>
<!-- reviews_eof //-->
