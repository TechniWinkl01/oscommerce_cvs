<?php
/*
  $Id: redirect.php,v 1.4 2001/06/13 13:38:25 hpdl Exp $

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

    case 'manufacturer' : if ($HTTP_GET_VARS['manufacturers_id']) {
                            $manufacturer_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and languages_id = '" . $languages_id . "'");
                            if (!tep_db_num_rows($manufacturer_query)) {
// no url exists for the selected language, lets use the default language then
                              $manufacturer_query = tep_db_query("select mi.languages_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " mi, " . TABLE_LANGUAGES . " l where mi.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and mi.languages_id = l.languages_id and l.code = '" . DEFAULT_LANGUAGE . "'");
                              if (!tep_db_num_rows($manufacturer_query)) {
// no url exists, return to the site
                                header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); tep_exit();
                              } else {
                                $manufacturer = tep_db_fetch_array($manufacturer_query);
                                tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set url_clicked = url_clicked+1, date_last_click = now() where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and languages_id = '" . $manufacturer['languages_id'] . "'");
                              }
                            } else {
// url exists in selected language
                              $manufacturer = tep_db_fetch_array($manufacturer_query);
                              tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set url_clicked = url_clicked+1, date_last_click = now() where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and languages_id = '" . $languages_id . "'");
                            }
                            header('Location: ' . $manufacturer['manufacturers_url']); tep_exit();
                          } else {
                            header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); tep_exit();
                          }
                          break;

    default:       header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')); tep_exit();
                   break;
  }
?>
