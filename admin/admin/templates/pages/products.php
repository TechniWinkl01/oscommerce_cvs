<?php
/*
  $Id: products.php,v 1.1 2004/08/27 22:13:15 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><h1><?php echo HEADING_TITLE; ?></h1></td>
    <td class="smallText" align="right">
<?php
  echo tep_draw_form('search', FILENAME_PRODUCTS, '', 'get') .
       HEADING_TITLE_SEARCH . ' ' . osc_draw_input_field('search') .
       '<input type="submit" value="GO" class="operationButton"></form><br>' .
       tep_draw_form('goto', FILENAME_PRODUCTS, '', 'get') .
       HEADING_TITLE_GOTO . ' ' . osc_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"') .
       '</form>';
?>
    </td>
  </tr>
</table>

<div id="infoBox_cDefault" <?php if (!empty($action)) { echo 'style="display: none;"'; } ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
    <thead>
      <tr>
        <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th><?php echo TABLE_HEADING_STATUS; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $Qproducts = $osC_Database->query('select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from :table_products p, :table_products_description pd, :table_products_to_categories p2c where p.products_id = pd.products_id and pd.language_id = :language_id and p.products_id = p2c.products_id and pd.products_name like :products_name order by pd.products_name');
    $Qproducts->bindValue(':products_name', '%' . $_GET['search'] . '%');
  } else {
    $Qproducts = $osC_Database->query('select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from :table_products p, :table_products_description pd where p.products_id = pd.products_id and pd.language_id = :language_id order by pd.products_name');
  }
  $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
  $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
  $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
  $Qproducts->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qproducts->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
  $Qproducts->execute();

  while ($Qproducts->next()) {
    if (isset($_GET['search']) && !empty($_GET['search'])) {
      $cPath = $Qproducts->valueInt('categories_id');
    }

    if (!isset($pInfo) && !isset($cInfo) && (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $Qproducts->valueInt('products_id')))) && ($action != 'cNew')) {
      $Qreviews = $osC_Database->query('select (avg(reviews_rating) / 5 * 100) as average_rating from :table_reviews where products_id = :products_id');
      $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreviews->bindInt(':products_id', $Qproducts->valueInt('products_id'));
      $Qreviews->execute();

      $pInfo = new objectInfo(array_merge($Qproducts->toArray(), $Qreviews->toArray()));
    }

    if (isset($pInfo) && ($Qproducts->valueInt('products_id') == $pInfo->products_id)) {
      echo '      <tr class="selected">' . "\n";
    } else {
      echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id')) . '\';">' . "\n";
    }
?>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '&nbsp;' . $Qproducts->value('products_name') . '</a>'; ?></td>
        <td align="center"><?php echo tep_image('templates/' . $template . '/images/icons/' . (($Qproducts->valueInt('products_status') === 1) ? 'checkbox_ticked.gif' : 'checkbox_crossed.gif')); ?></td>
        <td align="right">
<?php
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=new_product') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;';

    if (isset($pInfo) && ($Qproducts->valueInt('products_id') == $pInfo->products_id)) {
      echo '<a href="#" onClick="toggleInfoBox(\'pMove\');">' . tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'pCopyTo\');">' . tep_image('templates/' . $template . '/images/icons/16x16/copy.png', IMAGE_COPY_TO, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'pDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=pMove') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=pCopyTo') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/copy.png', IMAGE_COPY_TO, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=pDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
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
      <td class="smallText"><?php echo $Qproducts->displayBatchLinksTotal(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
      <td class="smallText" align="right"><?php echo $Qproducts->displayBatchLinksPullDown('page', 'cPath=' . $cPath); ?></td>
    </tr>
  </table>

  <p align="right"><?php echo '<input type="button" value="' . IMAGE_NEW_PRODUCT . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&action=new_product') . '\';" class="infoBoxButton">'; ?></p>
</div>

<?php
  if (isset($pInfo)) {
?>

<div id="infoBox_pCopyTo" <?php if ($action != 'pCopyTo') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/copy.png', IMAGE_COPY_TO, '16', '16') . ' ' . $pInfo->products_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('pCopyTo', FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to_confirm'); ?>

    <p><?php echo TEXT_INFO_COPY_TO_INTRO; ?></p>
    <p><?php echo TEXT_INFO_CURRENT_CATEGORIES . '<br>' . tep_output_generated_category_path($pInfo->products_id, 'product'); ?></p>
    <p><?php echo TEXT_CATEGORIES . '<br>' . osc_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id); ?></p>
    <p><?php echo TEXT_HOW_TO_COPY . '<br>' . osc_draw_radio_field('copy_as', array(array('id' => 'link', 'text' => TEXT_COPY_AS_LINK), array('id' => 'duplicate', 'text' => TEXT_COPY_AS_DUPLICATE)), 'link', '', false, '<br>'); ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_COPY . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<div id="infoBox_pMove" <?php if ($action != 'pMove') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . ' ' . $pInfo->products_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('pMove', FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product_confirm'); ?>

    <p><?php echo sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name); ?></p>
    <p><?php echo TEXT_INFO_CURRENT_CATEGORIES . '<br>' . tep_output_generated_category_path($pInfo->products_id, 'product'); ?></p>
    <p><?php echo sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . osc_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id); ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_MOVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<div id="infoBox_pDelete" <?php if ($action != 'pDelete') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . ' ' . $pInfo->products_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('pDelete', FILENAME_PRODUCTS, 'page=' . $_GET['page'] . '&cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product_confirm'); ?>

    <p><?php echo TEXT_DELETE_PRODUCT_INTRO; ?></p>
    <p><?php echo $pInfo->products_name; ?></p>
    <p>
<?php
    $product_categories_array = array();
    $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
    for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
      $category_path = '';
      for ($j=0, $k=sizeof($product_categories[$i]); $j<$k; $j++) {
        $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $category_path = substr($category_path, 0, -16);

      $product_categories_array[] = array('id' => $product_categories[$i][sizeof($product_categories[$i])-1]['id'], 'text' => $category_path);
    }

    echo osc_draw_checkbox_field('product_categories[]', $product_categories_array, true, '', false, '<br>');
?>
    </p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_DELETE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  }
?>
