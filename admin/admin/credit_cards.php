<?php
/*
  $Id: credit_cards.php,v 1.2 2004/07/22 23:27:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $selected_box = 'configuration';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!isset($_GET['page']) || (isset($_GET['page']) && !is_numeric($_GET['page']))) {
    $_GET['page'] = 1;
  }

  if (!empty($action)) {
    switch ($action) {
      case 'save':
        $error = false;

        if (empty($_POST['credit_card_name'])) {
          $messageStack->add(ERROR_CREDIT_CARD_NAME, 'error');
          $error = true;
        }

        if (empty($_POST['credit_card_code'])) {
          $messageStack->add(ERROR_CREDIT_CARD_CODE, 'error');
          $error = true;
        }

        if ($error === false) {
          if (isset($_GET['ccID']) && is_numeric($_GET['ccID'])) {
            $Qcc = $osC_Database->query('update :table_credit_cards set credit_card_name = :credit_card_name, credit_card_code = :credit_card_code, credit_card_status = :credit_card_status, sort_order = :sort_order where credit_card_id = :credit_card_id');
            $Qcc->bindInt(':credit_card_id', $_GET['ccID']);
          } else {
            $Qcc = $osC_Database->query('insert into :table_credit_cards (credit_card_name, credit_card_code, credit_card_status, sort_order) values (:credit_card_name, :credit_card_code, :credit_card_status, :sort_order)');
          }
          $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
          $Qcc->bindValue(':credit_card_name', $_POST['credit_card_name']);
          $Qcc->bindValue(':credit_card_code', $_POST['credit_card_code']);
          $Qcc->bindInt(':credit_card_status', (isset($_POST['credit_card_status']) && ($_POST['credit_card_status'] == '1') ? '1' : '0'));
          $Qcc->bindInt(':sort_order', $_POST['sort_order']);
          $Qcc->execute();

          if ($Qcc->affectedRows()) {
            osC_Cache::clear('credit-cards');

            $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');
          } else {
            $messageStack->add_session(WARNING_DB_ROWS_NOT_UPDATED, 'warning');
          }

          tep_redirect(tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $_GET['page'] . '&ccID=' . ((isset($_GET['ccID']) && is_numeric($_GET['ccID'])) ? $_GET['ccID'] : $osC_Database->nextID())));
        } else {
          if ($action == 'insert') {
            $action = 'new';
          } else {
            $action = 'configure';
          }
        }

        break;
      case 'deleteconfirm':
        if (isset($_GET['ccID']) && is_numeric($_GET['ccID'])) {
          $Qdel = $osC_Database->query('delete from :table_credit_cards where credit_card_id = :credit_card_id');
          $Qdel->bindRaw(':table_credit_cards', TABLE_CREDIT_CARDS);
          $Qdel->bindInt(':credit_card_id', $_GET['ccID']);
          $Qdel->execute();

          if ($Qdel->affectedRows()) {
            osC_Cache::clear('credit-cards');

            $messageStack->add_session(SUCCESS_DB_ROWS_UPDATED, 'success');
          } else {
            $messageStack->add_session(WARNING_DB_ROWS_NOT_UPDATED, 'warning');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $_GET['page']));
        break;
    }
  }

  $page_contents = 'credit_cards.php';

  require('templates/default.php');

  require('includes/application_bottom.php');
?>
