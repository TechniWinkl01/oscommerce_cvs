<?php
/*
  $Id: reviews.php,v 1.24 2001/11/29 20:49:18 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
?>
<!-- reviews //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="infoBoxHeading">' . BOX_HEADING_REVIEWS . '</a>');
  new infoBoxHeading($info_box_contents);

  if ($HTTP_GET_VARS['products_id']) {
    $random_product = tep_random_select("select r.reviews_id, substring(rd.reviews_text, 1, 60) as reviews_text, r.reviews_rating, p.products_id, pd.products_name, p.products_image from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and r.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and rd.languages_id = '" . $languages_id . "' order by r.reviews_id DESC limit " . MAX_RANDOM_SELECT_REVIEWS);
  } else {
    $random_product = tep_random_select("select r.reviews_id, substring(rd.reviews_text, 1, 60) as reviews_text, r.reviews_rating, p.products_id, pd.products_name, p.products_image from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and r.reviews_id = rd.reviews_id and r.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and rd.languages_id = '" . $languages_id . "' order by r.reviews_id DESC limit " . MAX_RANDOM_SELECT_REVIEWS);
  }

  if ($random_product) {
// display random review box
    $review = htmlspecialchars($random_product['reviews_text']);
    $review = tep_break_string($review, 15, '-<br>');

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id'], 'NONSSL') . '">' . $review . ' ..</a><br><div align="center">' . tep_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , sprintf(TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '<br><a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '">' . BOX_REVIEWS_MORE . '</a></div>');
    new infoBox($info_box_contents);
  } elseif ($HTTP_GET_VARS['products_id']) {
// display 'write a review' box
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBox"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'box_write_review.gif', 'write review') . '</a></td><td class="infoBox"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></td></tr></table>');
    new infoBox($info_box_contents);
  } else {
// display 'no reviews' box
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_REVIEWS_NO_REVIEWS);
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- reviews_eof //-->
