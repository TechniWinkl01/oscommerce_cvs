<!-- languages //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LANGUAGES
                              );
  new infoBoxHeading($info_box_contents);

  $languages = tep_get_languages();

  $languages_string = '';
  for ($i=0; $i<sizeof($languages); $i++) {
    $languages_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $languages[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $languages[$i]['image'], $languages[$i]['name']) . '</a>&nbsp;';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => $languages_string
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- languages_eof //-->
