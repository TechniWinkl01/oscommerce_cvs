<?php
/*
  $Id: product_reviews.php,v 1.40 2001/12/20 14:36:49 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

// lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = tep_get_all_get_params();
  $get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }

  $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';

  $product = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . $languages_id . "' and products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
  $product_values = tep_db_fetch_array($product);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo sprintf(HEADING_TITLE, $product_values['products_name']); ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_values['products_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_NUMBER; ?>&nbsp;</td>
            <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_AUTHOR; ?>&nbsp;</td>
            <td align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_RATING; ?>&nbsp;</td>
            <td align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_READ; ?>&nbsp;</td>
            <td align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_DATE_ADDED; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="5"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
  $reviews = tep_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_REVIEWS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' order by reviews_id DESC");
  if (tep_db_num_rows($reviews)) {
    $row = 0;
    while ($reviews_values = tep_db_fetch_array($reviews)) {
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
      echo '            <td class="smallText">&nbsp;' . $row . '.&nbsp;</td>' . "\n";
      echo '            <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $reviews_values['customers_name'] . '</a>&nbsp;</td>' . "\n";
      echo '            <td align="center" class="smallText">&nbsp;' . tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])) . '&nbsp;</td>' . "\n";
      echo '            <td align="center" class="smallText">&nbsp;' . $reviews_values['reviews_read'] . '&nbsp;</td>' . "\n";
      echo '            <td align="right" class="smallText">&nbsp;' . $date_added . '&nbsp;</td>' . "\n";
      echo '          </tr>' . "\n";
    }
  } else {
?>
          <tr class="productReviews-odd">
            <td colspan="5" class="smallText">&nbsp;<?php echo TEXT_NO_REVIEWS; ?>&nbsp;</td>
          </tr>
<?php
  }
?>
          <tr>
            <td colspan="5"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="5"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?>&nbsp;&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
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
