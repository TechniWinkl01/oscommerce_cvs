<?php
/*
  $Id: reports.php,v 1.6 2004/07/22 23:06:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => "javascript:toggleBlock('reports');");

  $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_STATISTICS) . '" class="menuBoxContentLink">' . BOX_REPORTS_STATISTICS . '</a>');

  $box = new box;
  echo $box->menuBox($heading, $contents, 'reports');
?>
            </td>
          </tr>
<!-- reports_eof //-->
