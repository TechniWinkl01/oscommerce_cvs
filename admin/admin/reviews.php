<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_REVIEWS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'update_reviews') {
      tep_db_query("update reviews set reviews_text = '" . htmlspecialchars($HTTP_POST_VARS['review']) . "', reviews_rating = '" . $HTTP_POST_VARS['rating'] . "' where reviews_id = '" . $HTTP_POST_VARS['reviews_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_reviews') {
      tep_db_query("delete from reviews where reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "'");
      tep_db_query("delete from reviews_extra where reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL')); tep_exit();
    }
  }
?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error_message = "<?=JS_ERROR;?>";
  var error = 0;
  var review = document.reviews.review.value;

  if (review.length < 50) {
    error_message = error_message + "<?=JS_REVIEW_TEXT;?>";
    error = 1;
  }

  if ((document.reviews.rating[0].checked) || (document.reviews.rating[1].checked) || (document.reviews.rating[2].checked) || (document.reviews.rating[3].checked) || (document.reviews.rating[4].checked)) {
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

function go() {
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_REVIEWS;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  if (($HTTP_GET_VARS['action'] == 'update') || ($HTTP_GET_VARS['action'] == 'delete')) {
    $reviews = tep_db_query("select reviews_extra.products_id, reviews_extra.customers_id, reviews_extra.date_added, reviews_extra.reviews_read, reviews.reviews_text, reviews.reviews_rating from reviews_extra, reviews where reviews_extra.reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "' and reviews.reviews_id = '" . $HTTP_GET_VARS['reviews_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
    $products = tep_db_query("select products_image from products where products_id = '" . $reviews_values['products_id'] . "'");
    $products_values = tep_db_fetch_array($products);
    tep_products_name($reviews_values['products_id']); // returns $products_name
    tep_customers_name($reviews_values['customers_id']); // returns $customers_name
    $date_added_formated = substr($reviews_values['date_added'], -2) . '/' . substr($reviews_values['date_added'], -4, 2) . '/' . substr($reviews_values['date_added'], 0, 4);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=ENTRY_PRODUCT;?></b>&nbsp;<?=$products_name;?>&nbsp;<br>&nbsp;<b><?=ENTRY_FROM;?></b>&nbsp;<?=$customers_name;?>&nbsp;<br>&nbsp;<b><?=ENTRY_DATE;?></b>&nbsp;<?=$date_added_formated;?>&nbsp;</font></td>
            <td align="right" nowrap><br><?=tep_image(DIR_CATALOG . $products_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0", hspace="5" vspace="5', $products_name);?></td>
          </tr>
        </table>
      </tr>
      <? if ($HTTP_GET_VARS['action'] == 'update') echo '<form name="reviews" method="post" action="' . tep_href_link(FILENAME_REVIEWS, 'action=update_reviews', 'NONSSL') . '" onSubmit="return check_form();"><input type="hidden" name="reviews_id" value="' . $HTTP_GET_VARS['reviews_id'] . '">' . "\n"; ?>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=ENTRY_REVIEW;?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><? if ($HTTP_GET_VARS['action'] == 'update') { echo '<textarea name="review" wrap="off" cols="60" rows="15">' . $reviews_values['reviews_text'] . '</textarea>'; } else { echo nl2br($reviews_values['reviews_text']); } ?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="2" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? if ($HTTP_GET_VARS['action'] == 'update') echo ENTRY_REVIEW_TEXT; ?></font></td>
          </tr>
        </table></td>
      </tr>
<?
    if ($HTTP_GET_VARS['action'] == 'update') {
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=ENTRY_RATING;?></b>&nbsp;&nbsp;<?=TEXT_BAD;?>&nbsp;<input type="radio" name="rating" value="1"<? if ($reviews_values['reviews_rating'] == '1') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="rating" value="2"<? if ($reviews_values['reviews_rating'] == '2') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="rating" value="3"<? if ($reviews_values['reviews_rating'] == '3') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="rating" value="4"<? if ($reviews_values['reviews_rating'] == '4') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="rating" value="5"<? if ($reviews_values['reviews_rating'] == '5') { echo ' CHECKED'; } ?>>&nbsp;<?=TEXT_GOOD;?>&nbsp;</font></td>
      </tr>
<?
    } else {
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=ENTRY_RATING;?></b>&nbsp;&nbsp;<?=tep_image(DIR_CATALOG_IMAGES . 'stars_' . $reviews_values['reviews_rating'] . '.gif', '59', '11', '0',  sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']));?>&nbsp;&nbsp;<small>[<?=sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating']);?>]</small>&nbsp;</font></td>
      </tr>
<?
    }
?>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
<?
    if ($HTTP_GET_VARS['action'] == 'update') {
?>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_update.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
      </tr>
<?
    } else {
?>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=delete_reviews&reviews_id=' . $HTTP_GET_VARS['reviews_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;<?='<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
      </tr>
<?
    }
    if ($HTTP_GET_VARS['action'] == 'update') echo '      </form>' . "\n";
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'reviews_extra.date_added';
    }
?>
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="reviews_extra.date_added"<? if ($order_by == 'reviews_extra.date_added') { echo ' SELECTED'; } ?>>Date Added</option><option value="reviews_extra.reviews_read"<? if ($order_by == 'reviews_extra.reviews_read') { echo ' SELECTED'; } ?>>Reviews Read</option><option value="reviews.reviews_rating"<? if ($order_by == 'reviews.reviews_rating') { echo ' SELECTED'; } ?>>Reviews Rating</option></select>&nbsp;&nbsp;</form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_AUTHOR;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_READ;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_RATING;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DATE_ADDED;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
<?
    $reviews = tep_db_query("select reviews_extra.reviews_id, reviews_extra.products_id, reviews_extra.customers_id, reviews_extra.date_added, reviews_extra.reviews_read, reviews.reviews_rating from reviews_extra, reviews where reviews_extra.reviews_id = reviews.reviews_id order by " . $order_by . " DESC");
    while ($reviews_values = tep_db_fetch_array($reviews)) {
      tep_products_name($reviews_values['products_id']); // returns $products_name
      tep_customers_name($reviews_values['customers_id']); // returns $customers_name
      $date_added_formated = substr($reviews_values['date_added'], -2) . '/' . substr($reviews_values['date_added'], -4, 2) . '/' . substr($reviews_values['date_added'], 0, 4);
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_name;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$customers_name;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$reviews_values['reviews_read'];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$reviews_values['reviews_rating'];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$date_added_formated;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=update&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;<?='<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=delete&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
        </table></td>
      </tr>
<?
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? include('includes/application_bottom.php'); ?>