<!-- banners //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'class="menuBoxHeading"',
                               'text'  => BOX_HEADING_BANNERS,
                               'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=banners')
                              );
  new infoBoxHeading($info_box_contents);

  if ($selected_box == 'banners') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL') . '">' . BOX_BANNERS_MANAGER . '</a><br>'
                                );
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- banners_eof //-->
