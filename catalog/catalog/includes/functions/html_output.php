<?php
/*
  $Id: html_output.php,v 1.49 2003/02/11 01:31:02 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }
 
////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTPS_SERVER . DIR_WS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

    if (tep_not_null($parameters)) {
      $link .= $page . '?' . $parameters;
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from HTTP and HTTPS servers or when SID is defined
    if ( (ENABLE_SSL == true ) && ($connection == 'SSL') && ($add_session_id == true) ) {
      $sid = tep_session_name() . '=' . tep_session_id();
    } elseif ( ($add_session_id == true) && (tep_not_null(SID)) ) {
      $sid = SID;
    }

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }
    
    if (isset($sid)) {
      $link .= $separator . $sid;
    }

    return $link;
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_parse_input_field_data($src, array('"' => '&quot;')) . '" border="0" alt="' . tep_parse_input_field_data($alt, array('"' => '&quot;')) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_parse_input_field_data($alt, array('"' => '&quot;')) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_parse_input_field_data($width, array('"' => '&quot;')) . '" height="' . tep_parse_input_field_data($height, array('"' => '&quot;')) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_parse_input_field_data(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, array('"' => '&quot;')) . '" border="0" alt="' . tep_parse_input_field_data($alt, array('"' => '&quot;')) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_parse_input_field_data($alt, array('"' => '&quot;')) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '" action="' . tep_parse_input_field_data($action, array('"' => '&quot;')) . '" method="' . tep_parse_input_field_data($method, array('"' => '&quot;')) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . tep_parse_input_field_data($type, array('"' => '&quot;')) . '" name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '"';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_parse_input_field_data($GLOBALS[$name], array('"' => '&quot;')) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_parse_input_field_data($value, array('"' => '&quot;')) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . tep_parse_input_field_data($type, array('"' => '&quot;')) . '" name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_parse_input_field_data($value, array('"' => '&quot;')) . '"';

    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ( (isset($value)) && ($GLOBALS[$name] == $value) ) ) {
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '" wrap="' . tep_parse_input_field_data($wrap, array('"' => '&quot;')) . '" cols="' . tep_parse_input_field_data($width, array('"' => '&quot;')) . '" rows="' . tep_parse_input_field_data($height, array('"' => '&quot;')) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= $GLOBALS[$name];
    } elseif (tep_not_null($text)) {
      $field .= $text;
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '" value="';

    if (tep_not_null($value)) {
      $field .= tep_parse_input_field_data($value, array('"' => '&quot;'));
    } else {
      $field .= tep_parse_input_field_data($GLOBALS[$name], array('"' => '&quot;'));
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '">';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    if (defined('SID') && tep_not_null(SID)) return tep_draw_hidden_field(tep_session_name(), tep_session_id());
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = $GLOBALS[$name];

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_parse_input_field_data($values[$i]['id'], array('"' => '&quot;')) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_parse_input_field_data($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
?>
