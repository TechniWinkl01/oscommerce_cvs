<?php
/*
  $Id: taxes.php,v 1.18 2004/07/22 23:06:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- taxes //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                     'link'  => "javascript:toggleBlock('taxes');");

  $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_COUNTRIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_COUNTRIES . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_ZONES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_ZONES . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_GEO_ZONES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_GEO_ZONES . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_TAX_CLASSES . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_TAX_RATES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TAXES_TAX_RATES . '</a>');

  $box = new box;
  echo $box->menuBox($heading, $contents, 'taxes');
?>
            </td>
          </tr>
<!-- taxes_eof //-->
