<!-- whats_new //-->
<?
  tep_random_select("select products.products_id, products.products_name, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location from products, products_to_manufacturers, manufacturers where products.products_status='1' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by products.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW);

  $products_name = tep_products_name($random_product['manufacturers_location'], $random_product['manufacturers_name'], $random_product['products_name']);
?>
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_WHATS_NEW;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="center" bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"], 'NONSSL');?>"><?=tep_image($random_product['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0', $products_name);?></a><br><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"], 'NONSSL');?>"><?=$products_name;?></a></font></td>
          </tr>
<!-- whats_new //-->
