<?php
/*
  $Id: categories.php,v 1.2 2004/08/04 16:53:50 hpdl Exp $

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
  echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get') .
       HEADING_TITLE_SEARCH . ' ' . osc_draw_input_field('search') .
       '<input type="submit" value="GO" class="operationButton"></form><br>' .
       tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get') .
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
        <th><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
        <th><?php echo TABLE_HEADING_STATUS; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $Qcategories = $osC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id and cd.categories_name like :categories_name order by c.sort_order, cd.categories_name');
    $Qcategories->bindValue(':categories_name', '%' . $_GET['search'] . '%');
  } else {
    $Qcategories = $osC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by c.sort_order, cd.categories_name');
    $Qcategories->bindInt(':parent_id', $current_category_id);
  }
  $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
  $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
  $Qcategories->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qcategories->execute();

  while ($Qcategories->next()) {
  if (isset($_GET['search']) && !empty($_GET['search'])) {
      $cPath = $Qcategories->valueInt('parent_id');
    }

    if (!isset($cInfo) && (!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $Qcategories->valueInt('categories_id')))) && ($action != 'cNew')) {
      $cInfo_extra = array('childs_count' => tep_childs_in_category_count($Qcategories->valueInt('categories_id')),
                           'products_count' => tep_products_in_category_count($Qcategories->valueInt('categories_id')));

      $cInfo = new objectInfo(array_merge($Qcategories->toArray(), $cInfo_extra));
    }

    if (isset($cInfo) && ($Qcategories->valueInt('categories_id') == $cInfo->categories_id)) {
      echo '      <tr class="selected">' . "\n";
    } else {
      echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $Qcategories->valueInt('categories_id')) . '\';">' . "\n";
    }
?>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($Qcategories->valueInt('categories_id'))) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '&nbsp;<b>' . $Qcategories->value('categories_name') . '</b></a>'; ?></td>
        <td align="center"><?php echo tep_image('templates/' . $template . '/images/icons/checkbox_ticked.gif'); ?></td>
        <td align="right">
<?php
    if (isset($cInfo) && ($Qcategories->valueInt('categories_id') == $cInfo->categories_id)) {
      echo '<a href="#" onClick="toggleInfoBox(\'cEdit\');">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'cMove\');">' . tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'cDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $Qcategories->valueInt('categories_id') . '&action=cEdit') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $Qcategories->valueInt('categories_id') . '&action=cMove') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . '&nbsp;</a>' .
           '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $Qcategories->valueInt('categories_id') . '&action=cDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    }
?>
        </td>
      </tr>
<?php
  }

  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $Qproducts = $osC_Database->query('select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from :table_products p, :table_products_description pd, :table_products_to_categories p2c where p.products_id = pd.products_id and pd.language_id = :language_id and p.products_id = p2c.products_id and pd.products_name like :products_name order by pd.products_name');
    $Qproducts->bindValue(':products_name', '%' . $_GET['search'] . '%');
  } else {
    $Qproducts = $osC_Database->query('select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from :table_products p, :table_products_description pd, :table_products_to_categories p2c where p.products_id = pd.products_id and pd.language_id = :language_id and p.products_id = p2c.products_id and p2c.categories_id = :categories_id order by pd.products_name');
    $Qproducts->bindInt(':categories_id', $current_category_id);
  }
  $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
  $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
  $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
  $Qproducts->bindInt(':language_id', $osC_Session->value('languages_id'));
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
      echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id')) . '\';">' . "\n";
    }
?>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '&nbsp;' . $Qproducts->value('products_name') . '</a>'; ?></td>
        <td align="center"><?php echo tep_image('templates/' . $template . '/images/icons/' . (($Qproducts->valueInt('products_status') === 1) ? 'checkbox_ticked.gif' : 'checkbox_crossed.gif')); ?></td>
        <td align="right">
<?php
    echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=new_product') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>';

    if (isset($pInfo) && ($Qproducts->valueInt('products_id') == $pInfo->products_id)) {
      echo '<a href="#" onClick="toggleInfoBox(\'pMove\');">' . tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'pCopyTo\');">' . tep_image('templates/' . $template . '/images/icons/16x16/copy.png', IMAGE_COPY_TO, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'pDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=pMove') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=pCopyTo') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/copy.png', IMAGE_COPY_TO, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $Qproducts->valueInt('products_id') . '&action=pDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    }
?>
        </td>
      </tr>
<?php
  }
?>
    </tbody>
  </table>

  <p align="right">
<?php
  if (sizeof($cPath_array) > 0) {
    echo '<input type="button" value="' . IMAGE_BACK . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . implode('_', array_slice($cPath_array, 0, -1)) . '&cID=' . $current_category_id) . '\';" class="infoBoxButton"> ';
  }

  if (!isset($_GET['search'])) {
    if ($action != 'cNew') {
      echo '<input type="button" value="' . IMAGE_NEW_CATEGORY . '" onClick="toggleInfoBox(\'cNew\');" class="infoBoxButton"> ';
    }

    echo '<input type="button" value="' . IMAGE_NEW_PRODUCT . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '\';" class="infoBoxButton">';
  }
?>
  </p>
</div>

<div id="infoBox_cNew" <?php if ($action != 'cNew') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/new.png', IMAGE_INSERT, '16', '16') . ' ' . TEXT_INFO_HEADING_NEW_CATEGORY; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('cNew', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=save_category', 'post', 'enctype="multipart/form-data"'); ?>

    <p><?php echo TEXT_NEW_CATEGORY_INTRO; ?></p>
    <p>
<?php
  echo TEXT_CATEGORIES_NAME;

  $languages = tep_get_languages();
  foreach ($languages as $l_entry) {
    echo '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $l_entry['directory'] . '/images/' . $l_entry['image'], $l_entry['name']) . '&nbsp;' . osc_draw_input_field('categories_name[' . $l_entry['id'] . ']');
  }
?>
    </p>
    <p><?php echo TEXT_CATEGORIES_IMAGE . '<br>' . osc_draw_file_field('categories_image'); ?></p>
    <p><?php echo TEXT_EDIT_SORT_ORDER . '<br>' . osc_draw_input_field('sort_order'); ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  if (isset($cInfo)) {
?>
<div id="infoBox_cMove" <?php if ($action != 'cMove') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/move.png', IMAGE_MOVE, '16', '16') . ' ' . $cInfo->categories_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('cMove', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category_confirm'); ?>

    <p><?php echo sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name); ?></p>
    <p><?php echo sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . osc_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id); ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_MOVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<div id="infoBox_cDelete" <?php if ($action != 'cDelete') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . ' ' . $cInfo->categories_name; ?></div>
  <div class="infoBoxContent">
    <p><?php echo TEXT_DELETE_CATEGORY_INTRO; ?></p>
    <p><?php echo '<b>' . $cInfo->categories_name . '</b>'; ?></p>
<?php
    if ($cInfo->childs_count > 0) {
      echo '    <p>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count) . '</p>';
    }

    if ($cInfo->products_count > 0) {
      echo '    <p>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count) . '</p>';
    }
?>
    <p align="center"><?php echo '<input type="button" value="' . IMAGE_DELETE . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category_confirm') . '\'" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>
  </div>
</div>

<div id="infoBox_cEdit" <?php if ($action != 'cEdit') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . ' ' . $cInfo->categories_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('cEdit', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=save_category', 'post', 'enctype="multipart/form-data"'); ?>

    <p><?php echo TEXT_EDIT_INTRO; ?></p>
    <p>
<?php
    echo TEXT_EDIT_CATEGORIES_NAME;

    $Qcd = $osC_Database->query('select language_id, categories_name from :table_categories_description where categories_id = :categories_id');
    $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcd->bindInt(':categories_id', $cInfo->categories_id);
    $Qcd->execute();

    $categories_name = array();
    while ($Qcd->next()) {
      $categories_name[$Qcd->valueInt('language_id')] = $Qcd->value('categories_name');
    }

    $languages = tep_get_languages();
    foreach ($languages as $l_entry) {
      echo '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $l_entry['directory'] . '/images/' . $l_entry['image'], $l_entry['name']) . '&nbsp;' . osc_draw_input_field('categories_name[' . $l_entry['id'] . ']', (isset($categories_name[$l_entry['id']]) ? $categories_name[$l_entry['id']] : ''));
    }
?>
    </p>
    <p><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>'; ?></p>
    <p><?php echo TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . osc_draw_file_field('categories_image'); ?></p>
    <p><?php echo TEXT_EDIT_SORT_ORDER . '<br>' . osc_draw_input_field('sort_order', $cInfo->sort_order); ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_UPDATE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'cDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  } elseif (isset($pInfo)) {
?>

<div id="infoBox_pCopyTo" <?php if ($action != 'pCopyTo') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/copy.png', IMAGE_COPY_TO, '16', '16') . ' ' . $pInfo->products_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('pCopyTo', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to_confirm'); ?>

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
    <?php echo tep_draw_form('pMove', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product_confirm'); ?>

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
    <?php echo tep_draw_form('pDelete', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product_confirm'); ?>

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
