<?php
/*
  $Id: reviews.php,v 1.32 2002/01/09 11:46:44 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'update_review') {
      tep_db_query("update " . TABLE_REVIEWS . " set reviews_rating = '" . $HTTP_POST_VARS['reviews_rating'] . "', last_modified = now() where reviews_id = '" . $HTTP_POST_VARS['reviews_id'] . "'");
      tep_db_query("update " . TABLE_REVIEWS_DESCRIPTION . " set reviews_text = '" . htmlspecialchars($HTTP_POST_VARS['reviews_text']) . "' where reviews_id = '" . $HTTP_POST_VARS['reviews_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'rID')) . 'info=' . $HTTP_GET_VARS['rID'], 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_review') {
      tep_db_query("delete from " . TABLE_REVIEWS . " where reviews_id = '" . $HTTP_GET_VARS['rID'] . "'");
      tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $HTTP_GET_VARS['rID'] . "'");
      header('Location: ' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'rID', 'info')), 'NONSSL')); tep_exit();
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body onload="SetFocus();">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $reviews_query = tep_db_query("select r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $HTTP_GET_VARS['rID'] . "' and r.reviews_id = rd.reviews_id");
    $reviews = tep_db_fetch_array($reviews_query);
    $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $reviews['products_id'] . "'");
    $products = tep_db_fetch_array($products_query);

    $rInfo_array = tep_array_merge($reviews, $products);
    $rInfo = new reviewInfo($rInfo_array);
?>
      <tr><form name="edit_review" <?php echo 'action="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'info', 'rID')) . 'action=preview&rID=' . $HTTP_GET_VARS['rID'], 'NONSSL') . '"'; ?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">&nbsp;<b><?php echo ENTRY_PRODUCT; ?></b>&nbsp;<?php echo $rInfo->products_name; ?>&nbsp;<br>&nbsp;<b><?php echo ENTRY_FROM; ?></b>&nbsp;<?php echo $rInfo->author; ?>&nbsp;<br>&nbsp;<b><?php echo ENTRY_DATE; ?></b>&nbsp;<?php echo tep_date_short($rInfo->date_added); ?>&nbsp;</td>
            <td align="right"><br><?php echo tep_image(DIR_WS_CATALOG . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main">&nbsp;<b><?php echo ENTRY_REVIEW; ?></b><br>&nbsp;<br><textarea name="reviews_text" wrap="off" cols="60" rows="15"><?php echo $rInfo->text; ?></textarea></td>
          </tr>
          <tr>
            <td align="right" class="smallText">&nbsp;<?php echo ENTRY_REVIEW_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br>&nbsp;<b><?php echo ENTRY_RATING; ?></b>&nbsp;&nbsp;<?php echo TEXT_BAD; ?>&nbsp;<input type="radio" name="reviews_rating" value="1"<?php if ($rInfo->rating == '1') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="reviews_rating" value="2"<?php if ($rInfo->rating == '2') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="reviews_rating" value="3"<?php if ($rInfo->rating == '3') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="reviews_rating" value="4"<?php if ($rInfo->rating == '4') { echo ' CHECKED'; } ?>>&nbsp;<input type="radio" name="reviews_rating" value="5"<?php if ($rInfo->rating == '5') { echo ' CHECKED'; } ?>>&nbsp;<?php echo TEXT_GOOD; ?>&nbsp;</td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br>&nbsp;<input type="hidden" name="date_added" value="<?php echo $rInfo->date_added; ?>"><?php echo tep_image_submit(DIR_WS_IMAGES . 'button_preview.gif', IMAGE_PREVIEW); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'rID', 'info')) . 'info=' . $HTTP_GET_VARS['rID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>&nbsp;</td>
      <input type="hidden" name="reviews_id" value="<?php echo $HTTP_GET_VARS['rID']; ?>"><input type="hidden" name="products_id" value="<?php echo $rInfo->products_id; ?>"><input type="hidden" name="customers_name" value="<?php echo $rInfo->author; ?>"><input type="hidden" name="products_image" value="<?php echo $rInfo->products_image; ?>"></form></tr>
<?php
  } elseif ($HTTP_GET_VARS['action'] == 'preview') {
    if ($HTTP_POST_VARS) {
      $rInfo = new reviewInfo($HTTP_POST_VARS);
    } else {
      $reviews_query = tep_db_query("select r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $HTTP_GET_VARS['rID'] . "' and r.reviews_id = rd.reviews_id");
      $reviews = tep_db_fetch_array($reviews_query);
      $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $reviews['products_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      $rInfo_array = tep_array_merge($reviews, $products);
      $rInfo = new reviewInfo($rInfo_array);
    }
?>
      <tr><form name="update_review" <?php echo 'action="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'info')) . 'action=update_review', 'NONSSL') . '"'; ?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">&nbsp;<b><?php echo ENTRY_PRODUCT; ?></b>&nbsp;<?php echo $rInfo->products_name; ?>&nbsp;<br>&nbsp;<b><?php echo ENTRY_FROM; ?></b>&nbsp;<?php echo $rInfo->author; ?>&nbsp;<br>&nbsp;<b><?php echo ENTRY_DATE; ?></b>&nbsp;<?php echo tep_date_short($rInfo->date_added); ?>&nbsp;</td>
            <td align="right"><br><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main">&nbsp;<b><?php echo ENTRY_REVIEW; ?></b><br>&nbsp;<br><?php echo tep_break_string($rInfo->text, 15); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br>&nbsp;<b><?php echo ENTRY_RATING; ?></b>&nbsp;&nbsp;<?php echo tep_image(DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->rating . '.gif', sprintf(TEXT_OF_5_STARS, $rInfo->rating)); ?>&nbsp;&nbsp;<small>[<?php echo sprintf(TEXT_OF_5_STARS, $rInfo->rating); ?>]</small>&nbsp;</td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
<?php
    if ($HTTP_POST_VARS) {
?>
      <tr>
        <td align="right" class="smallText"><br>
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while(list($key, $value) = each($HTTP_POST_VARS)) echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(stripslashes($value)) . '">';

      echo '<a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'rID')) . 'action=edit&rID=' . $HTTP_POST_VARS['reviews_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;<?php echo tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'rID')) . 'info=' . $HTTP_POST_VARS['reviews_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>&nbsp;</td>
      </form></tr>
<?php
    } else {
      if ($HTTP_GET_VARS['origin']) {
        $back_url = $HTTP_GET_VARS['origin'];
        $back_url_params = '';
      } else {
        $back_url = FILENAME_REVIEWS;
        $back_url_params = tep_get_all_get_params(array('action', 'rID')) . 'info=' . $HTTP_GET_VARS['rID'];
      }
?>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;</td>
      </tr>
<?php
    }
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_PRODUCTS; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_RATING; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_DATE_ADDED; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $reviews_query_raw = "select reviews_id, products_id, date_added, last_modified, reviews_rating from " . TABLE_REVIEWS . " order by date_added DESC";
    $reviews_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $reviews_query_raw, $reviews_query_numrows);
    $reviews_query = tep_db_query($reviews_query_raw);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
      $rows++;

      if ( ((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $reviews['reviews_id'])) && (!$rInfo) ) {
        $reviews_text_query = tep_db_query("select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $reviews['reviews_id'] . "' and r.reviews_id = rd.reviews_id");
        $reviews_text = tep_db_fetch_array($reviews_text_query);

        $products_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $reviews['products_id'] . "'");
        $products_image = tep_db_fetch_array($products_image_query);

// find out the rating average from customer reviews
        $reviews_average_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . $reviews['products_id'] . "'");
        $reviews_average = tep_db_fetch_array($reviews_average_query);

        $review_info = tep_array_merge($reviews_text, $reviews_average);
        $rInfo_array = tep_array_merge($reviews, $review_info, $products_image);
        $rInfo = new reviewInfo($rInfo_array);
      }

      if ($reviews['reviews_id'] == @$rInfo->id) {
        echo '              <tr class="selectedRow" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('info', 'action', 'rID')) . 'action=preview&rID=' . $rInfo->id, 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('info', 'action', 'rID')) . 'info=' . $reviews['reviews_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'info', 'rID')) . 'action=preview&rID=' . $reviews['reviews_id'], 'NONSSL') . '" class="blacklink"><u>' . tep_get_products_name($reviews['products_id']) . '</u></a>'; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $reviews['reviews_rating'] . ' / 5'; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_date_short($reviews['date_added']); ?>&nbsp;</td>
<?php
      if ($reviews['reviews_id'] == @$rInfo->id) {
?>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?php
      } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $reviews['reviews_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText">&nbsp;<?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $info_box_contents = array();
    if ($rInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $rInfo->products_name . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $info_box_contents = array();
    if ($rInfo) {
      if ($HTTP_GET_VARS['action'] == 'delete') {
        $form = '<form name="delete_review" action="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action')) . 'action=delete_review', 'NONSSL') . '" method="post">' . "\n";

        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_REVIEW_INTRO);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<b>' . $rInfo->products_name . '</b>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'rID')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      } else {
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action', 'info')) . 'action=edit&rID=' . $rInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_REVIEWS, tep_get_all_get_params(array('action')) . 'action=delete&rID=' . $rInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($rInfo->date_added) . '<br>&nbsp;' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($rInfo->last_modified));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_REVIEW_AUTHOR . ' ' . $rInfo->author);
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_REVIEW_RATING . ' ' . $rInfo->rating . ' / 5');
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_REVIEW_READ . ' ' . $rInfo->read);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_REVIEW_SIZE . ' ' . $rInfo->text_size . ' bytes');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');
      }
    }
?>
              <tr><?php echo $form; ?>
                <td class="box"><?php new infoBox($info_box_contents); ?></td>
              </tr><?php if ($form) echo '</form>'; ?>
              <tr>
                <td class="box"><?php echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>