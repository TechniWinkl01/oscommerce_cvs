<?php
/*
  $Id: manufacturer_info.php,v 1.3 2001/06/13 13:38:34 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  $manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image from " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS . " p  where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
  if (tep_db_num_rows($manufacturer_query)) {
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    $manufacturer_url_query = tep_db_query("select manufacturers_url from manufacturers_info where manufacturers_id = '" . $manufacturer['manufacturers_id'] . "'");
    $has_manufacturer_url = (tep_db_num_rows($manufacturer_url_query)) ? true : false;
?>
<!-- manufacturer_info //-->
          <tr>
            <td>
<?
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_HEADING_MANUFACTURER_INFO);
    new infoBoxHeading($info_box_contents);

    $manufacturer_info_string = '<div align="center">' . tep_image($manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '</div>' .
                                 '<table border="0" width="' . BOX_WIDTH . '" cellspacing="0" cellpadding="0">';
    if ($has_manufacturer_url) $manufacturer_info_string .= '<tr><td valign="top" class="infoBox">-&nbsp;</td><td valign="top" class="infoBox"><a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id'], 'NONSSL') . '" target="_blank"><b>' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</b></a></td></tr>';
    $manufacturer_info_string .= '<tr><td valign="top" class="infoBox">-&nbsp;</td><td valign="top" class="infoBox"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id'], 'NONSSL') . '"><b>' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</b></a></td></tr></table>';

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $manufacturer_info_string);
    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- manufacturer_info_eof //-->
<?php
  }
?>
