<?php
/*
  $Id: search.php,v 1.16 2001/12/19 01:37:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- search //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_SEARCH
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $hide = tep_hide_session_id();
  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="quick_find" method="get" action="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false) . '">' . $hide,
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="keywords" size="10" maxlength="30" value="' . htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["keywords"])) . '">&nbsp;' . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH) . '</div>' . BOX_SEARCH_TEXT . '<br><div align="center"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '">&nbsp;<b>' . BOX_SEARCH_ADVANCED_SEARCH . '</b>&nbsp;</a></div>'
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- search_eof //-->
