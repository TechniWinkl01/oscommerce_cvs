<?php
/*
  $Id: languages.php,v 1.37 2004/10/28 18:59:49 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $selected_box = 'localization';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!isset($_GET['page']) || (isset($_GET['page']) && !is_numeric($_GET['page']))) {
    $_GET['page'] = 1;
  }

  if (!empty($action)) {
    switch ($action) {
      case 'save':
        $error = false;

        $osC_Database->startTransaction();

        if (isset($_GET['lID']) && is_numeric($_GET['lID'])) {
          $Qlanguage = $osC_Database->query('update :table_languages set name = :name, code = :code, image = :image, directory = :directory, sort_order = :sort_order where languages_id = :languages_id');
          $Qlanguage->bindInt(':languages_id', $_GET['lID']);
        } else {
          $Qlanguage = $osC_Database->query('insert into :table_languages (name, code, image, directory, sort_order) values (:name, :code, :image, :directory, :sort_order)');
        }
        $Qlanguage->bindTable(':table_languages', TABLE_LANGUAGES);
        $Qlanguage->bindValue(':name', $_POST['name']);
        $Qlanguage->bindValue(':code', $_POST['code']);
        $Qlanguage->bindValue(':image', $_POST['image']);
        $Qlanguage->bindValue(':directory', $_POST['directory']);
        $Qlanguage->bindInt(':sort_order', $_POST['sort_order']);
        $Qlanguage->execute();

        if ($osC_Database->isError() === false) {
          if (isset($_GET['lID']) && is_numeric($_GET['lID'])) {
            $language_id = $_GET['lID'];
          } else {
            $language_id = $osC_Database->nextID();

            include('../includes/classes/language.php');
            $osC_Language = new language();

// create additional categories_description records
            $Qcategories = $osC_Database->query('select categories_id, categories_name from :table_categories_description where language_id = :language_id');
            $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
            $Qcategories->bindInt(':language_id', $osC_Language->catalog_languages[DEFAULT_LANGUAGE]['id']);
            $Qcategories->execute();

            while ($Qcategories->next()) {
              $Qinsert = $osC_Database->query('insert into :table_categories_description (categories_id, language_id, categories_name) values (:categories_id, :language_id, :categories_name)');
              $Qinsert->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
              $Qinsert->bindInt(':categories_id', $Qcategories->valueInt('categories_id'));
              $Qinsert->bindInt(':language_id', $language_id);
              $Qinsert->bindValue(':categories_name', $Qcategories->value('categories_name'));
              $Qinsert->execute();

              if ($osC_Database->isError()) {
                $error = true;
                break;
              }
            }

            if ($error === false) {
// create additional products_description records
              $Qproducts = $osC_Database->query('select products_id, products_name, products_description, products_url from :table_products_description where language_id = :language_id');
              $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
              $Qproducts->bindInt(':language_id', $osC_Language->catalog_languages[DEFAULT_LANGUAGE]['id']);
              $Qproducts->execute();

              while ($Qproducts->next()) {
                $Qinsert = $osC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_url) values (:products_id, :language_id, :products_name, :products_description, :products_url)');
                $Qinsert->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
                $Qinsert->bindInt(':products_id', $Qproducts->valueInt('products_id'));
                $Qinsert->bindInt(':language_id', $language_id);
                $Qinsert->bindInt(':products_name', $Qproducts->value('products_name'));
                $Qinsert->bindInt(':products_description', $Qproducts->value('products_description'));
                $Qinsert->bindInt(':products_url', $Qproducts->value('products_url'));
                $Qinsert->execute();

                if ($osC_Database->isError()) {
                  $error = true;
                  break;
                }
              }
            }

            if ($error === false) {
// create additional products_options records
              $Qoptions = $osC_Database->query('select products_options_id, products_options_name from :table_products_options where language_id = :language_id');
              $Qoptions->bindTable(':table_products_options', TABLE_PRODUCTS_OPTIONS);
              $Qoptions->bindInt(':language_id', $osC_Language->catalog_languages[DEFAULT_LANGUAGE]['id']);
              $Qoptions->execute();

              while ($Qoptions->next()) {
                $Qinsert = $osC_Database->query('insert into :table_products_options (products_options_id, language_id, products_options_name) values (:products_options_id, :language_id, :products_options_name)');
                $Qinsert->bindTable(':table_products_options', TABLE_PRODUCTS_OPTIONS);
                $Qinsert->bindInt(':products_options_id', $Qoptions->valueInt('products_options_id'));
                $Qinsert->bindInt(':language_id', $language_id);
                $Qinsert->bindValue(':products_options_name', $Qoptions->value('products_options_name'));
                $Qinsert->execute();

                if ($osC_Database->isError()) {
                  $error = true;
                  break;
                }
              }
            }

            if ($error === false) {
// create additional products_options_values records
              $Qvalues = $osC_Database->query('select products_options_values_id, products_options_values_name from :table_products_options_values where language_id = :language_id');
              $Qvalues->bindTable(':table_products_options_values', TABLE_PRODUCTS_OPTIONS_VALUES);
              $Qvalues->bindInt(':language_id', $osC_Language->catalog_languages[DEFAULT_LANGUAGE]['id']);
              $Qvalues->execute();

              while ($Qvalues->next()) {
                $Qinsert = $osC_Database->query('insert into :table_products_options_values (products_options_values_id, language_id, products_options_values_name) values (:products_options_values_id, :language_id, :products_options_values_name)');
                $Qinsert->bindTable(':table_products_options_values', TABLE_PRODUCTS_OPTIONS_VALUES);
                $Qinsert->bindInt(':products_options_values_id', $Qvalues->valueInt('products_options_values_id'));
                $Qinsert->bindInt(':language_id', $language_id);
                $Qinsert->bindValue(':products_options_values_name', $Qvalues->value('products_options_values_name'));
                $Qinsert->execute();

                if ($osC_Database->isError()) {
                  $error = true;
                  break;
                }
              }
            }

            if ($error === false) {
// create additional manufacturers_info records
              $Qmanufacturers = $osC_Database->query('select manufacturers_id, manufacturers_url from :table_manufacturers_info where languages_id = :languages_id');
              $Qmanufacturers->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
              $Qmanufacturers->bindInt(':languages_id', $osC_Language->catalog_languages[DEFAULT_LANGUAGE]['id']);
              $Qmanufacturers->execute();

              while ($Qmanufacturers->next()) {
                $Qinsert = $osC_Database->query('insert into :table_manufacturers_info (manufacturers_id, languages_id, manufacturers_url) values (:manufacturers_id, :languages_id, :manufacturers_url)');
                $Qinsert->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
                $Qinsert->bindInt(':manufacturers_id', $Qmanufacturers->valueInt('manufacturers_id'));
                $Qinsert->bindInt(':languages_id', $language_id);
                $Qinsert->bindValue(':manufacturers_url', $Qmanufacturers->value('manufacturers_url'));
                $Qinsert->execute();

                if ($osC_Database->isError()) {
                  $error = true;
                  break;
                }
              }
            }

            if ($error === false) {
// create additional orders_status records
              $Qstatus = $osC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id');
              $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
              $Qstatus->bindInt(':language_id', $osC_Language->catalog_languages[DEFAULT_LANGUAGE]['id']);
              $Qstatus->execute();

              while ($Qstatus->next()) {
                $Qinsert = $osC_Database->query('insert into :table_orders_status (orders_status_id, language_id, orders_status_name) values (:orders_status_id, :language_id, :orders_status_name)');
                $Qinsert->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
                $Qinsert->bindInt(':orders_status_id', $Qstatus->valueInt('orders_status_id'));
                $Qinsert->bindInt(':language_id', $language_id);
                $Qinsert->bindValue(':orders_status_name', $Qstatus->value('orders_status_name'));
                $Qinsert->execute();

                if ($osC_Database->isError()) {
                  $error = true;
                  break;
                }
              }
            }
          }
        } else {
          $error = true;
        }

        if ($error === false) {
          if ( (isset($_POST['default']) && ($_POST['default'] == 'on')) || (isset($_POST['is_default']) && ($_POST['is_default'] == 'true') && ($_POST['code'] != DEFAULT_LANGUAGE)) ) {
            $Qupdate = $osC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
            $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
            $Qupdate->bindValue(':configuration_value', $_POST['code']);
            $Qupdate->bindValue(':configuration_key', 'DEFAULT_LANGUAGE');
            $Qupdate->execute();

            if ($osC_Database->isError() === false) {
              if ($Qupdate->affectedRows()) {
                osC_Cache::clear('configuration');
              }
            } else {
              $error = true;
            }
          }
        }

        if ($error === false) {
          $osC_Database->commitTransaction();

          $osC_MessageStack->add_session('header', SUCCESS_DB_ROWS_UPDATED, 'success');
        } else {
          $osC_Database->rollbackTransaction();

          $osC_MessageStack->add_session('header', ERROR_DB_ROWS_NOT_UPDATED, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $language_id));
        break;
      case 'deleteconfirm':
        if (isset($_GET['lID']) && is_numeric($_GET['lID'])) {
          $Qcheck = $osC_Database->query('select code from :table_languages where languages_id = :languages_id');
          $Qcheck->bindTable(':table_languages', TABLE_LANGUAGES);
          $Qcheck->bindInt(':languages_id', $_GET['lID']);
          $Qcheck->execute();

          if ($Qcheck->value('code') != DEFAULT_LANGUAGE) {
            $error = false;

            $osC_Database->startTransaction();

            $Qcategories = $osC_Database->query('delete from :table_categories_description where language_id = :language_id');
            $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
            $Qcategories->bindInt(':language_id', $_GET['lID']);
            $Qcategories->execute();

            if ($osC_Database->isError()) {
              $error = true;
            }

            if ($error === false) {
              $Qproducts = $osC_Database->query('delete from :table_products_description where language_id = :language_id');
              $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
              $Qproducts->bindInt(':language_id', $_GET['lID']);
              $Qproducts->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }

            if ($error === false) {
              $Qproducts = $osC_Database->query('delete from :table_products_options where language_id = :language_id');
              $Qproducts->bindTable(':table_products_options', TABLE_PRODUCTS_OPTIONS);
              $Qproducts->bindInt(':language_id', $_GET['lID']);
              $Qproducts->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }

            if ($error === false) {
              $Qproducts = $osC_Database->query('delete from :table_products_options_values where language_id = :language_id');
              $Qproducts->bindTable(':table_products_options_values', TABLE_PRODUCTS_OPTIONS_VALUES);
              $Qproducts->bindInt(':language_id', $_GET['lID']);
              $Qproducts->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }

            if ($error === false) {
              $Qmanufacturers = $osC_Database->query('delete from :table_manufacturers_info where languages_id = :languages_id');
              $Qmanufacturers->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
              $Qmanufacturers->bindInt(':languages_id', $_GET['lID']);
              $Qmanufacturers->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }

            if ($error === false) {
              $Qstatus = $osC_Database->query('delete from :table_orders_status where language_id = :language_id');
              $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
              $Qstatus->bindInt(':language_id', $_GET['lID']);
              $Qstatus->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }

            if ($error === false) {
              $Qlanguages = $osC_Database->query('delete from :table_languages where languages_id = :languages_id');
              $Qlanguages->bindTable(':table_languages', TABLE_LANGUAGES);
              $Qlanguages->bindInt(':languages_id', $_GET['lID']);
              $Qlanguages->execute();

              if ($osC_Database->isError()) {
                $error = true;
              }
            }

            if ($error === false) {
              $osC_Database->commitTransaction();

              $osC_MessageStack->add_session('header', SUCCESS_DB_ROWS_UPDATED, 'success');
            } else {
              $osC_Database->rollbackTransaction();

              $osC_MessageStack->add_session('header', ERROR_DB_ROWS_NOT_UPDATED, 'error');
            }
          }
        }

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page']));
        break;
    }
  }

  $page_contents = 'languages.php';

  require('templates/default.php');

  require('includes/application_bottom.php');
?>
