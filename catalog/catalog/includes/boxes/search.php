<!-- search //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_SEARCH
                              );
  new infoBoxHeading($info_box_contents);
 
  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="quick_find" method="get" action="' . tep_href_link(FILENAME_SEARCH, '', 'NONSSL') . '">',
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="query" size="10" maxlength="30" value="' . htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["query"])) . '">&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_quick_find.gif', '16', '17', '0', BOX_HEADING_SEARCH) . '</div>' . BOX_SEARCH_TEXT . '<br><div align="center"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '">&nbsp;<b>' . BOX_SEARCH_ADVANCED_SEARCH . '</b>&nbsp;</a></div>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- search_eof //-->
