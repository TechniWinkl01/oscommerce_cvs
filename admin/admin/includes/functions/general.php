<?
  function tep_exit() {
    if (EXIT_AFTER_REDIRECT == 1) {
     return exit();
    }
  }

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

  function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . $src . '" border="0" alt=" ' . $alt . ' "';
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    $image .= '>';

    return $image;
  }

  function tep_image_submit($src, $alt = '', $width = '', $height = '', $params = '') {
    $image_submit = '<input type="image" src="' . $src . '" border="0" alt=" ' . $alt . ' "';
    if ($width) {
      $image_submit .= ' width="' . $width . '"';
    }
    if ($width) {
      $image_submit .= ' height="' . $height . '"';
    }
    $image_submit .= '>';

    return $image_submit;
  }

  function tep_black_line() {
    $black_line = tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');

    return $black_line;
  }

  function tep_currency_format($number, $calculate_currency_value = true, $currency_value = CURRENCY_VALUE) {
    global $currency_rates;

    $currency_query = tep_db_query("select symbol_left, symbol_right, decimal_point, thousands_point, decimal_places from " . TABLE_CURRENCIES . " where code = '" . $currency_value . "'");
    $currency = tep_db_fetch_array($currency_query);

    if ($calculate_currency_value == true) {
      if (strlen($currency_value) == 3) {
        $rate = $currency_rates[$currency_value]; // read from catalog/includes/data/rates.php - the value is in /catalog/includes/languages/<language>.php
      } else {
        $rate = 1;
      }
      $number2currency = $currency['symbol_left'] . number_format(($number * $rate), $currency['decimal_places'], $currency['decimal_point'], $currency['thousands_point']) . $currency['symbol_right'];
    } else {
      $number2currency = $currency['symbol_left'] . number_format($number, $currency['decimal_places'], $currency['decimal_point'], $currency['thousands_point']) . $currency['symbol_right'];
    }

    return $number2currency;
  }

  function tep_customers_name($customers_id) {
    $customers = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customers_id . "'");
    $customers_values = tep_db_fetch_array($customers);

    $customers_name = $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname'];

    return $customers_name;
  }

  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if ($current_category_id == '') {
      $cPath_new = implode('_', $cPath_array);
    } else {
      if (sizeof($cPath_array) == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $cPath_array[(sizeof($cPath_array)-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);
        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);
        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i=0; $i<(sizeof($cPath_array)-1); $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i=0; $i<sizeof($cPath_array); $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;
        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    }

    return 'cPath=' . $cPath_new;
  }

  function tep_get_all_get_params($exclude_array = '') {
    global $HTTP_GET_VARS;

    if ($exclude_array == '') $exclude_array = array();

    $get_url = '';

    reset($HTTP_GET_VARS);
    while (list($key, $value) = each($HTTP_GET_VARS)) {
      if (($key != session_name()) && ($key != 'error') && (!tep_in_array($key, $exclude_array))) $get_url .= $key . '=' . $value . '&';
    }

    return $get_url;
  }

  function tep_date_long($raw_date) {
    if (strlen($raw_date) == 19) {
      $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 6, 2),substr($raw_date, 8, 2),substr($raw_date, 0, 4)));
    } elseif (strlen($raw_date) == 14) {
      $date_formated = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, 6, 2),substr($raw_date, 0, 4)));
    } else {
      $date_formated = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, -2),substr($raw_date, 0, 4)));
    }

    return $date_formated;
  }

  function tep_date_short($raw_date) {
    if (strlen($raw_date) == 19) {
      $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 6, 2),substr($raw_date, 8, 2),substr($raw_date, 0, 4)));
    } elseif (strlen($raw_date) == 14) {
      $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, 6, 2),substr($raw_date, 0, 4)));
    } else {
      $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, -2),substr($raw_date, 0, 4)));
    }

    return $date_formated;
  }

  function tep_datetime_short($raw_datetime) {
    $datetime_formated = strftime(DATE_TIME_FORMAT, mktime(substr($raw_datetime, 11, 2),substr($raw_datetime, 14, 2),substr($raw_datetime, 17, 2),substr($raw_datetime, 6, 2),substr($raw_datetime, 8, 2),substr($raw_datetime, 0, 4)));
    return $datetime_formated;
  }

  function tep_array_merge($array1, $array2, $array3 = '') {

    if ($array3 == '') $array3 = array();

    if (function_exists('array_merge')) {
      $array_merged = array_merge($array1, $array2, $array3);
    } else {
      while (list($key, $val) = each($array1)) $array_merged[$key] = $val;
      while (list($key, $val) = each($array2)) $array_merged[$key] = $val;
      while (list($key, $val) = @each($array3)) $array_merged[$key] = $val;
    }

    return (array) $array_merged;
  }

  function tep_in_array($lookup_value, $lookup_array) {
    if (function_exists('in_array')) {
      if (in_array($lookup_value, $lookup_array)) return true;
    } else {
      reset($lookup_array);
      while (list($key, $value) = each($lookup_array)) {
        if ($value == $lookup_value) return true;
      }
    }

    return false;
  }

  function tep_categories_name_with_parent($categories_id) {
    global $languages_id;

    $categories_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $categories_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "'");
    $categories = tep_db_fetch_array($categories_query);

    $categories_parent_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $categories['parent_id'] . "' and language_id = '" . $languages_id . "'");
    $categories_parent = tep_db_fetch_array($categories_parent_query);

    $categories_name = $categories['categories_name'];
    if (tep_db_num_rows($categories_parent_query) > 0) $categories_name .= ' (' . $categories_parent['categories_name'] . ')';

    return $categories_name;
  }

  function tep_products_categories_array($products_id, $return_id = false) {
    $products_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p2c.products_id = '" . $products_id . "'");
    while ($products_categories = tep_db_fetch_array($products_categories_query)) {
      if ($return_id) {
        $products_categories_array[] = $products_categories['categories_id'];
      } else {
        $products_categories_array[] = tep_categories_name_with_parent($products_categories['categories_id']);
      }
    }

    return $products_categories_array;
  }

  function tep_products_categories_info_box($products_id) {
    $products_categories_array = tep_products_categories_array($products_id);

    for ($i=0; $i<sizeof($products_categories_array); $i++) $products_categories .= '<b>' . $products_categories_array[$i] . '</b><br>&nbsp;';

    return $products_categories;
  }

  function tep_categories_pull_down($parameters, $exclude = '') {
    global $languages_id;

    $select_string = '<select ' . $parameters . '>';
    $categories_all_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by cd.categories_name");
    while ($categories_all = tep_db_fetch_array($categories_all_query)) {
      if (!tep_in_array($categories_all['categories_id'], (array)$exclude)) {
        $categories_parent_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $categories_all['parent_id'] . "' and language_id = '" . $languages_id . "'");
        $categories_parent = tep_db_fetch_array($categories_parent_query);
        $select_string .= '<option value="' . $categories_all['categories_id'] . '">' . $categories_all['categories_name'];
        if (tep_db_num_rows($categories_parent_query) > 0) $select_string .= ' (' . $categories_parent['categories_name'] . ')';
        $select_string .= '</option>';
      }
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_products_pull_down($parameters) {
    global $languages_id;

    $select_string = '<select ' . $parameters . '>';
    $products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '$languages_id' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_countries_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while ($countries = tep_db_fetch_array($countries_query)) {
      $select_string .= '<option value="' . $countries['countries_id'] . '"';
      if ($selected == $countries['countries_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $countries['countries_name'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_options_name($options_id) {
    global $languages_id;

    $options = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_id . "' and language_id = '" . $languages_id . "'");
    $options_values = tep_db_fetch_array($options);

    $options_name = $options_values['products_options_name'];

    return $options_name;
  }

  function tep_values_name($values_id) {
    global $languages_id;

    $values = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $values_id . "' and language_id = '" . $languages_id . "'");
    $values_values = tep_db_fetch_array($values);

    $values_name = $values_values['products_options_values_name'];

    return $values_name;
  }

  function tep_info_image($image_source, $image_alt) {
    $image_size = @getimagesize(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG . $image_source);
    if ($image_size) {
      $image = tep_image(DIR_WS_CATALOG . $image_source, $image_alt, $image_size[0], $image_size[1]);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }

  function tep_break_string($string, $len) {
    $l = 0;
    $output = '';
    for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l == $len) {
        $l = 0;
        $output .= '-';
      }
      $output .= $char;
    }

    return $output;
  }

  function tep_get_country_name($country_id) {
    $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");

    if (!tep_db_num_rows($country_query)) {
      $country_name = $country_id;
    } else {
      $country = tep_db_fetch_array($country_query);
      $country_name = $country['countries_name'];
    }

    return $country_name;
  }

  function tep_not_null($value) {
    switch ($value) {
      case is_string($value):
                              if (($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
                                return true;
                              } else {
                                return false;
                              }
                              break;
      default: return false;
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //   tep_browser_detect - what broser is the customer using?
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////

  function tep_browser_detect($component) { 
    global $HTTP_USER_AGENT; 
    $result = stristr($HTTP_USER_AGENT,$component); 
    return $result; 
  } 

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //   tep_get_zone_list
  //
  //   - make a popup list of states and provinces
  //
  //   Written By: Kenneth Cheng
  //
  //   parameters
  //   ----------
  //
  //   popup_name:     the name attribute you want for the <SELECT> tag
  //
  //   country_code:   the default selected value [optional]
  //
  //   selected:       the default selected value [optional]
  //
  //   javascript:     javascript for the <SELECT> tag, i.e.
  //                   onChange="this.form.submit()" [optional]
  //
  //   size:           size [optional]
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_zone_list ($popup_name, $country_code="", $selected="", $javascript="", $size=1) {

    // start building the popup menu
    $result = "<select name=\"$popup_name\"";
    
    if ($size != 1)
      $result .= " size=\"$size\"";
      
    if ($javascript)
      $result .= " " . $javascript;
    
    $result .= ">\n";
    
    $result .= "<option value=\"\">" . PLEASE_SELECT . "\n";

    // Preset the width of the drop-down for Netscape
    //
    // 53 "&nbsp;" would provide the width for my longer state/province name
    // this number should be customized for your need
    // 
    if ( !tep_browser_detect('MSIE') && tep_browser_detect('Mozilla/4') ) {
      for ($i=0; $i<53; $i++)
        $result .= "&nbsp;";
    }

    $state_prov_result = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_code . "' order by zone_name");
      
    $populated = 0;
    while ($state_prov_values = tep_db_fetch_array($state_prov_result)) {
      $populated++;
      // printed SELECTED if an item was previously selected
      // so we maintain the state
      if ($selected == $state_prov_values[zone_id]) {
        $result .= "<option value=\"$state_prov_values[zone_id]\" SELECTED>$state_prov_values[zone_name]\n";
      } else {
        $result .= "<option value=\"$state_prov_values[zone_id]\">$state_prov_values[zone_name]\n";
      }
    }

    // Create dummy options for Netscape to preset the height of the drop-down
    if ($populated == 0) {
      if ( !tep_browser_detect('MSIE') && tep_browser_detect('Mozilla/4') ) { 
        for ($i=0; $i<9; $i++) {
          $result .= "\n<option value=\"\">";
        }
      }
    }

    // finish the popup menu
    $result .= "\n</select>\n";
    
    return $result;

  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_js_zone_list
  //
  // Arguments   : SelectedCountryVar        string that contains the SelectedCountry variable
  //                                         name
  //               FormName                  string that contains the form object name
  //
  // Return      : none
  //
  // Description : Function used to construct part of the JavaScript code for dynamically
  //               updating the State/Province Drop-Down list
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_js_zone_list($SelectedCountryVar, $FormName, $zoneInputName = 'zone_id') {
    $country_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $NumCountry=1;
    while ($country_values = tep_db_fetch_array($country_query)) {
      if ($NumCountry == 1)
        print ("  if (" . $SelectedCountryVar . " == \"" . $country_values['zone_country_id'] . "\") {\n");
      else 
        print ("  else if (" . $SelectedCountryVar . " == \"" . $country_values['zone_country_id'] . "\") {\n");
  
      $state_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $country_values['zone_country_id'] . "' order by zone_name");
      
      $NumState = 1;
      while ($state_values = tep_db_fetch_array($state_query)) {
        if ($NumState == 1)
          print ("    " . $FormName . "." . $zoneInputName . ".options[0] = new Option(\"" . PLEASE_SELECT . "\", \"\");\n");
        print ("    " . $FormName . "." . $zoneInputName . ".options[$NumState] = new Option(\"" . $state_values['zone_name'] . "\", \"" . $state_values['zone_id'] . "\");\n");
        $NumState++;
      }
      $NumCountry++;
      print ("  }\n");
    }
  }

  function tep_tax_classes_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $classes_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($classes = tep_db_fetch_array($classes_query)) {
      $select_string .= '<option value="' . $classes['tax_class_id'] . '"';
      if ($selected == $classes['tax_class_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $classes['tax_class_title'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

//////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_format_address
//
// Arguments	: customers_id, address_id, html
//
// Return	: properly formatted address
//
// Description	: This function will lookup the Addres format from the countries database
//		  and properly format the address label.
//
//////////////////////////////////////////////////////////////////////////////////////////

function tep_address_format($format_id, $delivery_values, $html, $boln, $eoln) {
  $format = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . $format_id . "'");
  $format_values = tep_db_fetch_array($format);
  $firstname = addslashes($delivery_values['firstname']);
  $lastname = addslashes($delivery_values['lastname']);
  $street = addslashes($delivery_values['street_address']);
  $suburb = addslashes($delivery_values['suburb']);
  $city = addslashes($delivery_values['city']);
  $state = addslashes($delivery_values['state']);
  $country_id = $delivery_values['country_id'];
  $zone_id = $delivery_values['zone_id'];
  $postcode = addslashes($delivery_values['postcode']);
  $zip = $postcode;
  $country = tep_get_country_name($country_id);
  $state = tep_get_zone_code($country_id, $zone_id, $state);

  $statecomma = '';
  $streets = $street;
  if ($suburb != '') $streets = $street . $cr . $suburb;
  if ($firstname == '') $firstname = addslashes($delivery_values['name']);
  if ($country == '') $country = addslashes($delivery_values['country']);
  if ($state != '') $statecomma = $state . ', ';
  if ($html == 0) { // Text Mode
    $CR = $eoln;
    $cr = $CR;
    $HR = '----------------------------------------';
    $hr = '----------------------------------------';
  } else {
    if ($html == 1) { // HTML Mode
      $HR = '<HR>';
      $hr = '<hr>';
      if ($boln == '' && $eoln == "\n") { // Valu not specified, use rational defaults
        $CR = '<BR>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    }
  }

  $fmt = $format_values['format'];
  eval("\$address = \"$fmt\";");
  $address = stripslashes($address);
  return $boln . $address . $eoln;
}

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_zone_code
  //
  // Arguments   : country           country code string
  //               zone              state/province zone_id
  //               def_state         default string if zone==0
  //
  // Return      : state_prov_code   state/province code
  //
  // Description : Function to retrieve the state/province code (as in FL for Florida etc)
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_zone_code($country, $zone, $def_state) {

    $state_prov_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' and zone_id = '" . $zone . "'");

    if (!tep_db_num_rows($state_prov_query)) {
      $state_prov_code = $def_state;
    }
    else {
      $state_prov_values = tep_db_fetch_array($state_prov_query);
      $state_prov_code = $state_prov_values['zone_code'];
    }
    
    return $state_prov_code;
  }

  function tep_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        $uprid = $uprid . '{' . $option . '}' . $value;
      }
    }

    return $uprid;
  }

  function tep_get_prid($uprid) {
    $pieces = explode ('{', $uprid);

    return $pieces[0];
  }

  function tep_get_languages() {
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']
                                );
    }

    return $languages_array;
  }

  function tep_get_category_name($category_id, $language_id) {
    $category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_name'];
  }

  function tep_get_products_name($product_id, $language_id = 0) {
    global $languages_id;

    if ($language_id == 0) $language_id = $languages_id;
    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }

  function tep_get_products_description($product_id, $language_id) {
    $product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_description'];
  }

  function tep_get_products_url($product_id, $language_id) {
    $product_query = tep_db_query("select products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_url'];
  }

////
// Return the manufacturers URL in the needed language
// TABLES: manufacturers_info
  function tep_get_manufacturer_url($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . $manufacturer_id . "' and languages_id = '" . $language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_url'];
  }

////
// Wrapper for class_exists() function
// This function is not available in all PHP versions so we test it before using it.
  function tep_class_exists($class_name) {
    if (function_exists('class_exists')) {
      return class_exists($class_name);
    } else {
      return true;
    }
  }

////
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
  function tep_products_in_category_count($categories_id, $include_deactivated = false) {
    $products_count = 0;

    if ($include_deactivated) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $categories_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $categories_id . "'");
    }

    $products = tep_db_fetch_array($products_query);

    $products_count += $products['total'];

    $childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $categories_id . "'");
    if (tep_db_num_rows($childs_query)) {
      while ($childs = tep_db_fetch_array($childs_query)) {
        $products_count += tep_products_in_category_count($childs['categories_id'], $include_deactivated);
      }
    }

    return $products_count;
  }

////
// Count how many subcategories exist in a category
// TABLES: categories
  function tep_childs_in_category_count($categories_id) {
    $categories_count = 0;

    $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $categories_id . "'");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $categories_count += tep_childs_in_category_count($categories['categories_id']);
    }

    return $categories_count;
  }

  function tep_redirect($destination, $parameters = '', $connection = 'NONSSL') {
    header('Location: ' . tep_href_link($destination, $parameters, $connection));
  }

////
// Returns an array with countries
// TABLES: countries
  function tep_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if ($countries_id) {
      if ($with_iso_codes) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }

    return $countries_array;
  }

////
// Returns a pull down select list with all countries
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
// Alias function for Store configuration values in the Administration Tool
  function tep_cfg_pull_down_country_list($country_id) {
    return tep_get_country_list('configuration_value', $country_id);
  }

////
// Sets the status of a banner
  function tep_set_banner_status($banners_id, $status) {
    return tep_db_query("update " . TABLE_BANNERS . " set status = '" . $status . "', date_status_change = now() where banners_id = '" . $banners_id . "'");
  }
?>
