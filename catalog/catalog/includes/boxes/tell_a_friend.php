<?php
/*
  $Id: tell_a_friend.php,v 1.7 2001/12/19 01:37:55 hpdl Exp $

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
  $info_box_contents[] = array('form' => '<form name="tell_a_friend" method="get" action="' . tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false) . '">' . $hide,
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="send_to" size="10">&nbsp;' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . '</div>' . BOX_TELL_A_FRIEND_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- tell_a_friend_eof //-->
