<?php
/*
  $Id: redirect.php,v 1.3 2001/06/13 12:14:59 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  switch ($HTTP_GET_VARS['action']) {
    case 'banner': $banner_query = tep_db_query("select banners_url from " . TABLE_BANNERS . " where banners_id = '" . $HTTP_GET_VARS['goto'] . "'");
                   if (tep_db_num_rows($banner_query)) {
                     $banner = tep_db_fetch_array($banner_query);
                     tep_update_banner_count($HTTP_GET_VARS['goto']);
                     header('Location: ' . $banner['banners_url']); tep_exit();
                   } else {
                     header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); tep_exit();
                   }
                   break;

    case 'url':    if ($HTTP_GET_VARS['goto']) {
                     header('Location: http://' . $HTTP_GET_VARS['goto']); tep_exit();
                   } else {
                     header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); tep_exit();
                   }
                   break;

    default:       header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); tep_exit();
                   break;
  }
?>
