<?php
/*
  $Id: tools.php,v 1.19 2002/03/08 18:38:17 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  if ($selected_box == 'tools') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_BACKUP) . '">' . BOX_TOOLS_BACKUP . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER) . '">' . BOX_TOOLS_BANNER_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_CACHE) . '">' . BOX_TOOLS_CACHE . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE) . '">' . BOX_TOOLS_DEFINE_LANGUAGE . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_FILE_MANAGER) . '">' . BOX_TOOLS_FILE_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MAIL) . '">' . BOX_TOOLS_MAIL . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_NEWSLETTERS) . '">' . BOX_TOOLS_NEWSLETTER_MANAGER . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_SERVER_INFO) . '">' . BOX_TOOLS_SERVER_INFO . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_WHOS_ONLINE) . '">' . BOX_TOOLS_WHOS_ONLINE . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
