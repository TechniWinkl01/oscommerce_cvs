<?php
/*
  $Id: product_reviews_info.php,v 1.47 2003/02/13 04:23:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = tep_get_all_get_params(array('reviews_id'));
  $get_params = substr($get_params, 0, -1); //remove trailing &

  $reviews_query = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, p.products_id, pd.products_name, p.products_image from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd left join " . TABLE_PRODUCTS . " p on (r.products_id = p.products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id and pd.language_id = '". $languages_id . "') where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id and p.products_status = '1'");
  if (!tep_db_num_rows($reviews_query)) tep_redirect(tep_href_link(FILENAME_REVIEWS));
  $reviews = tep_db_fetch_array($reviews_query);

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
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
<?php
  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . $reviews['reviews_id'] . "'");

  $reviews_text = tep_break_string(htmlspecialchars($reviews['reviews_text']), 60, '-<br>');
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $reviews['products_name']); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $reviews['products_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_PRODUCT; ?></b> <?php echo $reviews['products_name']; ?></td>
            <td class="smallText" rowspan="3" align="center"><a href="javascript:popupImageWindow('<?php echo tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $reviews['products_id']); ?>')"><?php echo tep_image(DIR_WS_IMAGES . $reviews['products_image'], $reviews['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?><br></a></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_FROM; ?></b> <?php echo $reviews['customers_name']; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_DATE; ?></b> <?php echo tep_date_long($reviews['date_added']); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
      </tr>
      <tr>
        <td class="main"><br><?php echo nl2br($reviews_text); ?></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo SUB_TITLE_RATING; ?></b> <?php echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])); ?> <small>[<?php echo sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']); ?>]</small></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, 'action=buy_now&products_id=' . $reviews['products_id']) . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?></a></td>
          </tr>
        </table></td>
      </tr>
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
