<?php
/*
  $Id: languages.php,v 1.8 2001/12/19 01:37:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- languages //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_LANGUAGES
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $languages = tep_get_languages();

  $languages_string = '';
  for ($i=0; $i<sizeof($languages); $i++) {
    $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $languages[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $languages[$i]['image'], $languages[$i]['name']) . '</a> ';
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
