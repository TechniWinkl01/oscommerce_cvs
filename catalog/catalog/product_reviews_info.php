<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
// lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = tep_get_all_get_params(array('reviews_id'));
  $get_params = substr($get_params, 0, -1); //remove trailing &
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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


  $product = tep_db_query("select products_name, products_image from products where products_id = '" . $reviews_values['products_id'] . "'");
  $product_values = tep_db_fetch_array($product);

  $customer = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $reviews_values['customers_id'] . "'");
  $customer_values = tep_db_fetch_array($customer);
?>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo sprintf(HEADING_TITLE, $product_values['products_name']); ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_values['products_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<b><? echo SUB_TITLE_PRODUCT; ?></b>&nbsp;<? echo $product_values['products_name']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<b><? echo SUB_TITLE_FROM; ?></b>&nbsp;<? echo $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<b><? echo SUB_TITLE_DATE; ?></b>&nbsp;<? echo tep_date_long($reviews_values['date_added']); ?>&nbsp;</font></td>
              </tr>
            </table></td>
            <td align="right"><br><? echo tep_image($product_values['products_image'], $product_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td nowrap><?php echo FONT_STYLE_MAIN; ?><b>&nbsp;<? echo SUB_TITLE_REVIEW; ?>&nbsp;</b></font></td>
      </tr>
      <tr>
        <td wrap><br><?php echo FONT_STYLE_MAIN; ?><? echo nl2br($reviews_text); ?></font></td>
      </tr>
      <tr>
        <td nowrap><?php echo FONT_STYLE_MAIN; ?><br><b>&nbsp;<? echo SUB_TITLE_RATING; ?>&nbsp;</b>&nbsp;<? echo tep_image(DIR_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])); ?>&nbsp;&nbsp;<small>[<? echo sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']); ?>]</small>&nbsp;</font></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?><br><a href="<? echo tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_write_a_review.gif', IMAGE_WRITE_A_REVIEW); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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