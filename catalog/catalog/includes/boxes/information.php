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
                               'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_SHIPPING, '', 'NONSSL') . '">' . BOX_INFORMATION_SHIPPING . '</a>&nbsp;<br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_PRIVACY, '', 'NONSSL') . '">' . BOX_INFORMATION_PRIVACY . '</a>&nbsp;<br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_CONDITIONS, '', 'NONSSL') . '">' . BOX_INFORMATION_CONDITIONS . '</a>&nbsp;<br>' .
                                          '&nbsp;<a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '">' . BOX_INFORMATION_CONTACT . '</a>&nbsp;'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->