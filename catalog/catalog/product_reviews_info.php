<?php
/*
  $Id: product_reviews_info.php,v 1.32 2001/11/22 22:28:45 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

// lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = tep_get_all_get_params(array('reviews_id'));
  $get_params = substr($get_params, 0, -1); //remove trailing &

  $location = ' : <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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
<table border="0" width="100%" cellspacing="5" cellpadding="5">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "'");

  $reviews = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id");
  $reviews_values = tep_db_fetch_array($reviews);

  $reviews_text = htmlspecialchars($reviews_values['reviews_text']);
  $reviews_text = tep_break_string($reviews_text, 15);

  $product = tep_db_query("select p.products_id, pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $reviews_values['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '". $languages_id . "'");
  $product_values = tep_db_fetch_array($product);
?>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main">&nbsp;<b><?php echo SUB_TITLE_PRODUCT; ?></b>&nbsp;<?php echo $product_values['products_name']; ?>&nbsp;</td>
              </tr>
              <tr>
                <td class="main">&nbsp;<b><?php echo SUB_TITLE_FROM; ?></b>&nbsp;<?php echo $reviews_values['customers_name']; ?>&nbsp;</td>
              </tr>
              <tr>
                <td class="main">&nbsp;<b><?php echo SUB_TITLE_DATE; ?></b>&nbsp;<?php echo tep_date_long($reviews_values['date_added']); ?>&nbsp;</td>
              </tr>
            </table></td>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" align="right"><a href="javascript:popupImageWindow('<?php echo tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_values['products_id']); ?>')"><?php echo tep_image($product_values['products_image'], $product_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?></a></td>
              </tr>
              <tr>
                <td align="right" class="smallText"><a href="javascript:popupImageWindow('<?php echo tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_values['products_id']); ?>')"><?php echo TEXT_CLICK_TO_ENLARGE; ?></a></td>
              </tr>
            </table></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td class="main"><b>&nbsp;<?php echo SUB_TITLE_REVIEW; ?>&nbsp;</b></td>
      </tr>
      <tr>
        <td class="main"><br><?php echo nl2br($reviews_text); ?></td>
      </tr>
      <tr>
        <td class="main"><br><b>&nbsp;<?php echo SUB_TITLE_RATING; ?>&nbsp;</b>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])); ?>&nbsp;&nbsp;<small>[<?php echo sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']); ?>]</small>&nbsp;</td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>&nbsp;&nbsp;</td>
            <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, 'action=buy_now&products_id=' . $product_values['products_id'], 'NONSSL') . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?></a>&nbsp;&nbsp;</td>
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