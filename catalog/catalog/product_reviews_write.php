<? include('includes/application_top.php'); ?>
<?
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_PRODUCT_REVIEWS_WRITE . '&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL'));
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
  $get_params = tep_get_all_get_params();
  $get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }
?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<? echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <? echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<? echo JS_REVIEW_RATING; ?>";
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
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="product_reviews_write" method="post" action="<? echo tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL'); ?>" onSubmit="return checkForm();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
<?
  $product = tep_db_query("select pd.products_name, p.products_image from products p, products_description pd where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "'");
  $product_values = tep_db_fetch_array($product);

  $customer = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $customer_id . "'");
  $customer_values = tep_db_fetch_array($customer);
?>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo sprintf(TOP_BAR_TITLE, $product_values['products_name']); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
                <td class="main" nowrap>&nbsp;<b><? echo SUB_TITLE_PRODUCT; ?></b>&nbsp;<? echo $product_values['products_name']; ?>&nbsp;</td>
              </tr>
              <tr>
                <td class="main" nowrap>&nbsp;<b><? echo SUB_TITLE_FROM; ?></b>&nbsp;<? echo $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname']; ?>&nbsp;</td>
              </tr>
            </table></td>
            <td align="right" nowrap><br><? echo tep_image($product_values['products_image'], $product_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main" nowrap>&nbsp;<b><? echo SUB_TITLE_REVIEW; ?></b>&nbsp;</td>
            <td class="main" nowrap><textarea name="review" wrap="soft" cols="60" rows="15"></textarea></td>
          </tr>
          <tr>
            <td align="right" colspan="2" class="smallText" nowrap>&nbsp;<? echo TEXT_NO_HTML; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br>&nbsp;<b><? echo SUB_TITLE_RATING; ?></b>&nbsp;&nbsp;<? echo TEXT_BAD; ?>&nbsp;<input type="radio" name="rating" value="1">&nbsp;<input type="radio" name="rating" value="2">&nbsp;<input type="radio" name="rating" value="3">&nbsp;<input type="radio" name="rating" value="4">&nbsp;<input type="radio" name="rating" value="5">&nbsp;<? echo TEXT_GOOD; ?>&nbsp;</td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main" nowrap><br><? echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;<?
    $reviews = tep_db_query("select count(*) as count from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
    if ($reviews_values['count'] == '0') {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $get_params_back, 'NONSSL') . '">';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params_back, 'NONSSL') . '">';
    } ?><? echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
      </tr>
    </table><input type="hidden" name="get_params" value="<? echo $get_params; ?>"></form></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

