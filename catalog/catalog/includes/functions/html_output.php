<?php
/*
  $Id: html_output.php,v 1.63 2005/03/29 22:56:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $osC_Session, $SID, $osC_Services;

    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

    if (tep_not_null($parameters)) {
      $link .= $page . '?' . tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($osC_Session->is_started == true) && (SERVICE_SESSION_FORCE_COOKIE_USAGE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = $osC_Session->name . '=' . $osC_Session->id;
        }
      }
    }

    if ( ($search_engine_safe == true) && $osC_Services->isStarted('sefu')) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($_sid)) {
      $link .= $separator . tep_output_string($_sid);
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
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = (int)$image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = (int)$image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = (int)$image_size[0];
          $height = (int)$image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $osC_Session;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $osC_Session->value('language') . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $osC_Session;

    return tep_image(DIR_WS_LANGUAGES . $osC_Session->value('language') . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

  function osc_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
    if (PHP_VERSION < 4.1) {
      global $_GET, $_POST;
    }

    $field_value = $value;

    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ($reinsert_value === true) {
      if (isset($_GET[$name])) {
        $field_value = $_GET[$name];
      } elseif (isset($_POST[$name])) {
        $field_value = $_POST[$name];
      }
    }

    if (strlen(trim($field_value)) > 0) {
      $field .= ' value="' . tep_output_string($field_value) . '"';
    }

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= '>';

    if ($required === true) {
      $field .= '&nbsp;<span class="inputRequirement">*</span>';
    }

    return $field;
  }

  function osc_draw_password_field($name, $parameters = '', $required = false) {
    return osc_draw_input_field($name, '', $parameters, $required, 'password', false);
  }

  function osc_draw_selection_field($name, $type, $values, $default = '', $parameters = '', $required = false, $separator = '&nbsp;&nbsp;') {
    if (PHP_VERSION < 4.1) {
      global $_GET, $_POST;
    }

    if (!is_array($values)) {
      $values = array($values);
    }

    if (isset($_GET[$name])) {
      $default = $_GET[$name];
    } elseif (isset($_POST[$name])) {
      $default = $_POST[$name];
    }

    $field = '';

    foreach ($values as $key => $value) {
      if (is_array($value)) {
        $selection_value = $value['id'];
        $selection_text = '&nbsp;' . $value['text'];
      } else {
        $selection_value = $value;
        $selection_text = '';
      }

      $field .= '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

      if (!empty($selection_value)) {
        $field .= ' value="' . tep_output_string($selection_value) . '"';
      }

      if ((is_bool($default) && $default === true) || (!empty($default) && ($default == $selection_value))) {
        $field .= ' CHECKED';
      }

      if (!empty($parameters)) {
        $field .= ' ' . $parameters;
      }

      $field .= '>' . $selection_text . $separator;
    }

    $field = substr($field, 0, strlen($field)-strlen($separator));

    if ($required === true) {
      $field .= '&nbsp;<span class="inputRequirement">*</span>';
    }

    return $field;
  }

  function osc_draw_checkbox_field($name, $values, $default = '', $parameters = '', $required = false, $separator = '&nbsp;&nbsp;') {
    return osc_draw_selection_field($name, 'checkbox', $values, $default, $parameters, $required, $separator);
  }

  function osc_draw_radio_field($name, $values, $default = '', $parameters = '', $required = false, $separator = '&nbsp;&nbsp;') {
    return osc_draw_selection_field($name, 'radio', $values, $default, $parameters, $required, $separator);
  }

  function osc_draw_textarea_field($name, $value = '', $width = '60', $height = '5', $wrap = 'soft', $parameters = '', $reinsert_value = true, $required = false) {
    if (PHP_VERSION < 4.1) {
      global $_GET, $_POST;
    }

    if ($reinsert_value === true) {
      if (isset($_GET[$name])) {
        $value = $_GET[$name];
      } elseif (isset($_POST[$name])) {
        $value = $_POST[$name];
      }
    }

    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= '>' . $value . '</textarea>';

    if ($required === true) {
      $field .= '&nbsp;<span class="inputRequirement">*</span>';
    }

    return $field;
  }

  function osc_draw_hidden_field($name, $value = '', $parameters = '') {
    if (PHP_VERSION < 4.1) {
      global $_GET, $_POST;
    }

    if (empty($value)) {
      if (isset($_GET[$name])) {
        $value = $_GET[$name];
      } elseif (isset($_POST[$name])) {
        $value = $_POST[$name];
      }
    }

    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (!empty($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (!empty($parameters)) {
      $field .= ' ' . $parameters;
    }

    $field .= '>';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $osC_Session, $SID;

    if (($osC_Session->is_started == true) && tep_not_null($SID)) {
      return osc_draw_hidden_field($osC_Session->name, $osC_Session->id);
    }
  }

  function osc_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    if (PHP_VERSION < 4.1) {
      global $_GET, $_POST;
    }

    $field = '<select name="' . tep_output_string($name) . '"';

    if (!empty($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    $default_value = $default;

    if (isset($_GET[$name])) {
      $default_value = $_GET[$name];
    } elseif (isset($_POST[$name])) {
      $default_value = $_POST[$name];
    }

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';

      if ($default_value == $values[$i]['id']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }

    $field .= '</select>';

    if ($required === true) {
      $field .= '&nbsp;<span class="inputRequirement">*</span>';
    }

    return $field;
  }

  function tep_draw_date_pull_down_menu($name, $value = '', $default_today = true, $show_days = true, $use_month_names = true, $year_range_start = '0', $year_range_end  = '1') {
    $params = '';

// days pull down menu
    $days_select_string = '';

    if ($show_days === true) {
      $params = 'onChange="updateDatePullDownMenu(this.form, \'' . $name . '\');"';

      $days_in_month = ($default_today === true) ? date('t') : 31;

      $days_array = array();
      for ($i=1; $i<=$days_in_month; $i++) {
        $days_array[] = array('id' => $i,
                              'text' => $i);
      }

      if (isset($GLOBALS[$name . '_days'])) {
        $days_default = $GLOBALS[$name . '_days'];
      } elseif (!empty($value)) {
        $days_default = date('j', $value);
      } elseif ($default_today === true) {
        $days_default = date('j');
      } else {
        $days_default = 1;
      }

      $days_select_string = osc_draw_pull_down_menu($name . '_days', $days_array, $days_default);
    }

// months pull down menu
    $months_array = array();
    for ($i=1; $i<=12; $i++) {
      $months_array[] = array('id' => $i,
                              'text' => (($use_month_names === true) ? strftime('%B', mktime(0, 0, 0, $i)) : $i));
    }

    if (isset($GLOBALS[$name . '_months'])) {
      $months_default = $GLOBALS[$name . '_months'];
    } elseif (!empty($value)) {
      $months_default = date('n', $value);
    } elseif ($default_today === true) {
      $months_default = date('n');
    } else {
      $months_default = 1;
    }

    $months_select_string = osc_draw_pull_down_menu($name . '_months', $months_array, $months_default, $params);

// year pull down menu
    $year = date('Y');

    $years_array = array();
    for ($i = ($year - $year_range_start); $i <= ($year + $year_range_end); $i++) {
      $years_array[] = array('id' => $i,
                             'text' => $i);
    }

    if (isset($GLOBALS[$name . '_years'])) {
      $years_default = $GLOBALS[$name . '_years'];
    } elseif (!empty($value)) {
      $years_default = date('Y', $value);
    } elseif ($default_today === true) {
      $years_default = $year;
    } else {
      $years_default = $year - $year_range_start;
    }

    $years_select_string = osc_draw_pull_down_menu($name . '_years', $years_array, $years_default, $params);

    return $days_select_string . $months_select_string . $years_select_string;
  }
?>
