<?php
/*
  $Id: localization.php,v 1.18 2004/07/22 23:06:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- localization //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCALIZATION,
                     'link'  => "javascript:toggleBlock('localization');");

  $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_CURRENCIES . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_LANGUAGES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_LANGUAGES . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_ORDERS_STATUS . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_LOCALIZATION_WEIGHT_CLASSES . '</a>');

  $box = new box;
  echo $box->menuBox($heading, $contents, 'localization');
?>
            </td>
          </tr>
<!-- localization_eof //-->
