<!-- specials //-->
<?
  tep_random_select("select products.products_id, products.products_name, products.products_price, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location, specials.specials_new_products_price from products, products_to_manufacturers, manufacturers, specials where products.products_status='1' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id and specials.products_id = products.products_id order by specials.specials_date_added DESC limit " . MAX_RANDOM_SELECT_SPECIALS);

  $products_name = tep_products_name($random_product['manufacturers_location'], $random_product['manufacturers_name'], $random_product['products_name']);
?>
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_SPECIALS, '', 'NONSSL');?>" class="blacklink"><?=BOX_HEADING_SPECIALS;?></a>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="center" bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"], 'NONSSL');?>"><?=tep_image($random_product['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0',  $products_name);?></a><br><s><?=tep_currency_format($random_product["products_price"]);?></s>&nbsp;&nbsp;<font color="<?=SPECIALS_PRICE_COLOR;?>"><?=tep_currency_format($random_product["specials_new_products_price"]);?></font><br><a href="<?=tep_href_link(FILENAME_SPECIALS, '', 'NONSSL');?>"><?=BOX_SPECIALS_MORE;?></a></font></td>
          </tr>
<!-- specials_eof //-->
