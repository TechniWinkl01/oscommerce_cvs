<?php
/*
  $Id: currencies.php,v 1.12 2002/05/11 13:18:01 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- currencies //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CURRENCIES
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $select_box = '<select name="currency" onChange="this.form.submit();">';
  reset($currencies->currencies);
  while (list($key, $value) = each($currencies->currencies)) {
    $select_box .= '<option value="' . $key . '"';
// $currency is a session variable
    if ($currency == $key) {
      $select_box .= ' SELECTED';
    }
    $select_box .= '>' . $value['title'] . '</option>';
  }
  $select_box .= "</select>";
  $select_box .= tep_hide_session_id();

  $hidden_get_variables = '';
  reset($HTTP_GET_VARS);
  while (list($key, $value) = each ($HTTP_GET_VARS)) {
    if ( ($key != 'currency') && ($key != tep_session_name()) ) {
      $hidden_get_variables .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
    }
  }

  if (getenv('HTTPS') == 'on') $connection = 'SSL';
  else $connection = 'NONSSL';

  $select_box .= $hidden_get_variables;

  $info_box_contents = array();
  $info_box_contents[] = array('form'  => '<form name="currencies" method="get" action="' . tep_href_link(basename($PHP_SELF), '', $connection, false) . '">',
                               'align' => 'left',
                               'text'  => $select_box
                              );

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- currencies_eof //-->
