<?php
/*
  $Id: modules.php,v 1.17 2004/07/22 23:06:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- modules //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_MODULES,
                     'link'  => "javascript:toggleBlock('modules');");

  $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_PAYMENT . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_SHIPPING . '</a><br>' .
                                 '<a href="' . tep_href_link(FILENAME_MODULES, 'set=ordertotal', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_ORDER_TOTAL . '</a>');

  $box = new box;
  echo $box->menuBox($heading, $contents, 'modules');
?>
            </td>
          </tr>
<!-- modules_eof //-->
