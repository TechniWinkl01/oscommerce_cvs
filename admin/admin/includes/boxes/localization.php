<!-- localization //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LOCALIZATION
                              );
  new navigationBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'params' => 'nowrap',
                               'text'  => '&nbsp;<a href="' . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL') . '">' . BOX_LOCALIZATION_CURRENCIES . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- localization_eof //-->
