<?php
/*
  $Id: html_output.php,v 1.1 2001/11/19 13:44:47 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    if ($page == '') {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }
    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_ADMIN;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 1) {
        $link = HTTPS_SERVER . DIR_WS_ADMIN;
      } else {
        $link = HTTP_SERVER . DIR_WS_ADMIN;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }
    if ($parameters == '') {
      $link = $link . $page . '?' . SID;
    } else {
      $link = $link . $page . '?' . $parameters . '&' . SID;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . $src . '" border="0" alt=" ' . $alt . ' "';
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    if ($params) {
      $image .= ' ' . $params;
    }
    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt) {
    global $language;

    $image_submit = '<input type="image" src="' . DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image . '" border="0" alt="' . $alt . '">';

    return $image_submit;
  }

////
// Draw a 1 pixel black line
  function tep_black_line() {
    return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }

////
// Creates a pull-down list of countries
// Parameters:
// popup_name: the name of the pull-down list
// selected:   the default selected item
// javascript: javascript for the pull-down list (ie, onChange="this.form.submit()")
// size:       pull-down list size
  function tep_get_country_list($popup_name, $selected = '', $javascript = '', $size = 1) {
    $result = '<select name="' . $popup_name . '"';

    if ($size != 1) $result .= ' size="' . $size . '"';

    if ($javascript != '') $result .= ' ' . $javascript;

    $result .= '><option value="">' . PLEASE_SELECT . '</option>';

    $countries = tep_get_countries();
    for ($i=0; $i<sizeof($countries); $i++) {
      $result .= '<option value="' . $countries[$i]['countries_id'] . '"';
      if ($selected == $countries[$i]['countries_id']) $result .= ' SELECTED';
      $result .= '>' . $countries[$i]['countries_name'] . '</option>';
     }
    $result .= '</select>';

    return $result;
  }

////
// Creates a pull-down list of states and provinces
// Parameters:
// popup_name:   the name of the pull-down list
// country_code: the default selected item
// selected:     the default selected item
// javascript:   javascript for the pull-down list (ie, onChange="this.form.submit()")
// size:         pull-down list size
// TABLES: zones
  function tep_get_zone_list($popup_name, $country_code = '', $selected = '', $javascript = '', $size = 1) {
    $result = '<select name="' . $popup_name . '"';

    if ($size != 1) $result .= ' size="' . $size . '"';

    if ($javascript) $result .= ' ' . $javascript;

    $result .= '>';

    // Preset the width of the drop-down for Netscape
    if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<53; $i++) $result .= '&nbsp;';
    }

    $state_prov_result = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_code . "' order by zone_name");
    if (tep_db_num_rows($state_prov_result)) {
      $result .= '<option>' . PLEASE_SELECT . '</option>';
    } else {
      $result .= '<option>' . TYPE_BELOW . '</option>';
    }

    $populated = 0;
    while ($state_prov_values = tep_db_fetch_array($state_prov_result)) {
      $populated++;
      $result .= '<option value="' . $state_prov_values['zone_id'] . '"';
      if ($selected == $state_prov_values['zone_id']) $result .= ' SELECTED';
      $result .= '>' . $state_prov_values['zone_name'] . '</option>';
    }

    // Create dummy options for Netscape to preset the height of the drop-down
    if ($populated == 0) {
      if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
        for ($i=0; $i<9; $i++) {
          $result .= '<option></option>';
        }
      }
    }

    $result .= '</select>';

    echo $result;
  }

////
// javascript to dynamically update the states/provinces list when the country is changed
// Parameters:
// selectedcountryvar: string that contains the selected country variable
// formname:           the form name
// TABLES: zones
  function tep_js_zone_list($selected_country_var, $form_name) {
    $country_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $num_country = 1;
    while ($country_values = tep_db_fetch_array($country_query)) {
      if ($num_country == 1) {
        echo '  if (' . $selected_country_var . ' == "' . $country_values['zone_country_id'] . '") {' . "\n";
      } else {
        echo '  else if (' . $selected_country_var . ' == "' . $country_values['zone_country_id'] . '") {' . "\n";
      }

      $state_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $country_values['zone_country_id'] . "' order by zone_name");

      $num_state = 1;
      while ($state_values = tep_db_fetch_array($state_query)) {
        if ($num_state == 1) {
          echo '    ' . $form_name . '.zone_id.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
        }
        echo '    ' . $form_name . '.zone_id.options[' . $num_state . '] = new Option("' . $state_values['zone_name'] . '", "' . $state_values['zone_id'] . '");' . "\n";
        $num_state++;
      }
      $num_country++;
      echo '  }' . "\n";
    }
    echo '  else {' . "\n" .
         '    ' . $form_name . '.zone_id.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
         '  }' . "\n";
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . $type . '" name="' . $name . '"';
    if ( ($GLOBALS[$name]) && ($reinsert_value) ) {
      $field .= ' value="' . trim($GLOBALS[$name]) . '"';
    } elseif ($value != '') {
      $field .= ' value="' . trim($value) . '"';
    }
    if ($parameters != '') {
      $field .= ' ' . $parameters;
    }
    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '') {
    $field = tep_draw_input_field($name, $value, 'maxlength="40"', 'password', false);

    return $field;
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false) {
    $selection = '<input type="' . $type . '" name="' . $name . '"';
    if ($value != '') {
      $selection .= ' value="' . $value . '"';
    }
    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ($value && $GLOBALS[$name] == $value) ) {
      $selection .= ' CHECKED';
    }
    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false) {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false) {
    return tep_draw_selection_field($name, 'radio', $value, $checked);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $reinsert_value = true) {
    $field = '<textarea name="' . $name . '" wrap="' . $wrap . '" cols="' . $width . '" rows="' . $height . '">';
    if ( ($GLOBALS[$name]) && ($reinsert_value) ) {
      $field .= $GLOBALS[$name];
    } elseif ($text != '') {
      $field .= $text;
    }
    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '') {
    $field = '<input type="hidden" name="' . $name . '" value="';
    if ($value != '') {
      $field .= trim($value);
    } else {
      $field .= trim($GLOBALS[$name]);
    }
    $field .= '">';

    return $field;
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '') {
    $field = '<select name="' . $name . '">';
    for ($i=0; $i<sizeof($values); $i++) {
      $field .= '<option value="' . $values[$i]['id'] . '"';
      if ( ($GLOBALS[$name] == $values[$i]['id']) || ($default == $values[$i]['id']) ) {
        $field .= ' SELECTED';
      }
      $field .= '>' . $values[$i]['text'] . '</option>';
    }
    $field .= '</select>';

    return $field;
  }
?>
