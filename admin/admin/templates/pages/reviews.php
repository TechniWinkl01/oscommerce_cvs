<?php
/*
  $Id: reviews.php,v 1.1 2004/07/22 23:30:26 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div id="infoBox_rDefault" <?php if (!empty($action)) { echo 'style="display: none;"'; } ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
    <thead>
      <tr>
        <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th><?php echo TABLE_HEADING_LANGUAGE; ?></th>
        <th><?php echo TABLE_HEADING_RATING; ?></th>
        <th><?php echo TABLE_HEADING_DATE_ADDED; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  $Qreviews = $osC_Database->query('select r.reviews_id, r.products_id, r.date_added, r.last_modified, r.reviews_rating, rd.languages_id, pd.products_name, l.name as languages_name, l.directory as languages_directory, l.image as languages_image from :table_reviews r, :table_reviews_description rd left join :table_products_description pd on (r.products_id = pd.products_id and rd.languages_id = pd.language_id), :table_languages l where r.reviews_id = rd.reviews_id and rd.languages_id = l.languages_id order by r.date_added desc');
  $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
  $Qreviews->bindTable(':table_reviews_description', TABLE_REVIEWS_DESCRIPTION);
  $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
  $Qreviews->bindTable(':table_languages', TABLE_LANGUAGES);
  $Qreviews->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
  $Qreviews->execute();

  while ($Qreviews->next()) {
    if (!isset($rInfo) && (!isset($_GET['rID']) || (isset($_GET['rID']) && ($_GET['rID'] == $Qreviews->valueInt('reviews_id'))))) {
      $Qtext = $osC_Database->query('select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size from :table_reviews r, :table_reviews_description rd where r.reviews_id = :reviews_id and r.reviews_id = rd.reviews_id');
      $Qtext->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qtext->bindTable(':table_reviews_description', TABLE_REVIEWS_DESCRIPTION);
      $Qtext->bindInt(':reviews_id', $Qreviews->valueInt('reviews_id'));
      $Qtext->execute();

      $Qimage = $osC_Database->query('select products_image from :table_products where products_id = :products_id');
      $Qimage->bindTable(':table_products', TABLE_PRODUCTS);
      $Qimage->bindInt(':products_id', $Qreviews->valueInt('products_id'));
      $Qimage->execute();

      $Qaverage = $osC_Database->query('select (avg(reviews_rating) / 5 * 100) as average_rating from :table_reviews where products_id = :products_id');
      $Qaverage->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qaverage->bindInt(':products_id', $Qreviews->valueInt('products_id'));
      $Qaverage->execute();

      $rInfo = new objectInfo(array_merge($Qreviews->toArray(), $Qtext->toArray(), $Qimage->toArray(), $Qaverage->toArray()));
    }

    if (isset($rInfo) && ($Qreviews->valueInt('reviews_id') == $rInfo->reviews_id) ) {
      echo '      <tr class="selected">' . "\n";
    } else {
      echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $Qreviews->valueInt('reviews_id')) . '\';">' . "\n";
    }
?>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $Qreviews->valueInt('reviews_id') . '&action=rPreview') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '&nbsp;' . $Qreviews->value('products_name') . '</a>'; ?></td>
        <td align="center"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $Qreviews->value('languages_directory') . '/images/' . $Qreviews->value('languages_image'), $Qreviews->value('languages_name')); ?></td>
        <td align="center"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.gif', sprintf(TEXT_OF_5_STARS, $Qreviews->valueInt('reviews_rating'))); ?></td>
        <td><?php echo tep_date_short($Qreviews->value('date_added')); ?></td>
        <td align="right">
<?php
    echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $Qreviews->valueInt('reviews_id') . '&action=rEdit') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;';

    if (isset($rInfo) && ($Qreviews->valueInt('reviews_id') == $rInfo->reviews_id)) {
      echo '<a href="#" onClick="toggleInfoBox(\'rDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $Qreviews->valueInt('reviews_id') . '&action=rDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    }
?>
        </td>
      </tr>
<?php
  }
?>
    </tbody>
  </table>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td class="smallText"><?php echo $Qreviews->displayBatchLinksTotal(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
      <td class="smallText" align="right"><?php echo $Qreviews->displayBatchLinksPullDown(); ?></td>
    </tr>
  </table>
</div>

<?php
  if (isset($rInfo)) {
?>

<div id="infoBox_rDelete" <?php if ($action != 'rDelete') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . ' ' . $rInfo->products_name; ?></div>
  <div class="infoBoxContent">
    <p><?php echo TEXT_INFO_DELETE_REVIEW_INTRO; ?></p>
    <p><?php echo '<b>' . $rInfo->products_name . '</b>'; ?></p>
    <p align="center"><?php echo '<input type="button" value="' . IMAGE_DELETE . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm') . '\';" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'rDefault\');" class="operationButton">'; ?></p>
  </div>
</div>

<?php
  }
?>
