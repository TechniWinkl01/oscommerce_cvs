<!-- banners //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_BANNERS,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=banners'),
                               'img'  => ($selected_box == 'banners') ? tep_image(DIR_WS_IMAGES . 'icon_opened_box.gif', '', '11', '11') : tep_image(DIR_WS_IMAGES . 'icon_open_box.gif', '', '11', '11')
                              );
  new navigationBoxHeading($info_box_contents);

  if ($selected_box == 'banners') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'params' => 'nowrap',
                                 'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL') . '">' . BOX_BANNERS_MANAGER . '</a><br>'
                                );
    new navigationBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- banners_eof //-->
