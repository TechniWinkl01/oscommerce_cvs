<?php
/*
  $Id: configuration.php,v 1.20 2004/07/22 23:06:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- configuration //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CONFIGURATION,
                     'link'  => "javascript:toggleBlock('configuration');");

  $cfg_groups = '';
  $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
  while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
    $cfg_groups .= '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '" class="menuBoxContentLink">' . $configuration_groups['cgTitle'] . '</a><br>';
  }

  $cfg_groups .= '<a href="' . tep_href_link(FILENAME_SERVICES) . '" class="menuBoxContentLink">' . BOX_CONFIGURATION_SERVICES . '</a><br>';
  $cfg_groups .= '<a href="' . tep_href_link(FILENAME_CREDIT_CARDS) . '" class="menuBoxContentLink">' . BOX_CONFIGURATION_CREDIT_CARDS . '</a><br>';

  $contents[] = array('text'  => $cfg_groups);

  $box = new box;
  echo $box->menuBox($heading, $contents, 'configuration');
?>
            </td>
          </tr>
<!-- configuration_eof //-->
