<?php
/*
  $Id: banner.php,v 1.3 2001/07/20 12:40:25 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
// Sets the status of a banner
  function tep_set_banner_status($banners_id, $status) {
    return tep_db_query("update " . TABLE_BANNERS . " set status = '" . $status . "', date_status_change = now() where banners_id = '" . $banners_id . "'");
  }

////
// Check and expire a banner
  function tep_expire_banner_check($banners_id) {
    $banners_query = tep_db_query("select b.expires_date, b.expires_impressions, b.date_added, sum(bh.banners_shown) as banners_shown from banners b, banners_history bh where b.banners_id = '" . $banners_id . "' and b.banners_id = bh.banners_id group by b.banners_id");
    if (tep_db_num_rows($banners_query)) {
      $banners = tep_db_fetch_array($banners_query);
      if ($banners['expires_date']) {
        if (date('Y-m-d H:i:s') >= $banners['expires_date']) {
          return tep_set_banner_status($banners_id, '0');
        } else {
          return false;
        }
      } elseif ($banners['expires_impressions']) {
        if ($banners['banners_shown'] >= $banners['expires_impressions']) {
          return tep_set_banner_status($banners_id, '0');
        } else {
          return false;
        }
      }
    } else {
      return false;
    }
  }

////
// Display a banner from the specified group or banner id ($identifier)
  function tep_display_banner($action, $identifier) {
    if ($action == 'dynamic') {
      $banners_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'");
      $banners = tep_db_fetch_array($banners_query);
      if ($banners['count'] > 0) {
        $banner = tep_random_select("select banners_id, banners_title, banners_image from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'");
      } else {
        return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</b>';
      }
    } elseif ($action == 'static') {
      $banner_query = tep_db_query("select banners_id, banners_title, banners_image from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . $identifier . "'");
      if (tep_db_num_rows($banner_query)) {
        $banner = tep_db_fetch_array($banner_query);
      } else {
        return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</b>';
      }
    } else {
      return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</b>';
    }

    $banner_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '" target="_blank"><img src="' . $banner['banners_image'] . '" border="0" alt="' . $banner['banners_title'] . '"></a>';

    $banner_check_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $banner['banners_id'] . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    $banner_check = tep_db_fetch_array($banner_check_query);

    if ($banner_check['count'] > 0) {
      tep_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_shown = banners_shown + 1 where banners_id = '" . $banner['banners_id'] . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    } else {
      tep_db_query("insert into " . TABLE_BANNERS_HISTORY . " (banners_id, banners_shown, banners_history_date) values ('" . $banner['banners_id'] . "', 1, now())");
    }

    tep_expire_banner_check($banner['banners_id']);

    return $banner_string;
  }

////
// Check to see if a banner exists
  function tep_banner_exists($action, $identifier) {
    if ($action == 'dynamic') {
      $banners_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'");
      $banners = tep_db_fetch_array($banners_query);
      if ($banners['count'] > 0) {
        return true;
      } else {
        return false;
      }
    } elseif ($action == 'static') {
      $banner_query = tep_db_query("select banners_id from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . $identifier . "'");
      if (tep_db_num_rows($banner_query)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

////
// Update the banner click statistics
  function tep_update_banner_count($banner_id) {
    tep_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_clicked = banners_clicked + 1 where banners_id = '" . $banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
  }
?>