<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
// lets retrieve all $HTTP_GET_VARS keys and values..
  $keys = array_keys($HTTP_GET_VARS);
  $values = array_values($HTTP_GET_VARS);

  $get_params = '';
  for ($i=0;$i<sizeof($keys);$i++) {
    if ($keys[$i] != 'reviews_id') {
      $get_params.=$keys[$i] . '=' . $values[$i] . '&';
    }
  }
  $get_params = substr($get_params, 0, -1); //remove trailing &
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
<?
  tep_db_query("update reviews_extra set reviews_read = reviews_read+1 where reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "'");

  $reviews = tep_db_query("select reviews.reviews_text, reviews.reviews_rating, reviews.reviews_id, reviews_extra.products_id, reviews_extra.customers_id, reviews_extra.date_added, reviews_extra.reviews_read from reviews, reviews_extra where reviews.reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "' and reviews_extra.reviews_id = reviews.reviews_id");
  $reviews_values = tep_db_fetch_array($reviews);

  $reviews_text = htmlspecialchars($reviews_values['reviews_text']);
  $reviews_text = tep_break_string($reviews_text, 15);


  $product = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_name, products.products_image from manufacturers, products_to_manufacturers, products where products.products_id = '" . $reviews_values['products_id'] . "' and products_to_manufacturers.products_id = products.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
  $product_values = tep_db_fetch_array($product);

  $products_name = tep_products_name($product_values['manufacturers_location'], $product_values['manufacturers_name'], $product_values['products_name']);

  $customer = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $reviews_values['customers_id'] . "'");
  $customer_values = tep_db_fetch_array($customer);
?>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=sprintf(HEADING_TITLE, $products_name);?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_reviews.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_PRODUCT;?></b>&nbsp;<?=$products_name;?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_FROM;?></b>&nbsp;<?=$customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_DATE;?></b>&nbsp;<?=date('l, jS F, Y', mktime(0,0,0,substr($reviews_values['date_added'], 4, 2),substr($reviews_values['date_added'], -2),substr($reviews_values['date_added'], 0, 4)));?>&nbsp;</font></td>
              </tr>
            </table></td>
            <td align="right"><br><?=tep_image($product_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0", hspace="5" vspace="5', $products_name);?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=SUB_TITLE_REVIEW;?>&nbsp;</b></font></td>
      </tr>
      <tr>
        <td wrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><? echo nl2br($reviews_text); ?></font></td>
      </tr>
      <tr>
        <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><br><b>&nbsp;<?=SUB_TITLE_RATING;?>&nbsp;</b>&nbsp;<?=tep_image(DIR_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', '59', '11', '0',  sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']));?>&nbsp;&nbsp;<small>[<?=sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']);?>]</small>&nbsp;</font></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><br><a href="<?=tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_write_a_review.gif', '140', '24', '0', IMAGE_WRITE_A_REVIEW);?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK);?></a>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>