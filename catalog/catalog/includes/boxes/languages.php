<!-- languages //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LANGUAGES
                              );
  new infoBoxHeading($info_box_contents);
 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params() . 'language=english', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'flag_en.gif', BOX_LANGUAGES_ENGLISH) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params() . 'language=german', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'flag_de.gif', BOX_LANGUAGES_DEUTSCH) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params() . 'language=espanol', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'flag_es.gif', BOX_LANGUAGES_ESPANOL) . '</a>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- languages_eof //-->
