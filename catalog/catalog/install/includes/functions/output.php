<?php
/*
  $Id: output.php,v 1.3 2002/06/01 18:44:37 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  function osc_draw_box_heading($heading_title, $left_corner = false, $right_corner = false) {
    $heading = '<table cellspacing="0" cellpadding="0" width="100%" border="0">' . CR .
               '  <tr valign="middle" bgcolor="' . BOX_BGCOLOR_HEADING . '">' . CR .
               '    <td>';
    if ($left_corner) {
      $heading .= '<img src="images/main_page/box_corner_left.gif" border="0">';
    } else {
      $heading .= '<img src="images/main_page/box_corner_right_left.gif" border="0">';
    }

    $heading .= '</td>' . CR .
                '    <td width="100%" height="14" class="infoBoxHeading">' . $heading_title . '</td>' . CR;

    if ($right_corner) {
      $heading .= '    <td height="14" class="infoBoxHeading"><img src="images/main_page/box_corner_right.gif" border="0"></td>' . CR;
    }

    $heading .= '  </tr>' . CR .
                '</table>' . CR;

    return $heading;
  }

  function osc_draw_box_contents($box_contents, $box_shadow_color = BOX_SHADOW, $box_background_color = BOX_BGCOLOR_CONTENTS) {
    $contents = '<table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="' . $box_shadow_color . '">' . CR .
                 '  <tr>' . CR .
                 '    <td><table border="0" width="100%" cellspacing="0" cellpadding="4" bgcolor="' . $box_background_color . '">' . CR .
                 '      <tr>' . CR .
                 '        <td><img src="images/pixel_trans.gif" border="0" width="100%" height="5"></td>' . CR .
                 '      </tr>';

    if (is_array($box_contents)) {
      for ($i=0; $i<sizeof($box_contents); $i++) {
        $contents .= osc_draw_box_content_bullet($box_contents[$i]['title'], $box_contents[$i]['link']);
      }
    } else {
      $contents .= '      <tr>' . CR .
                   '        <td class="infoboxText">' . $box_contents . '</td>' . CR .
                   '      </tr>' . CR;
    }

    $contents .= '      <tr>' . CR .
                 '        <td><img src="images/pixel_trans.gif" border="0" width="100%" height="5"></td>' . CR .
                 '      </tr>' . CR .
                 '    </table></td>' . CR .
                 '  </tr>' . CR .
                 '</table>' . CR;

    return $contents;
  }

  function osc_draw_box_content_bullet($bullet_text, $bullet_link = '') {
    global $page_file;

    $bullet = '      <tr>' . CR .
              '        <td><table border="0" cellspacing="0" cellpadding="0">' . CR .
              '          <tr>' . CR .
              '            <td width="12" class="boxText"><img src="images/icon_pointer.gif" border="0"></td>' . CR .
              '            <td class="infoboxText">';
    if ($bullet_link) {
      if ($bullet_link == $page_file) {
        $bullet .= '<font color="#0033cc"><b>' . $bullet_text . '</b></font>';
      } else {
        $bullet .= '<a href="' . $bullet_link . '">' . $bullet_text . '</a>';
      }
    } else {
      $bullet .= $bullet_text;
    }

    $bullet .= '</td>' . CR .
               '         </tr>' . CR .
               '       </table></td>' . CR .
               '     </tr>' . CR;

    return $bullet;
  }
?>