<?php
/*
  $Id: reviews_preview.php,v 1.1 2004/07/22 23:30:26 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  if (empty($_POST)) {
    $Qreviews = $osC_Database->query('select r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating, p.products_image, pd.products_name from :table_reviews r, :table_reviews_description rd left join :table_products_description pd on (r.products_id = pd.products_id and rd.languages_id = pd.language_id) left join :table_products p on (r.products_id = p.products_id) where r.reviews_id = :reviews_id and r.reviews_id = rd.reviews_id');
    $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreviews->bindTable(':table_reviews_description', TABLE_REVIEWS_DESCRIPTION);
    $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qreviews->bindTable(':table_products', TABLE_PRODUCTS);
    $Qreviews->bindInt(':reviews_id', $_GET['rID']);
    $Qreviews->execute();

    $rInfo = new objectInfo($Qreviews->toArray());
  } else {
    $Qreview = $osC_Database->query('select r.customers_name, r.date_added, p.products_image, pd.products_name from :table_reviews r, :table_reviews_description rd, :table_products p, :table_products_description pd where r.reviews_id = :reviews_id and r.products_id = p.products_id and p.products_id = pd.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = pd.language_id');
    $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreview->bindTable(':table_reviews_description', TABLE_REVIEWS_DESCRIPTION);
    $Qreview->bindTable(':table_products', TABLE_PRODUCTS);
    $Qreview->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qreview->bindInt(':reviews_id', $_GET['rID']);
    $Qreview->execute();

    $rInfo = new objectInfo(array_merge($_POST, $Qreview->toArray()));
  }
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<p class="main"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '<b>' . ENTRY_PRODUCT . '</b> ' . $rInfo->products_name . '<br><b>' . ENTRY_FROM . '</b> ' . $rInfo->customers_name . '<br><br><b>' . ENTRY_DATE . '</b> ' . tep_date_short($rInfo->date_added); ?></p>

<p class="main"><?php echo '<b>' . ENTRY_REVIEW . '</b><br>' . nl2br(tep_output_string_protected($rInfo->reviews_text)); ?></p>

<p class="main"><?php echo '<b>' . ENTRY_RATING . '</b>&nbsp;' . tep_image(DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif', sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating)) . '&nbsp;<small>[' . sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating) . ']</small>'; ?></p>

<?php
  if (empty($_POST)) {
    echo '<p align="right"><input type="button" value="' . IMAGE_BACK . '" class="operationButton" onClick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID']) . '\';"></p>';
  } else {
    echo tep_draw_form('update', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=update', 'post', 'enctype="multipart/form-data"');

    foreach ($_POST as $key => $value) {
      echo osc_draw_hidden_field($key, $value);
    }

    echo '<p align="right"><input type="submit" value="' . IMAGE_BACK . '" name="review_edit" class="operationButton"> <input type="submit" value="' . IMAGE_UPDATE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" class="operationButton" onClick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID']) . '\';"></p>';

    echo '</form>';
  }
?>
