<!-- mail //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_MAIL,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=mail'),
                               'img'  => ($selected_box == 'mail') ? tep_image(DIR_WS_IMAGES . 'icon_opened_box.gif', '', '11', '11') : tep_image(DIR_WS_IMAGES . 'icon_open_box.gif', '', '11', '11')
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'mail') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_MAIL, 'action=email_user', 'NONSSL') . '">' . BOX_MAIL_MAIL . '</a>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- mail_eof //-->
