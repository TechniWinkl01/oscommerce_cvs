<?php
/*
  $Id: add_a_quickie.php,v 1.10 2001/12/19 01:37:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- add_a_quickie //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_ADD_PRODUCT_ID
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="quick_add" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_a_quickie', 'NONSSL') . '">',
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="quickie" size="10">&nbsp;' . tep_image_submit('button_add_quick.gif', BOX_HEADING_ADD_PRODUCT_ID) . '</div>' . BOX_ADD_PRODUCT_ID_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- add_a_quickie_eof //-->
