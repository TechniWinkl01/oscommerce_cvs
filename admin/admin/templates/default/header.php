<?php
/*
  $Id: header.php,v 1.2 2004/08/24 11:06:07 hpdl Exp $

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

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; ?></td>
    <td class="headerBarContent" align="right"><?php echo '<a href="http://www.oscommerce.com" class="headerLink">' . HEADER_TITLE_SUPPORT_SITE . '</a> &nbsp;|&nbsp; <a href="' . tep_catalog_href_link() . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a> &nbsp;|&nbsp; ' . tep_draw_form('languages', FILENAME_DEFAULT, '', 'get') . tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"') . '</form>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>
