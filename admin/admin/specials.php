<?php
/*
  $Id: specials.php,v 1.43 2004/10/28 18:59:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $selected_box = 'catalog';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!isset($_GET['page']) || (isset($_GET['page']) && !is_numeric($_GET['page']))) {
    $_GET['page'] = 1;
  }

  if (!empty($action)) {
    switch ($action) {
      case 'save':
        $Qproduct = $osC_Database->query('select products_price from :table_products where products_id = :products_id');
        $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproduct->bindInt(':products_id', $_POST['products_id']);
        $Qproduct->execute();

        if ($Qproduct->numberOfRows() === 1) {
          $specials_price = $_POST['specials_price'];

          if (substr($specials_price, -1) == '%') {
            $specials_price = $Qproduct->valueDecimal('products_price') - (((double)$specials_price / 100) * $Qproduct->valueDecimal('products_price'));
          }

          if (isset($_GET['sID']) && is_numeric($_GET['sID'])) {
            $Qspecial = $osC_Database->query('update :table_specials set specials_new_products_price = :specials_new_products_price, specials_last_modified = now(), expires_date = :expires_date, status = :status where specials_id = :specials_id');
            $Qspecial->bindInt(':specials_id', $_GET['sID']);
          } else {
            $Qspecial = $osC_Database->query('insert into :table_specials (products_id, specials_new_products_price, specials_date_added, expires_date, status) values (:products_id, :specials_new_products_price, now(), :expires_date, :status)');
            $Qspecial->bindInt(':products_id', $_POST['products_id']);
          }
          $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
          $Qspecial->bindValue(':specials_new_products_price', $specials_price);
          $Qspecial->bindValue(':expires_date', $_POST['specials_expires_date']);
          $Qspecial->bindInt(':status', (isset($_POST['specials_status']) && ($_POST['specials_status'] == '1') ? '1' : '0'));
          $Qspecial->execute();

          if ($osC_Database->isError() === false) {
            $osC_MessageStack->add_session('header', SUCCESS_DB_ROWS_UPDATED, 'success');
          } else {
            $osC_MessageStack->add_session('header', ERROR_DB_ROWS_NOT_UPDATED, 'error');
          }
        }

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . (isset($_GET['sID']) ? '&sID=' . $_GET['sID'] : '')));
        break;
      case 'deleteconfirm':
        if (isset($_GET['sID']) && is_numeric($_GET['sID'])) {
          $Qspecial = $osC_Database->query('delete from :table_specials where specials_id = :specials_id');
          $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
          $Qspecial->bindInt(':specials_id', $_GET['sID']);
          $Qspecial->execute();

          if ($Qspecial->affectedRows()) {
            $osC_MessageStack->add_session('header', SUCCESS_DB_ROWS_UPDATED, 'success');
          } else {
            $osC_MessageStack->add_session('header', WARNING_DB_ROWS_NOT_UPDATED, 'warning');
          }
        }

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page']));
        break;
    }
  }

  require('../includes/classes/currencies.php');
  $osC_Currencies = new osC_Currencies();

  switch ($action) {
    case 'sNew':
    case 'sEdit': $page_contents = 'specials_edit.php'; break;
    default: $page_contents = 'specials.php';
  }

  require('templates/default.php');

  require('includes/application_bottom.php');
?>
