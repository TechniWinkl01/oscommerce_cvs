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

  function tep_image($src, $width, $height, $border, $alt) {
    $image = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '"';
    if ($alt != '') {
      $image .= ' alt=" ' . $alt . ' "';
    }
    $image .= '>';

    return $image;
  }

  function tep_image_submit($src, $width, $height, $border, $alt) {
    $image_submit = '<input type="image" src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '" alt=" ' . $alt . ' ">';

    return $image_submit;
  }

  function tep_black_line() {
    $black_line = tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '100%', '1', '0', '');

    return $black_line;
  }

  function tep_currency_format($number, $calculate_currency_value = true, $currency_value = CURRENCY_VALUE) {
    global $currency_rates;

    $currency_query = tep_db_query("select symbol_left, symbol_right, decimal_point, thousands_point, decimal_places from currencies where code = '" . $currency_value . "'");
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
    $customers = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $customers_id . "'");
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
        $last_category_query = tep_db_query("select parent_id from categories where categories_id = '" . $cPath_array[(sizeof($cPath_array)-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);
        $current_category_query = tep_db_query("select parent_id from categories where categories_id = '" . $current_category_id . "'");
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
    if (Strlen($raw_date) == 14) {
      $date_formated = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, 6, 2),substr($raw_date, 0, 4)));
    } else {
      $date_formated = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, -2),substr($raw_date, 0, 4)));
    }

    return $date_formated;
  }

  function tep_date_short($raw_date) {
    if (strlen($raw_date) == 14) {
      $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, 6, 2),substr($raw_date, 0, 4)));
    } else {
      $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, -2),substr($raw_date, 0, 4)));
    }

    return $date_formated;
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

    $categories_query = tep_db_query("select cd.categories_name, c.parent_id from categories c, categories_description cd where c.categories_id = '" . $categories_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "'");
    $categories = tep_db_fetch_array($categories_query);

    $categories_parent_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $categories['parent_id'] . "' and language_id = '" . $languages_id . "'");
    $categories_parent = tep_db_fetch_array($categories_parent_query);

    $categories_name = $categories['categories_name'];
    if (tep_db_num_rows($categories_parent_query) > 0) $categories_name .= ' (' . $categories_parent['categories_name'] . ')';

    return $categories_name;
  }

  function tep_products_categories_array($products_id, $return_id = false) {
    $products_categories_query = tep_db_query("select categories_id from products_to_categories p2c where p2c.products_id = '" . $products_id . "'");
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
    $categories_all_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by cd.categories_name");
    while ($categories_all = tep_db_fetch_array($categories_all_query)) {
      if (!tep_in_array($categories_all['categories_id'], (array)$exclude)) {
        $categories_parent_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $categories_all['parent_id'] . "' and language_id = '" . $languages_id . "'");
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
    $products_query = tep_db_query("select p.products_id, pd.products_name from products p, products_description pd where p.products_id = pd.products_id and pd.language_id = '$languages_id' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_countries_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $countries_query = tep_db_query("select countries_id, countries_name from countries order by countries_name");
    while ($countries = tep_db_fetch_array($countries_query)) {
      $select_string .= '<option value="' . $countries['countries_id'] . '"';
      if ($selected == $countries['countries_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $countries['countries_name'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_options_name($options_id) {
    global $options_name;

    $options = tep_db_query("select products_options_name from products_options where products_options_id = '" . $options_id . "'");
    $options_values = tep_db_fetch_array($options);

    $options_name = $options_values['products_options_name'];

    return $options_name;
  }

  function tep_values_name($values_id) {
    global $values_name;

    $values = tep_db_query("select products_options_values_name from products_options_values where products_options_values_id = '" . $values_id . "'");
    $values_values = tep_db_fetch_array($values);

    $values_name = $values_values['products_options_values_name'];

    return $values_name;
  }

  function tep_info_image($image_source, $image_alt) {
    $image_size = @getimagesize(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG . $image_source);
    if ($image_size) {
      $image = tep_image(DIR_WS_CATALOG . $image_source, $image_size[0], $image_size[1], 0, $image_alt);
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
    $country_query = tep_db_query("select countries_name from countries where countries_id = '" . $country_id . "'");

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

    $state_prov_result = tep_db_query("select zone_id, zone_name from zones where zone_country_id = '" . $country_code . "' order by zone_name");
      
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
    $country_query = tep_db_query("select distinct zone_country_id from zones order by zone_country_id");
    $NumCountry=1;
    while ($country_values = tep_db_fetch_array($country_query)) {
      if ($NumCountry == 1)
        print ("  if (" . $SelectedCountryVar . " == \"" . $country_values['zone_country_id'] . "\") {\n");
      else 
        print ("  else if (" . $SelectedCountryVar . " == \"" . $country_values['zone_country_id'] . "\") {\n");
  
      $state_query = tep_db_query("select zones.zone_name, zones.zone_id from zones where zones.zone_country_id = '" . $country_values['zone_country_id'] . "' order by zones.zone_name");
      
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
    $classes_query = tep_db_query("select tax_class_id, tax_class_title from tax_class order by tax_class_title");
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
  $format = tep_db_query("select address_format as format from address_format where address_format_id = '" . $format_id . "'");
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

    $state_prov_query = tep_db_query("select zone_code from zones where zone_country_id = '" . $country . "' and zone_id = '" . $zone . "'");

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
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from languages order by sort_order");
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

  function tep_get_languages_directory($code) {
    global $languages_id;

    $language_query = tep_db_query("select languages_id, directory from languages where code = '" . $code . "'");
    if (tep_db_num_rows($language_query)) {
      $language = tep_db_fetch_array($language_query);
      $languages_id = $language['languages_id'];
      return $language['directory'];
    } else {
      return false;
    }
  }

  function tep_get_category_name($category_id, $language_id) {
    $category_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_name'];
  }

  function tep_get_products_name($product_id, $language_id = 0) {
    global $languages_id;

    if ($language_id == 0) $language_id = $languages_id;
    $product_query = tep_db_query("select products_name from products_description where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }

  function tep_get_products_description($product_id, $language_id) {
    $product_query = tep_db_query("select products_description from products_description where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_description'];
  }

  function tep_get_products_url($product_id, $language_id) {
    $product_query = tep_db_query("select products_url from products_description where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_url'];
  }

////
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
  function tep_products_in_category_count($categories_id, $include_deactivated = false) {
    $products_count = 0;

    if ($include_deactivated) {
      $products_query = tep_db_query("select count(*) as total from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $categories_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $categories_id . "'");
    }

    $products = tep_db_fetch_array($products_query);

    $products_count += $products['total'];

    $childs_query = tep_db_query("select categories_id from categories where parent_id = '" . $categories_id . "'");
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

    $categories_query = tep_db_query("select categories_id from categories where parent_id = '" . $categories_id . "'");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $categories_count += tep_childs_in_category_count($categories['categories_id']);
    }

    return $categories_count;
  }

  function tep_redirect($destination, $parameters = '', $connection = 'NONSSL') {
    header('Location: ' . tep_href_link($destination, $parameters, $connection));
  }
?>
