<? include('includes/application_top.php'); ?>
<?
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=product_reviews_write&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL'));
    tep_exit();
  }

  if (@$HTTP_GET_VARS['action'] == 'process') {
    $date_now = date('Ymd');
    tep_db_query("insert into reviews values ('', '" . htmlspecialchars($HTTP_POST_VARS['review']) . "', '" . $HTTP_POST_VARS['rating'] . "')");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into reviews_extra values ('" . $insert_id . "', '" . $HTTP_GET_VARS['products_id'] . "', '" . $customer_id . "', '" . $date_now . "', 0)");

    header('Location: ' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $HTTP_POST_VARS['get_params'], 'NONSSL'));
    tep_exit();
  }

// lets retrieve all $HTTP_GET_VARS keys and values..
  $keys = array_keys($HTTP_GET_VARS);
  $values = array_values($HTTP_GET_VARS);

  $get_params = '';
  $get_params_back = ''; // for back button
  for ($i=0;$i<sizeof($keys);$i++) {
    $get_params.=$keys[$i] . '=' . $values[$i] . '&';
    if ($keys[$i] != 'reviews_id') {
      $get_params_back.=$keys[$i] . '=' . $values[$i] . '&';
    }
  }
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?=JS_ERROR;?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?=REVIEW_TEXT_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_REVIEW_TEXT;?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?=JS_REVIEW_RATING;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
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
    <td width="100%" valign="top"><form name="product_reviews_write" method="post" action="<?=tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL');?>" onSubmit="return checkForm();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
<?
  $product = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_name, products.products_image from manufacturers, products_to_manufacturers, products where products.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and products_to_manufacturers.products_id = products.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
  $product_values = tep_db_fetch_array($product);

  $products_name = tep_products_name($product_values['manufacturers_location'], $product_values['manufacturers_name'], $product_values['products_name']);

  $customer = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $customer_id . "'");
  $customer_values = tep_db_fetch_array($customer);
?>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=sprintf(TOP_BAR_TITLE, $products_name);?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
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
            </table></td>
            <td align="right" nowrap><br><?=tep_image($product_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0", hspace="5" vspace="5', $products_name);?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_REVIEW;?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><textarea name="review" wrap="soft" cols="60" rows="15"></textarea></font></td>
          </tr>
          <tr>
            <td align="right" colspan="2" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_NO_HTML;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_RATING;?></b>&nbsp;&nbsp;<?=TEXT_BAD;?>&nbsp;<input type="radio" name="rating" value="1">&nbsp;<input type="radio" name="rating" value="2">&nbsp;<input type="radio" name="rating" value="3">&nbsp;<input type="radio" name="rating" value="4">&nbsp;<input type="radio" name="rating" value="5">&nbsp;<?=TEXT_GOOD;?>&nbsp;</font></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '70', '24', '0', IMAGE_INSERT);?>&nbsp;<?
    $reviews = tep_db_query("select count(*) as count from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
    if ($reviews_values['count'] == '0') {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $get_params_back, 'NONSSL') . '">';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params_back, 'NONSSL') . '">';
    } ?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '72', '24', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
      </tr>
    </table><input type="hidden" name="get_params" value="<?=$get_params;?>"></form></td>
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