<?php
/*
  $Id: banners.php,v 1.7 2002/01/08 18:58:25 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- banners //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_BANNERS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=banners'));

  if ($selected_box == 'banners') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL') . '">' . BOX_BANNERS_MANAGER . '</a><br>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- banners_eof //-->
