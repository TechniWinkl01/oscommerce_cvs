<?php
/*
  $Id: banner.php,v 1.14 2004/11/24 15:55:05 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

////
// Sets the status of a banner
  function tep_set_banner_status($banners_id, $status) {
    global $osC_Database;

    if ($status == '1') {
      $Qbanner = $osC_Database->query('update :table_banners set status = 1, date_status_change = now(), date_scheduled = NULL where banners_id = :banners_id');
      $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
      $Qbanner->bindInt(':banners_id', $banners_id);
      $Qbanner->execute();
    } else {
      $Qbanner = $osC_Database->query('update :table_banners set status = 0, date_status_change = now() where banners_id = :banners_id');
      $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
      $Qbanner->bindInt(':banners_id', $banners_id);
      $Qbanner->execute();
    }

    $Qbanner->freeResult();
  }

////
// Auto activate banners
  function tep_activate_banners() {
    global $osC_Database;

    $Qbanner = $osC_Database->query('select banners_id, date_scheduled from :table_banners where date_scheduled != ""');
    $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
    $Qbanner->execute();

    if ($Qbanner->numberOfRows()) {
      while ($Qbanner->next()) {
        if (date('Y-m-d H:i:s') >= $Qbanner->value('date_scheduled')) {
          tep_set_banner_status($Qbanner->valueInt('banners_id'), '1');
        }
      }
    }

    $Qbanner->freeResult();
  }

////
// Auto expire banners
  function tep_expire_banners() {
    global $osC_Database;

    $Qbanner = $osC_Database->query('select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from :table_banners b, :table_banners_history bh where b.status = 1 and b.banners_id = bh.banners_id group by b.banners_id');
    $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
    $Qbanner->bindRaw(':table_banners_history', TABLE_BANNERS_HISTORY);
    $Qbanner->execute();

    if ($Qbanner->numberOfRows()) {
      while ($Qbanner->next()) {
        if (tep_not_null($Qbanner->value('expires_date'))) {
          if (date('Y-m-d H:i:s') >= $Qbanner->value('expires_date')) {
            tep_set_banner_status($Qbanner->valueInt('banners_id'), '0');
          }
        } elseif (tep_not_null($Qbanner->valueInt('expires_impressions'))) {
          if ( ($Qbanner->valueInt('expires_impressions') > 0) && ($Qbanner->valueInt('banners_shown') >= $Qbanner->valueInt('expires_impressions')) ) {
            tep_set_banner_status($Qbanner->valueInt('banners_id'), '0');
          }
        }
      }
    }

    $Qbanner->freeResult();
  }

////
// Display a banner from the specified group or banner id ($identifier)
  function tep_display_banner($action, $identifier) {
    global $osC_Database;

    if ($action == 'dynamic') {
      $Qbannercheck = $osC_Database->query('select count(*) as count from :table_banners where status = 1 and banners_group = :banners_group');
      $Qbannercheck->bindRaw(':table_banners', TABLE_BANNERS);
      $Qbannercheck->bindValue(':banners_group', $identifier);
      $Qbannercheck->execute();

      if ($Qbannercheck->valueInt('count') > 0) {
        $Qbanner = $osC_Database->query('select banners_id, banners_title, banners_image, banners_html_text from :table_banners where banners_group = :banners_group and status = 1');
        $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
        $Qbanner->bindValue(':banners_group', $identifier);
        $Qbanner->executeRandom();
      }

      $Qbannercheck->freeResult();
    } elseif ($action == 'static') {
      $Qbanner = $osC_Database->query('select banners_id, banners_title, banners_image, banners_html_text from :table_banners where banners_id = :banners_id and status = 1');
      $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
      $Qbanner->bindInt(':banners_id', $identifier);
      $Qbanner->execute();
    }

    if ($Qbanner->numberOfRows() === 1) {
      if (tep_not_null($Qbanner->value('banners_html_text'))) {
        $banner_string = $Qbanner->value('banners_html_text');
      } else {
        $banner_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $Qbanner->valueInt('banners_id')) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $Qbanner->value('banners_image'), $Qbanner->value('banners_title')) . '</a>';
      }

      tep_update_banner_display_count($Qbanner->valueInt('banners_id'));

      $Qbanner->freeResult();

      return $banner_string;
    } else {
      return false;
    }
  }

////
// Check to see if a banner exists
  function tep_banner_exists($action, $identifier) {
    global $osC_Database;

    if ($action == 'dynamic') {
      $Qbanner = $osC_Database->query('select banners_id, banners_title, banners_image, banners_html_text from :table_banners where status = 1 and banners_group = :banners_group');
      $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
      $Qbanner->bindValue(':banners_group', $identifier);
      $Qbanner->executeRandom();
    } else {
      $Qbanner = $osC_Database->query('select banners_id, banners_title, banners_image, banners_html_text from :table_banners where status = 1 and banners_id = :banners_id');
      $Qbanner->bindRaw(':table_banners', TABLE_BANNERS);
      $Qbanner->bindInt(':banners_id', $identifier);
      $Qbanner->execute();
    }

    if ($Qbanner->numberOfRows()) {
      return true;
    } else {
      return false;
    }
  }

////
// Update the banner display statistics
  function tep_update_banner_display_count($banner_id) {
    global $osC_Database;

    $Qbannercheck = $osC_Database->query('select count(*) as count from :table_banners_history where banners_id = :banners_id and date_format(banners_history_date, "%Y%m%d") = date_format(now(), "%Y%m%d")');
    $Qbannercheck->bindRaw(':table_banners_history', TABLE_BANNERS_HISTORY);
    $Qbannercheck->bindInt(':banners_id', $banner_id);
    $Qbannercheck->execute();

    if ($Qbannercheck->valueInt('count') > 0) {
      $Qbanner = $osC_Database->query('update :table_banners_history set banners_shown = banners_shown + 1 where banners_id = :banners_id and date_format(banners_history_date, "%Y%m%d") = date_format(now(), "%Y%m%d")');
    } else {
      $Qbanner = $osC_Database->query('insert into :table_banners_history (banners_id, banners_shown, banners_history_date) values (:banners_id, 1, now())');
    }
    $Qbanner->bindRaw(':table_banners_history', TABLE_BANNERS_HISTORY);
    $Qbanner->bindInt(':banners_id', $banner_id);
    $Qbanner->execute();

    $Qbannercheck->freeResult();
    $Qbanner->freeResult();
  }

////
// Update the banner click statistics
  function tep_update_banner_click_count($banner_id) {
    global $osC_Database;

    $Qbanner = $osC_Database->query('update :table_banners_history set banners_clicked = banners_clicked + 1 where banners_id = :banners_id and date_format(banners_history_date, "%Y%m%d") = date_format(now(), "%Y%m%d")');
    $Qbanner->bindRaw(':table_banners_history', TABLE_BANNERS_HISTORY);
    $Qbanner->bindInt(':banners_id', $banner_id);
    $Qbanner->execute();

    $Qbanner->freeResult();
  }
?>
