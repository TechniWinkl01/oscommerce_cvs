<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<?
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
<? $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?
  $product = tep_db_query("select products_name from products where products_status = '1' and products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
  $product_values = tep_db_fetch_array($product);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeader" nowrap>&nbsp;<? echo sprintf(HEADING_TITLE, $product_values['products_name']); ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_values['products_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading" nowrap>&nbsp;<? echo TABLE_HEADING_NUMBER; ?>&nbsp;</td>
            <td class="tableHeading" nowrap>&nbsp;<? echo TABLE_HEADING_AUTHOR; ?>&nbsp;</td>
            <td align="center" class="tableHeading" nowrap>&nbsp;<? echo TABLE_HEADING_RATING; ?>&nbsp;</td>
            <td align="center" class="tableHeading" nowrap>&nbsp;<? echo TABLE_HEADING_READ; ?>&nbsp;</td>
            <td align="right" class="tableHeading" nowrap>&nbsp;<? echo TABLE_HEADING_DATE_ADDED; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="5"><? echo tep_black_line(); ?></td>
          </tr>
<?
  $reviews = tep_db_query("select reviews.reviews_rating, reviews.reviews_id, reviews_extra.customers_id, reviews_extra.date_added, reviews_extra.reviews_read from reviews, reviews_extra where reviews_extra.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and reviews_extra.reviews_id = reviews.reviews_id order by reviews.reviews_id DESC");
  if (tep_db_num_rows($reviews)) {
    $row = 0;
    while ($reviews_values = tep_db_fetch_array($reviews)) {
      $customers_name = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $reviews_values['customers_id'] . "'");
      $customers_name_values = tep_db_fetch_array($customers_name);
      $row++;
      if (strlen($row) < 2) {
        $row = '0' . $row;
      }
      $date_added = tep_date_short($reviews_values['date_added']);
      if (($row / 2) == floor($row / 2)) {
        echo '          <tr class="productReviews-even">' . "\n";
      } else {
        echo '          <tr class="productReviews-odd">' . "\n";
      }
      echo '            <td class="smallText" nowrap>&nbsp;' . $row . '.&nbsp;</td>' . "\n";
      echo '            <td class="smallText" nowrap>&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $customers_name_values['customers_firstname'] . ' ' . $customers_name_values['customers_lastname'] . '</a>&nbsp;</td>' . "\n";
      echo '            <td align="center" class="smallText" nowrap>&nbsp;' . tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])) . '&nbsp;</td>' . "\n";
      echo '            <td align="center" class="smallText" nowrap>&nbsp;' . $reviews_values['reviews_read'] . '&nbsp;</td>' . "\n";
      echo '            <td align="right" class="smallText" nowrap>&nbsp;' . $date_added . '&nbsp;</td>' . "\n";
      echo '          </tr>' . "\n";
    }
  } else {
?>
          <tr class="productReviews-odd">
            <td colspan="5" class="smallText">&nbsp;<? echo TEXT_NO_REVIEWS; ?>&nbsp;</td>
          </tr>
<?
  }
?>
          <tr>
            <td colspan="5"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td align="right" class="main" colspan="5"><br>&nbsp;<a href="<? echo tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_write_a_review.gif', IMAGE_WRITE_A_REVIEW); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_PRODUCT_INFO, $get_params_back, 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
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
