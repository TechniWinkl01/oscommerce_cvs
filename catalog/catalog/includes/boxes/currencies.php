<?php
/*
  $Id: currencies.php,v 1.18 2003/12/18 23:52:14 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($osC_Currencies) && is_object($osC_Currencies)) {
?>
<!-- currencies //-->
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_CURRENCIES);

    new infoBoxHeading($info_box_contents, false, false);

    reset($osC_Currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($osC_Currencies->currencies)) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }

    $hidden_get_variables = '';
    reset($_GET);
    while (list($key, $value) = each($_GET)) {
      if ( ($key != 'currency') && ($key != $osC_Session->name) && ($key != 'x') && ($key != 'y') ) {
        $hidden_get_variables .= tep_draw_hidden_field($key, $value);
      }
    }

    $info_box_contents = array();
    $info_box_contents[] = array('form' => tep_draw_form('currencies', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'get'),
                                 'align' => 'center',
                                 'text' => tep_draw_pull_down_menu('currency', $currencies_array, $osC_Session->value('currency'), 'onChange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . tep_hide_session_id());

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- currencies_eof //-->
<?php
  }
?>
