<!-- information //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_INFORMATION
                              );
  new infoBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a href="' . tep_href_link(FILENAME_INFORMATION, 'action=shipping', 'NONSSL') . '">' . BOX_INFORMATION_SHIPPING . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_INFORMATION, 'action=privacy', 'NONSSL') . '">' . BOX_INFORMATION_PRIVACY . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_INFORMATION, 'action=conditions', 'NONSSL') . '">' . BOX_INFORMATION_CONDITIONS . '</a><br>' .
                                          '<a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '">' . BOX_INFORMATION_CONTACT . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->