<?php
/*
  $Id: tell_a_friend.php,v 1.9 2002/01/02 16:25:33 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tell_a_friend //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_TELL_A_FRIEND
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $hide = '<input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">';
  $hide .= tep_hide_session_id();

  $info_box_contents = array();
  $info_box_contents['form'] = '<form name="tell_a_friend" method="get" action="' . tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false) . '">';
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="send_to" size="10">&nbsp;' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . $hide . '</div>' . BOX_TELL_A_FRIEND_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- tell_a_friend_eof //-->
