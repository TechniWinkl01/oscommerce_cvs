<?php
/*
  $Id: product_reviews_write.php,v 1.51 2003/02/13 04:23:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $product_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1'");
  $valid_product = (tep_db_num_rows($product_query) > 0);

  if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'process') {
    if ($valid_product == true) { // We got to the process but it is an illegal product, don't write
      $customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
      $customer_values = tep_db_fetch_array($customer);
      $date_now = date('Ymd');
      tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . $HTTP_GET_VARS['products_id'] . "', '" . $customer_id . "', '" . addslashes($customer_values['customers_firstname']) . ' ' . addslashes($customer_values['customers_lastname']) . "', '" . $HTTP_POST_VARS['rating'] . "', now())");
      $insert_id = tep_db_insert_id();
      tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . $insert_id . "', '" . $languages_id . "', '" . $HTTP_POST_VARS['review'] . "')");
    }

    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, $HTTP_POST_VARS['get_params']));
  }

// lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = tep_get_all_get_params();
  $get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if (tep_not_null($get_params_back)) {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params));

  $customer_info_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
  $customer_info = tep_db_fetch_array($customer_info_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($valid_product == false) {
?>
          <tr>
            <td class="main"><br><b><?php echo ERROR_INVALID_PRODUCT; ?></b></td>
          </tr>
<?php
  } else {
    $product_info = tep_db_fetch_array($product_query);
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <?php echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?>
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_PRODUCT; ?></b> <?php echo $product_info['products_name']; ?></td>
            <td rowspan="4" valign="top" align="right"><br><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_FROM; ?></b> <?php echo $customer_info['customers_firstname'] . ' ' . $customer_info['customers_lastname']; ?></td>
          </tr>
          <tr>
            <td class="main"><br><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_textarea_field('review', 'soft', 60, 15);?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_NO_HTML; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo SUB_TITLE_RATING; ?></b> <?php echo TEXT_BAD . ' ' . tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5') . ' ' . TEXT_GOOD; ?></td>
      </tr>
      <tr>
        <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params_back) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table><?php echo tep_draw_hidden_field('get_params', $get_params); ?></form></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
