<?php
/*
  $Id: products_preview.php,v 1.1 2004/08/27 22:13:15 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  if (isset($_GET['pID']) && empty($_POST)) {
    $Qp = $osC_Database->query('select products_id, products_quantity, products_model, products_image, products_price, products_weight, products_weight_class, products_date_added, products_last_modified, date_format(products_date_available, "%Y-%m-%d") as products_date_available, products_status, products_tax_class_id, manufacturers_id from :table_products where products_id = :products_id');
    $Qp->bindTable(':table_products', TABLE_PRODUCTS);
    $Qp->bindInt(':products_id', $_GET['pID']);
    $Qp->execute();

    $Qpd = $osC_Database->query('select products_name, products_description, products_url, language_id from :table_products_description where products_id = :products_id');
    $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qpd->bindInt(':products_id', $_GET['pID']);
    $Qpd->execute();

    $pd_extra = array();
    while ($Qpd->next()) {
      $pd_extra['products_name'][$Qpd->valueInt('language_id')] = $Qpd->value('products_name');
      $pd_extra['products_description'][$Qpd->valueInt('language_id')] = $Qpd->value('products_description');
      $pd_extra['products_url'][$Qpd->valueInt('language_id')] = $Qpd->value('products_url');
    }

    $pInfo = new objectInfo(array_merge($Qp->toArray(), $pd_extra));

    $products_image_name = $pInfo->products_image;
  } else {
    $pInfo = new objectInfo($_POST);

    if (!isset($_GET['read'])) {
// copy image only if modified
      if ($products_image = new upload('products_image', DIR_FS_CATALOG_IMAGES)) {
        $products_image_name = $products_image->filename;
      } else {
        $products_image_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
      }
    }
  }

  $languages = tep_get_languages();

  echo tep_draw_form('save_product', FILENAME_PRODUCTS, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=save_product', 'post', 'enctype="multipart/form-data"');
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr bgcolor="#fff3e7">
    <td>
<?php
  foreach ($languages as $l_entry) {
    echo '<span id="lang_' . $l_entry['code'] . '"' . (($l_entry['directory'] == $osC_Session->value('language')) ? ' class="highlight"' : '') . '><a href="javascript:toggleDivBlocks(\'pName_\', \'pName_' . $l_entry['code'] . '\'); toggleClass(\'lang_\', \'lang_' . $l_entry['code'] . '\', \'highlight\', \'span\');">' . tep_image(DIR_WS_CATALOG_LANGUAGES . $l_entry['directory'] . '/images/' . $l_entry['image'], $l_entry['name']) . '</a></span>&nbsp;&nbsp;';
  }
?>
    </td>
  </tr>
</table>

<?php
  foreach ($languages as $l_entry) {
?>

<div id="pName_<?php echo $l_entry['code']; ?>" <?php echo (($l_entry['directory'] != $osC_Session->value('language')) ? ' style="display: none;"' : ''); ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td><h1><?php echo $pInfo->products_name[$l_entry['id']]; ?></h1></td>
      <td align="right"><h1><?php echo $osC_Currencies->format($pInfo->products_price); ?></h1></td>
    </tr>
  </table>

  <p class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $pInfo->products_name[$l_entry['id']], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $pInfo->products_description[$l_entry['id']]; ?></p>

<?php
    if (!empty($pInfo->products_url[$l_entry['id']])) {
      echo '<p class="main">' . sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url[$l_entry['id']]) . '</p>';
    }
?>

<?php
    if ($pInfo->products_date_available > date('Y-m-d')) {
      echo '<p class="smallText" align="center">' . sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)) . '</p>';
    } else {
      echo '<p class="smallText" align="center">' . sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)) . '</p>';
    }
?>

</div>

<?php
  }

  if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
    echo '<p align="right"><input type="button" value="' . IMAGE_BACK . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '\';" class="operationButton"></p>';
  } else {
    echo '<p align="right">';

    foreach (osc_sanitize_multidimensional_array($_POST) as $key => $value) {
      echo osc_draw_hidden_field($key, $value);
    }

    echo osc_draw_hidden_field('products_image', $products_image_name);

    echo '<input type="submit" value="' . IMAGE_BACK . '" name="product_edit" class="operationButton"> <input type="submit" value="' . (isset($_GET['pID']) ? IMAGE_UPDATE : IMAGE_INSERT) . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '\';" class="operationButton">';

    echo '</p>';
  }
?>

</form>
