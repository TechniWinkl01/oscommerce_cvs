<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_REVIEWS; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
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
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_reviews_new.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
<?
  $new = tep_db_query("select reviews.reviews_id, reviews.reviews_text, reviews.reviews_rating, reviews_extra.date_added, products.products_id, products.products_name, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location, customers.customers_firstname, customers.customers_lastname from reviews, reviews_extra, products, manufacturers, products_to_manufacturers, customers where reviews.reviews_id = reviews_extra.reviews_id and reviews_extra.products_id = products.products_id and reviews_extra.customers_id = customers.customers_id and reviews_extra.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by reviews.reviews_id DESC limit " . MAX_DISPLAY_NEW_REVIEWS);
  $row = 0;
  while ($new_values = tep_db_fetch_array($new)) {
    $row++;
    $products_name = tep_products_name($new_values['manufacturers_location'], $new_values['manufacturers_name'], $new_values['products_name']);
    $review = htmlspecialchars(substr($new_values['reviews_text'], 0, 60));
    $review = tep_break_string($review, 15);

    echo '            <td align="center"><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $new_values['products_id'] . '&reviews_id=' . $new_values['reviews_id'], 'NONSSL') . '">' . tep_image($new_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0', $products_name) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $new_values['products_id'] . '&reviews_id=' . $new_values['reviews_id'], 'NONSSL') . '">' . $products_name . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $new_values['products_id'] . '&reviews_id=' . $new_values['reviews_id'], 'NONSSL') . '">' . $review . ' ..</a><br><div align="center">' . tep_image(DIR_IMAGES . 'stars_' . $new_values['reviews_rating'] . '.gif', '59', '12', '0', sprintf(TEXT_OF_5_STARS, $new_values['reviews_rating'])) . '</div></font></td>' . "\n";
    if ((($row / 3) == floor($row / 3)) && ($row != 6)) {
      echo '          </tr>' . "\n";
      echo '          <tr>' . "\n";
      echo '            <td>&nbsp;</td>' . "\n";
      echo '          </tr>' . "\n";
      echo '          <tr>' . "\n";
    }    
  }
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><a href="<?=tep_href_link(FILENAME_DEFAULT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU);?></a>&nbsp;&nbsp;</font></td>
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