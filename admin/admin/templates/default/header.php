<?php
/*
  $Id: header.php,v 1.3 2004/08/25 20:05:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);

    if ($languages[$i]['directory'] == $osC_Session->value('language')) {
      $languages_selected = $languages[$i]['code'];
    }
  }

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>

<script language="javascript" src="external/jscookmenu/JSCookMenu.js"></script>
<link rel="stylesheet" href="external/jscookmenu/ThemeOffice/theme.css" type="text/css">
<script language="javascript" src="external/jscookmenu/ThemeOffice/theme.js"></script>

<script language="javascript"><!--
<?php
  echo 'var administrationMenu =' . "\n" .
       '[' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_CONFIGURATION) . '\', null, null, null,' . "\n";

  $Qgroups = $osC_Database->query('select configuration_group_id, configuration_group_title from :table_configuration_group where visible = 1 order by sort_order');
  $Qgroups->bindTable(':table_configuration_group', TABLE_CONFIGURATION_GROUP);
  $Qgroups->execute();

  while ($Qgroups->next()) {
    echo '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png') . '\', \'' . addslashes($Qgroups->value('configuration_group_title')) . '\', \'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $Qgroups->valueInt('configuration_group_id')) . '\', null, null],' . "\n";
  }

  echo '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/services.png') . '\', \'' . addslashes(BOX_CONFIGURATION_SERVICES) . '\', \'' . tep_href_link(FILENAME_SERVICES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/wallet.png') . '\', \'' . addslashes(BOX_CONFIGURATION_CREDIT_CARDS) . '\', \'' . tep_href_link(FILENAME_CREDIT_CARDS) . '\', null, null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_CATALOG) . '\', null, null, null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png') . '\', \'' . addslashes(BOX_CATALOG_CATEGORIES_PRODUCTS) . '\', \'' . tep_href_link(FILENAME_CATEGORIES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/attributes.png') . '\', \'' . addslashes(BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) . '\', \'' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES) . '\', \'\', null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/run.png') . '\', \'' . addslashes(BOX_CATALOG_MANUFACTURERS) . '\', \'' . tep_href_link(FILENAME_MANUFACTURERS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/write.png') . '\', \'' . addslashes(BOX_CATALOG_REVIEWS) . '\', \'' . tep_href_link(FILENAME_REVIEWS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/specials.png') . '\', \'' . addslashes(BOX_CATALOG_SPECIALS) . '\', \'' . tep_href_link(FILENAME_SPECIALS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/history.png') . '\', \'' . addslashes(BOX_CATALOG_PRODUCTS_EXPECTED) . '\', \'' . tep_href_link(FILENAME_PRODUCTS_EXPECTED) . '\', null, null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_MODULES) . '\', null, null, null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/payment.png') . '\', \'' . addslashes(BOX_MODULES_PAYMENT) . '\', \'' . tep_href_link(FILENAME_MODULES, 'set=payment') . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/install.png') . '\', \'' . addslashes(BOX_MODULES_SHIPPING) . '\', \'' . tep_href_link(FILENAME_MODULES, 'set=shipping') . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/calculator.png') . '\', \'' . addslashes(BOX_MODULES_ORDER_TOTAL) . '\', \'' . tep_href_link(FILENAME_MODULES, 'set=ordertotal') . '\', null, null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_CUSTOMERS) . '\', null, null, null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/people.png') . '\', \'' . addslashes(BOX_CUSTOMERS_CUSTOMERS) . '\', \'' . tep_href_link(FILENAME_CUSTOMERS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/orders.png') . '\', \'' . addslashes(BOX_CUSTOMERS_ORDERS) . '\', \'' . tep_href_link(FILENAME_ORDERS) . '\', null, null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_LOCATION_AND_TAXES) . '\', null, null, null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/world.png') . '\', \'' . addslashes(BOX_TAXES_COUNTRIES) . '\', \'' . tep_href_link(FILENAME_COUNTRIES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/remote.png') . '\', \'' . addslashes(BOX_TAXES_ZONES) . '\', \'' . tep_href_link(FILENAME_ZONES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/relationships.png') . '\', \'' . addslashes(BOX_TAXES_GEO_ZONES) . '\', \'' . tep_href_link(FILENAME_GEO_ZONES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/classes.png') . '\', \'' . addslashes(BOX_TAXES_TAX_CLASSES) . '\', \'' . tep_href_link(FILENAME_TAX_CLASSES) . '\', null, null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_LOCALIZATION) . '\', null, null, null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/currencies.png') . '\', \'' . addslashes(BOX_LOCALIZATION_CURRENCIES) . '\', \'' . tep_href_link(FILENAME_CURRENCIES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/locale.png') . '\', \'' . addslashes(BOX_LOCALIZATION_LANGUAGES) . '\', \'' . tep_href_link(FILENAME_LANGUAGES) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/status.png') . '\', \'' . addslashes(BOX_LOCALIZATION_ORDERS_STATUS) . '\', \'' . tep_href_link(FILENAME_ORDERS_STATUS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/weight.png') . '\', \'' . addslashes(BOX_LOCALIZATION_WEIGHT_CLASSES) . '\', \'' . tep_href_link(FILENAME_WEIGHT_CLASSES) . '\', null, null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(BOX_HEADING_TOOLS) . '\', null, null, null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/tape.png') . '\', \'' . addslashes(BOX_TOOLS_BACKUP) . '\', \'' . tep_href_link(FILENAME_BACKUP) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/windows.png') . '\', \'' . addslashes(BOX_TOOLS_BANNER_MANAGER) . '\', \'' . tep_href_link(FILENAME_BANNER_MANAGER) . '\', \'\', null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/log.png') . '\', \'' . addslashes(BOX_TOOLS_CACHE) . '\', \'' . tep_href_link(FILENAME_CACHE) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/locale.png') . '\', \'' . addslashes(BOX_TOOLS_DEFINE_LANGUAGE) . '\', \'' . tep_href_link(FILENAME_DEFINE_LANGUAGE) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/file_manager.png') . '\', \'' . addslashes(BOX_TOOLS_FILE_MANAGER) . '\', \'' . tep_href_link(FILENAME_FILE_MANAGER) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/email_send.png') . '\', \'' . addslashes(BOX_TOOLS_NEWSLETTER_MANAGER) . '\', \'' . tep_href_link(FILENAME_NEWSLETTERS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/server_info.png') . '\', \'' . addslashes(BOX_TOOLS_SERVER_INFO) . '\', \'' . tep_href_link(FILENAME_SERVER_INFO) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/statistics.png') . '\', \'' . addslashes(BOX_REPORTS_STATISTICS) . '\', \'' . tep_href_link(FILENAME_STATISTICS) . '\', null, null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/people.png') . '\', \'' . addslashes(BOX_TOOLS_WHOS_ONLINE) . '\', \'' . tep_href_link(FILENAME_WHOS_ONLINE) . '\', null, null]' . "\n" .
       '    ]' . "\n" .
       '];' . "\n" .
       'var infoMenu =' . "\n" .
       '[' . "\n" .
       '    [null, \'' . addslashes(HEADER_TITLE_SUPPORT_SITE) . '\', \'http://www.oscommerce.com\', \'_blank\', null,' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/oscommerce.png') . '\', \'Support Site\', \'http://www.oscommerce.com\', \'_blank\', null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/log.png') . '\', \'Knowledge Base\', \'http://www.oscommerce.info\', \'_blank\', null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/people.png') . '\', \'Community Forums\', \'http://forums.oscommerce.com\', \'_blank\', null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/run.png') . '\', \'Contributions\', \'http://www.oscommerce.com/community/contributions\', \'_blank\', null],' . "\n" .
       '        [\'' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png') . '\', \'Bug Reporter\', \'http://www.oscommerce.com/community/bugs\', \'_blank\', null]' . "\n" .
       '    ],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . addslashes(HEADER_TITLE_ONLINE_CATALOG) . '\', \'' . tep_catalog_href_link() . '\', \'_blank\', null],' . "\n" .
       '    _cmSplit,' . "\n" .
       '    [null, \'' . tep_draw_form('languages', FILENAME_DEFAULT, '', 'get') . tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"') . '</form>' . '\', null, null, null]' . "\n" .
       '];' . "\n";
?>
--></script>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'oscommerce.gif', 'osCommerce', '204', '50') . '</a>'; ?></td>
    <td width="150" align="right" class="smallText">
<?php
  if ($request_type == 'SSL') {
    echo sprintf(BOX_CONNECTION_PROTECTED, (isset($_SERVER['SSL_CIPHER_ALGKEYSIZE']) ? $_SERVER['SSL_CIPHER_ALGKEYSIZE'] . '-bit' : '<i>' . BOX_CONNECTION_UNKNOWN . '</i>')) . tep_image('templates/' . $template . '/images/icons/16x16/locked.png', ICON_LOCKED);
  } else {
    echo BOX_CONNECTION_UNPROTECTED . ' ' . tep_image('templates/' . $template . '/images/icons/16x16/unlocked.png', ICON_UNLOCKED);
  }
?>
    </td>
  </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="0" class="ThemeOfficeMainItem">
  <tr>
    <td id="administrationMenuID"></td>
    <td align="right" id="infoMenuID"></td>
  </tr>
</table>

<script language="javascript"><!--
  cmDraw('administrationMenuID', administrationMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
  cmDraw('infoMenuID', infoMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
--></script>
