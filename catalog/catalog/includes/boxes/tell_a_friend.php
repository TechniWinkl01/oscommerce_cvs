<!-- tell_a_friend //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_TELL_A_FRIEND
                              );
  new infoBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="tell_a_friend" method="post" action="' . tep_href_link(FILENAME_EMAILPRODUCT, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">',
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="send_to" size="10">&nbsp;' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . '</div>' . BOX_TELL_A_FRIEND_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- tell_a_friend_eof //-->
