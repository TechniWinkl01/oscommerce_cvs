<?php
/*
  $Id: languages.php,v 1.10 2002/04/24 16:48:13 hpdl Exp $

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

  if (!class_exists('language')) include(DIR_WS_CLASSES . 'language.php');
  if (!is_object($lng)) $lng = new language;

  $languages_string = '';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
    $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, 'NONSSL') . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
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
