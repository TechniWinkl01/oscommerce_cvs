<!-- currencies //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CURRENCIES
                              );
  new infoBoxHeading($info_box_contents);

  $currencies_query = tep_db_query("select title, code from " . TABLE_CURRENCIES . " order by title");

  $select_box = '<select name="currency" onChange="this.form.submit();">';
  while ($currencies_values = tep_db_fetch_array($currencies_query)) {
    $select_box .= '<option value="' . $currencies_values['code'] . '"';
// $currency is a session variable
    if ($currency == $currencies_values['code']) {
      $select_box .= ' SELECTED';
    }
    $select_box .= '>' . $currencies_values['title'] . '</option>';
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

  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="currencies" method="get" action="' . tep_href_link(basename($PHP_SELF), '', 'NONSSL', false) . '">' . $hidden_get_variables,
                               'align' => 'left',
                               'text'  => $select_box
                              );

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- currencies_eof //-->