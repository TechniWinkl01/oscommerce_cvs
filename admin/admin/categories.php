<?php
/*
  $Id: categories.php,v 1.153 2004/08/18 11:49:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $selected_box = 'catalog';

// calculate category path
  $cPath = (isset($_GET['cPath']) ? $_GET['cPath'] : '');

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'save_category':
        $category_id = '';
        $error = false;

        $osC_Database->startTransaction();

        if (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
          $Qcat = $osC_Database->query('update :table_categories set sort_order = :sort_order, last_modified = now() where categories_id = :categories_id');
          $Qcat->bindInt(':categories_id', $_GET['cID']);
        } else {
          $Qcat = $osC_Database->query('insert into :table_categories (parent_id, sort_order, date_added) values (:parent_id, :sort_order, now())');
          $Qcat->bindInt(':parent_id', $current_category_id);
        }
        $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qcat->bindInt(':sort_order', $_POST['sort_order']);
        $Qcat->execute();

        if ($osC_Database->isError() === false) {
          $category_id = (isset($_GET['cID']) && is_numeric($_GET['cID'])) ? $_GET['cID'] : $osC_Database->nextID();

          $languages = tep_get_languages();

          foreach ($languages as $l_entry) {
            if (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
              $Qcd = $osC_Database->query('update :table_categories_description set categories_name = :categories_name where categories_id = :categories_id and language_id = :language_id');
            } else {
              $Qcd = $osC_Database->query('insert into :table_categories_description (categories_id, language_id, categories_name) values (:categories_id, :language_id, :categories_name)');
            }
            $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
            $Qcd->bindInt(':categories_id', $category_id);
            $Qcd->bindInt(':language_id', $l_entry['id']);
            $Qcd->bindValue(':categories_name', $_POST['categories_name'][$l_entry['id']]);
            $Qcd->execute();

            if ($osC_Database->isError()) {
              $error = true;
              break;
            }
          }

          if (($error === false) && ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES))) {
            $Qcf = $osC_Database->query('update :table_categories set categories_image = :categories_image where categories_id = :categories_id');
            $Qcf->bindTable(':table_categories', TABLE_CATEGORIES);
            $Qcf->bindValue(':categories_image', $categories_image->filename);
            $Qcf->bindInt(':categories_id', $category_id);
            $Qcf->execute();

            if ($osC_Database->isError()) {
              $error = true;
            }
          }
        } else {
          $error = true;
        }

        if ($error === false) {
          $osC_Database->commitTransaction();

          osC_Cache::reset('categories');
          osC_Cache::reset('also_purchased');

          $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');
        } else {
          $osC_Database->rollbackTransaction();

          $messageStack->add_session(ERROR_DB_ROWS_NOT_UPDATED, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $category_id));
        break;
      case 'delete_category_confirm':
        if (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
          $categories = tep_get_category_tree($_GET['cID'], '', '0', '', true);
          $products = array();
          $products_delete = array();

          foreach ($categories as $c_entry) {
            $Qproducts = $osC_Database->query('select products_id from :table_products_to_categories where categories_id = :categories_id');
            $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qproducts->bindInt(':categories_id', $c_entry['id']);
            $Qproducts->execute();

            while ($Qproducts->next()) {
              $products[$Qproducts->valueInt('products_id')]['categories'][] = $c_entry['id'];
            }
          }

          foreach ($products as $key => $value) {
            $Qcheck = $osC_Database->query('select count(*) as total from :table_products_to_categories where products_id = :products_id and categories_id not in :categories_id');
            $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qcheck->bindInt(':products_id', $key);
            $Qcheck->bindRaw(':categories_id', '("' . implode('", "', $value['categories']) . '")');
            $Qcheck->execute();

            if ($Qcheck->valueInt('total') < 1) {
              $products_delete[$key] = $key;
            }
          }

          tep_set_time_limit(0);

          foreach ($categories as $c_entry) {
            tep_remove_category($c_entry['id']);
          }

          foreach ($products_delete as $key => $value) {
            tep_remove_product($key);
          }

          osC_Cache::reset('categories');
          osC_Cache::reset('also_purchased');

          $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($_GET['pID']) && isset($_POST['product_categories']) && is_array($_POST['product_categories']) && !empty($_POST['product_categories'])) {
          $Qpc = $osC_Database->query('delete from :table_products_to_categories where products_id = :products_id and categories_id in :categories_id');
          $Qpc->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
          $Qpc->bindInt(':products_id', $_GET['pID']);
          $Qpc->bindRaw(':categories_id', '("' . implode('", "', $_POST['product_categories']) . '")');
          $Qpc->execute();

          if ($osC_Database->isError() === false) {
            $Qcheck = $osC_Database->query('select count(*) as total from :table_products_to_categories where products_id = :products_id');
            $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qcheck->bindInt(':products_id', $_GET['pID']);
            $Qcheck->execute();

            if ($Qcheck->valueInt('total') < 1) {
              tep_remove_product($_GET['pID']);
            }

            osC_Cache::reset('categories');
            osC_Cache::reset('also_purchased');

            $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($_GET['cID']) && ($_GET['cID'] != $_POST['move_to_category_id'])) {
          $path = explode('_', tep_get_generated_category_path_ids($_POST['move_to_category_id']));

          if (in_array($_GET['cID'], $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');
          } else {
            $Qupdate = $osC_Database->query('update :table_categories set parent_id = :parent_id, last_modified = now() where categories_id = :categories_id');
            $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
            $Qupdate->bindInt(':parent_id', $_POST['move_to_category_id']);
            $Qupdate->bindInt(':categories_id', $_GET['cID']);
            $Qupdate->execute();

            if ($Qupdate->affectedRows()) {
              osC_Cache::reset('categories');
              osC_Cache::reset('also_purchased');

              $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');

              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_POST['move_to_category_id'] . '&cID=' . $_GET['cID']));
            }
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID']));
        break;
      case 'move_product_confirm':
        if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
          $Qcheck = $osC_Database->query('select count(*) as total from :table_products_to_categories where products_id = :products_id and categories_id = :categories_id');
          $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
          $Qcheck->bindInt(':products_id', $_GET['pID']);
          $Qcheck->bindInt(':categories_id', $_POST['move_to_category_id']);
          $Qcheck->execute();

          if ($Qcheck->valueInt('total') < 1) {
            $Qupdate = $osC_Database->query('update :table_products_to_categories set categories_id = :categories_id where products_id = :products_id and categories_id = :current_categories_id');
            $Qupdate->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qupdate->bindInt(':categories_id', $_POST['move_to_category_id']);
            $Qupdate->bindInt(':products_id', $_GET['pID']);
            $Qupdate->bindInt(':current_categories_id', $current_category_id);
            $Qupdate->execute();

            if ($Qupdate->affectedRows()) {
              osC_Cache::reset('categories');
              osC_Cache::reset('also_purchased');

              $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');

              tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_POST['move_to_category_id'] . '&pID=' . $_GET['pID']));
            } else {
              $messageStack->add_session(WARNING_DB_ROWS_NOT_UPDATED, 'warning');
            }
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID']));
        break;
      case 'save_product':
        if (isset($_POST['product_edit'])) {
          $action = 'new_product';
        } else {
          $error = false;

          $osC_Database->startTransaction();

          if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
            $Qproduct = $osC_Database->query('update :table_products set products_quantity = :products_quantity, products_model = :products_model, products_price = :products_price, products_date_available = :products_date_available, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id, manufacturers_id = :manufacturers_id, products_last_modified = now() where products_id = :products_id');
            $Qproduct->bindInt(':products_id', $_GET['pID']);
          } else {
            $Qproduct = $osC_Database->query('insert into :table_products (products_quantity, products_model, products_price, products_date_available, products_weight, products_weight_class, products_status, products_tax_class_id, manufacturers_id, products_date_added) values (:products_quantity, :products_model, :products_price, :products_date_available, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :manufacturers_id, now())');
          }
          $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
          $Qproduct->bindInt(':products_quantity', $_POST['products_quantity']);
          $Qproduct->bindValue(':products_model', $_POST['products_model']);
          $Qproduct->bindValue(':products_price', $_POST['products_price']);
          if (date('Y-m-d') < $_POST['products_date_available']) {
            $Qproduct->bindValue(':products_date_available', $_POST['products_date_available']);
          } else {
            $Qproduct->bindRaw(':products_date_available', 'null');
          }
          $Qproduct->bindValue(':products_weight', $_POST['products_weight']);
          $Qproduct->bindInt(':products_weight_class', $_POST['products_weight_class']);
          $Qproduct->bindInt(':products_status', $_POST['products_status']);
          $Qproduct->bindInt(':products_tax_class_id', $_POST['products_tax_class_id']);
          $Qproduct->bindInt(':manufacturers_id', $_POST['manufacturers_id']);
          $Qproduct->execute();

          if ($osC_Database->isError()) {
            $error = true;
          } else {
            if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
              $products_id = $_GET['pID'];
            } else {
              $products_id = $osC_Database->nextID();

              $Qp2c = $osC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id');
              $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
              $Qp2c->bindInt(':products_id', $products_id);
              $Qp2c->bindInt(':categories_id', $current_category_id);
              $Qp2c->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }
          }

          if ($error === false) {
            if (isset($_POST['products_image']) && !empty($_POST['products_image']) && ($_POST['products_image'] != 'none')) {
              $Qimage =  $osC_Database->query('update :table_products set products_image = :products_image where products_id = :products_id');
              $Qimage->bindTable(':table_products', TABLE_PRODUCTS);
              $Qimage->bindValue(':products_image', $_POST['products_image']);
              $Qimage->bindInt(':products_id', $products_id);
              $Qimage->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }
          }

          if ($error === false) {
            $languages = tep_get_languages();
            foreach ($languages as $l_entry) {
              if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
                $Qpd = $osC_Database->query('update :table_products_description set products_name = :products_name, products_description = :products_description, products_url = :products_url where products_id = :products_id and language_id = :language_id');
              } else {
                $Qpd = $osC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_url) values (:products_id, :language_id, :products_name, :products_description, :products_url)');
              }
              $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
              $Qpd->bindInt(':products_id', $products_id);
              $Qpd->bindInt(':language_id', $l_entry['id']);
              $Qpd->bindValue(':products_name', $_POST['products_name'][$l_entry['id']]);
              $Qpd->bindValue(':products_description', $_POST['products_description'][$l_entry['id']]);
              $Qpd->bindValue(':products_url', $_POST['products_url'][$l_entry['id']]);
              $Qpd->execute();

              if ($osC_Database->isError()) {
                $error = true;
                break;
              }
            }
          }

          if ($error === false) {
            $osC_Database->commitTransaction();

            osC_Cache::reset('categories');
            osC_Cache::reset('also_purchased');

            $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');
          } else {
            $osC_Database->rollbackTransaction();

            $messageStack->add_session(ERROR_DB_ROWS_NOT_UPDATED, 'error');
          }

          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
        break;
      case 'copy_to_confirm':
        if (isset($_GET['pID']) && isset($_POST['categories_id'])) {
          if ($_POST['copy_as'] == 'link') {
            if ($_POST['categories_id'] != $current_category_id) {
              $Qcheck = $osC_Database->query('select count(*) as total from :table_products_to_categories where products_id = :products_id and categories_id = :categories_id');
              $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
              $Qcheck->bindInt(':products_id', $_GET['pID']);
              $Qcheck->bindInt(':categories_id', $_POST['categories_id']);
              $Qcheck->execute();

              if ($Qcheck->valueInt('total') < 1) {
                $Qcat = $osC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
                $Qcat->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
                $Qcat->bindInt(':products_id', $_GET['pID']);
                $Qcat->bindInt(':categories_id', $_POST['categories_id']);
                $Qcat->execute();

                if ($Qcat->affectedRows()) {
                  $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');

                  tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_POST['categories_id'] . '&pID=' . $_GET['pID']));
                }
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($_POST['copy_as'] == 'duplicate') {
            $Qproduct = $osC_Database->query('select products_quantity, products_model, products_image, products_price, products_date_available, products_weight, products_tax_class_id, manufacturers_id from :table_products where products_id = :products_id');
            $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
            $Qproduct->bindInt(':products_id', $_GET['pID']);
            $Qproduct->execute();

            if ($Qproduct->numberOfRows() === 1) {
              $error = false;

              $osC_Database->startTransaction();

              $Qnew = $osC_Database->query('insert into :table_products (products_quantity, products_model, products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values (:products_quantity, :products_model, :products_image, :products_price, now(), :products_date_available, :products_weight, 0, :products_tax_class_id, :manufacturers_id)');
              $Qnew->bindTable(':table_products', TABLE_PRODUCTS);
              $Qnew->bindInt(':products_quantity', $Qproduct->valueInt('products_quantity'));
              $Qnew->bindValue(':products_model', $Qproduct->value('products_model'));
              $Qnew->bindValue(':products_image', $Qproduct->value('products_image'));
              $Qnew->bindValue(':products_price', $Qproduct->value('products_price'));
              $Qnew->bindValue(':products_date_available', $Qproduct->value('products_date_available'));
              $Qnew->bindValue(':products_weight', $Qproduct->value('products_weight'));
              $Qnew->bindInt(':products_tax_class_id', $Qproduct->valueInt('products_tax_class_id'));
              $Qnew->bindInt(':manufacturers_id', $Qproduct->valueInt('manufacturers_id'));
              $Qnew->execute();

              if ($Qnew->affectedRows()) {
                $new_product_id = $osC_Database->nextID();

                $Qdesc = $osC_Database->query('select language_id, products_name, products_description, products_url from :table_products_description where products_id = :products_id');
                $Qdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
                $Qdesc->bindInt(':products_id', $_GET['pID']);
                $Qdesc->execute();

                while ($Qdesc->next()) {
                  $Qnewdesc = $osC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_url, products_viewed) values (:products_id, :language_id, :products_name, :products_description, :products_url, 0)');
                  $Qnewdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
                  $Qnewdesc->bindInt(':products_id', $new_product_id);
                  $Qnewdesc->bindInt(':language_id', $Qdesc->valueInt('language_id'));
                  $Qnewdesc->bindValue(':products_name', $Qdesc->value('products_name'));
                  $Qnewdesc->bindValue(':products_description', $Qdesc->value('products_description'));
                  $Qnewdesc->bindValue(':products_url', $Qdesc->value('products_url'));
                  $Qnewdesc->execute();

                  if ($osC_Database->isError()) {
                    $error = true;

                    break;
                  }
                }

                if ($error === false) {
                  $Qp2c = $osC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
                  $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
                  $Qp2c->bindInt(':products_id', $new_product_id);
                  $Qp2c->bindInt(':categories_id', $_POST['categories_id']);
                  $Qp2c->execute();

                  if ($osC_Database->isError()) {
                    $error = true;
                  }
                }
              } else {
                $error = true;
              }

              if ($error === false) {
                $osC_Database->commitTransaction();

                osC_Cache::reset('categories');
                osC_Cache::reset('also_purchased');

                $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');

                tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_POST['categories_id'] . '&pID=' . $new_product_id));
              } else {
                $osC_Database->rollbackTransaction();

                $messageStack->add_session(ERROR_DB_ROWS_NOT_UPDATED, 'error');
              }
            }
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID']));
        break;
    }
  }

  require('../includes/classes/currencies.php');
  $osC_Currencies = new osC_Currencies();

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) {
      $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

  switch ($action) {
    case 'new_product': $page_contents = 'categories_new_product.php'; break;
    case 'new_product_preview': $page_contents = 'categories_new_product_preview.php'; break;
    default: $page_contents = 'categories.php';
  }

  require('templates/default.php');

  require('includes/application_bottom.php');
?>
