<?php
/*
  $Id: html_output.php,v 1.42 2002/08/02 11:13:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

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

    if (isset($sid)) {
      $link .= $separator . $sid;
    }

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);
    }

    return $link;
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
    if ( (($src == '') || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

    $image = '<img src="' . $src . '" border="0"';

    if (tep_not_null($alt)) {
      $image .= ' alt="' . htmlspecialchars($alt) . '" title=" ' . htmlspecialchars($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && ((!$width) || (!$height)) ) {
      if ($image_size = @getimagesize($src)) {
        if ( (!$width) && ($height) ) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif ( ($width) && (!$height) ) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif ( (!$width) && (!$height) ) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if ( ($width) && ($height) ) {
      $image .= ' width="' . $width . '" height="' . $height . '"';
    }

    if (tep_not_null($params)) $image .= ' ' . $params;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt) {
    global $language;

    $image_submit = '<input type="image" src="' . DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image . '" border="0"';

    if (tep_not_null($alt)) {
      $image_submit .= ' alt="' . htmlspecialchars($alt) . '" title=" ' . htmlspecialchars($alt) . ' ">';
    }

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $params = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $params);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . $name . '" action="' . $action . '" method="' . $method . '"';
    if ($parameters) $form .= ' ' . $parameters;
    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . $type . '" name="' . $name . '"';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . htmlspecialchars(trim($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . htmlspecialchars(trim($value)) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '') {
    return tep_draw_input_field($name, $value, 'maxlength="40"', 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false) {
    $selection = '<input type="' . $type . '" name="' . $name . '"';

    if (tep_not_null($value)) $selection .= ' value="' . $value . '"';

    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ( (isset($value)) && ($GLOBALS[$name] == $value) ) ) {
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
  function tep_draw_hidden_field($name, $value = '') {
    $field = '<input type="hidden" name="' . $name . '" value="';
    if (tep_not_null($value)) {
      $field .= htmlspecialchars(trim($value));
    } else {
      $field .= htmlspecialchars(trim($GLOBALS[$name]));
    }
    $field .= '">';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    if (tep_not_null(SID)) return tep_draw_hidden_field(tep_session_name(), tep_session_id());
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . $name . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    for ($i=0; $i<sizeof($values); $i++) {
      $field .= '<option value="' . $values[$i]['id'] . '"';
      if ( ($GLOBALS[$name] == $values[$i]['id']) || ($default == $values[$i]['id']) ) {
        $field .= ' SELECTED';
      }
      $field .= '>' . htmlspecialchars($values[$i]['text']) . '</option>';
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
    for ($i=0; $i<sizeof($countries); $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
?>
