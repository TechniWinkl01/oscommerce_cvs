<?php
/*
  $Id: information.php,v 1.5 2002/01/11 22:04:06 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_INFORMATION
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a href="' . tep_href_link(FILENAME_SHIPPING, '', 'NONSSL') . '">' . BOX_INFORMATION_SHIPPING . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_PRIVACY, '', 'NONSSL') . '">' . BOX_INFORMATION_PRIVACY . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_CONDITIONS, '', 'NONSSL') . '">' . BOX_INFORMATION_CONDITIONS . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '">' . BOX_INFORMATION_CONTACT . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->