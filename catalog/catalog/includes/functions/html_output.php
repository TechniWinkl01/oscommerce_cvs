<?php
/*
  $Id: html_output.php,v 1.1 2001/06/04 17:13:14 hpdl Exp $

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
      $link = HTTP_SERVER . DIR_WS_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL) {
        $link = HTTPS_SERVER . DIR_WS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }
    // Put the session in the URL if we are we are using cookies and changing to SSL
    // Otherwise, we loose the cookie and our session
    if (!SID && !getenv(HTTPS) && $connection=='SSL')
      $sess = tep_session_name() . '=' . tep_session_id();
    else
      $sess = SID;
    if ($parameters == '') {
      $link = $link . $page . '?' . $sess;
    } else {
      $link = $link . $page . '?' . $parameters . '&' . $sess;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
    if ( (($src == '') || ($src == 'images/')) && (!IMAGE_REQUIRED) ) {
      return;
    }

    $image = '<img src="' . $src . '" border="0" alt=" ' . htmlspecialchars(StripSlashes($alt)) . ' "';

    if ( (CONFIG_CALCULATE_IMAGE_SIZE) && ((!$width) || (!$height)) ) {
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
      } elseif (!IMAGE_REQUIRED) {
        return '';
      }
    }

    if ( ($width) && ($height) ) {
      $image .= ' width="' . $width . '" height="' . $height . '"';
    }

    if ($params != '') {
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
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $params = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $params);
  }

////
// Draw a 1 pixel black line
  function tep_black_line() {
    global $black_line;

    $black_line = tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');

    return $black_line;
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

    echo $result;
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
      $result .= '<option value="">' . PLEASE_SELECT . '</option>';
    } else {
      $result .= '<option value="">' . TYPE_BELOW . '</option>';
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
          $result .= '<option value=""></option>';
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

      $state_query = tep_db_query("select zones.zone_name, zones.zone_id from " . TABLE_ZONES . " where zones.zone_country_id = '" . $country_values['zone_country_id'] . "' order by zones.zone_name");

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
// Hide form elements
  function tep_hide_fields($fields_array) {
    $result = '';
    reset($fields_array);
    while (list($key, $value) = each($fields_array)) {
      $result .= '<input type="hidden" name="' . $value . '" value="' . $GLOBALS[$value] . '">';
    }

    return $result;
  }
?>