<?php
/*
  $Id: general.php,v 1.148 2002/01/03 19:11:06 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

////
// Stop from parsing any further PHP code
  function tep_exit() {
   tep_session_close();
   exit();
  }

////
// Redirect to another page or site
  function tep_redirect($url) {
    header('Location: ' . $url);
    tep_exit();
  }

////
// Error message wrapper
// When optional parameters are provided, it closes the application
// (ie, halts the current application execution task)
  function tep_error_message($error_message, $close_application = false, $close_application_error = '') {
    echo $error_message;

    if ($close_application) {
      die($close_application_error);
    }
  }

////
// Return a random row from a database query
  function tep_random_select($query) {
    srand((double)microtime()*1000000); // seed the random number generator
    $random_product = '';
    $random_query = tep_db_query($query);
    $num_rows = tep_db_num_rows($random_query);
    if ($num_rows > 0) {
      $random_row = @rand(0, ($num_rows - 1));
      tep_db_data_seek($random_query, $random_row);
      $random_product = tep_db_fetch_array($random_query);
    }
    return $random_product;
  }

////
// Check product stock
// TABLES: products
  function check_stock($products_id, $products_quantity) {
    global $qtd_stock, $any_out_of_stock;

    $products_id = tep_get_prid($products_id);
    $qtd_stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
    $stock = tep_db_fetch_array($qtd_stock_query);
    $qtd_stock = $stock['products_quantity'];
    $qtd_buy = $products_quantity;
    $qtd_left = ($qtd_stock -= $qtd_buy);

    $out_of_stock = '';
    if ($qtd_left <= '-1') {
      $qtd_stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
      $stock = tep_db_fetch_array($qtd_stock_query);
      $qtd_stock = $stock['products_quantity'];

      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
      $any_out_of_stock = 'YES';
    }

    return $out_of_stock;
  }

////
// Break a word in a string if it is longer than a specified length ($len)
  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

////
// wrapper to in_array() for PHP3 compatibility
// Checks if the lookup value exists in the lookup array
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

////
// Return all HTTP GET variables, except those passed as a parameter
  function tep_get_all_get_params($exclude_array = '') {
    global $HTTP_GET_VARS;

    if ($exclude_array == '') $exclude_array = array();

    $get_url = '';
    if (is_array($HTTP_GET_VARS)) {
      reset($HTTP_GET_VARS);
      while (list($key, $value) = each($HTTP_GET_VARS)) {
        if (($key != session_name()) && ($key != 'error') && (!tep_in_array($key, $exclude_array))) $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
      }
    }
    return $get_url;
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
// Alias function to tep_get_countries, which also returns the countries iso codes
  function tep_get_countries_with_iso_codes($countries_id) {
    return tep_get_countries($countries_id, true);
  }

////
// Generate a path to categories
  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if ($current_category_id) {
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
    } else {
      $cPath_new = implode('_', $cPath_array);
    }

    return 'cPath=' . $cPath_new;
  }

////
// wrapper to array_reverse for PHP3 compatibility
  function tep_array_reverse($array) {
    if (function_exists('array_reverse')) {
      $array_reversed = array_reverse($array);
    } else {
      for ($i=0; $i<sizeof($array); $i++) $array_reversed[$i] = $array[(sizeof($array)-$i-1)];
    }

    return $array_reversed;
  }

////
// Returns the clients browser
  function tep_browser_detect($component) {
    global $HTTP_USER_AGENT;

    $result = stristr($HTTP_USER_AGENT, $component);

    return $result;
  }

////
// Alias function to tep_get_countries()
  function tep_get_country_name($country_id) {
    $country_array = tep_get_countries($country_id);

    return $country_array['countries_name'];
  }

////
// Returns the zone (State/Province) name
// TABLES: zones
  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

////
// Returns the zone (State/Province) code
// TABLES: zones
  function tep_get_zone_code($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_code'];
    } else {
      return $default_zone;
    }
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function tep_get_tax_rate($country_id, $zone_id, $class_id) {
    $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_ZONES_TO_GEO_ZONES . " za LEFT JOIN " . TABLE_TAX_RATES . " tr ON tr.tax_zone_id=za.geo_zone_id, ". TABLE_GEO_ZONES . " tz WHERE za.zone_country_id='" . $country_id . "' AND (za.zone_id IS NULL OR za.zone_id='0' OR za.zone_id='" . $zone_id . "') AND tr.tax_class_id = '" . $class_id . "'  AND (tz.geo_zone_id=tr.tax_zone_id) GROUP BY tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier=1.000;
      while($tax = tep_db_fetch_array($tax_query))
      {
        $tax_multiplier *= 1.000+($tax['tax_rate']/100);
      }
      return ($tax_multiplier-1.000)*100;
    } else {
      return TAX_VALUE;
    }
  }

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
  function tep_get_tax_description($zone_id, $class_id) {
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " where tax_zone_id = '" . $zone_id . "' and tax_class_id = '" . $class_id . "'");
    if (tep_db_num_rows($tax_query)) {
      $tax = tep_db_fetch_array($tax_query);
      return $tax['tax_description'];
    } else {
      return TEXT_UNKNOWN_TAX_RATE;
    }
  }

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
  function tep_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
    if ($include_inactive) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $category_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $category_id . "'");
    }
    $products = tep_db_fetch_array($products_query);
    $products_count += $products['total'];

    if (USE_RECURSIVE_COUNT) {
      $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $category_id . "'");
      if (tep_db_num_rows($child_categories_query)) {
        while ($child_categories = tep_db_fetch_array($child_categories_query)) {
          $products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
        }
      }
    }

    return $products_count;
  }

////
// Return true if the category has subcategories
// TABLES: categories
  function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . $category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Returns the address_format_id for the given country
// TABLES: countries;
  function tep_get_address_format_id($country_id) {
    $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");
    if (tep_db_num_rows($address_format_query)) {
      $address_format = tep_db_fetch_array($address_format_query);
      return $address_format['format_id'];
    } else {
      return '1';
    }
  }

////
// Return a formatted address
// TABLES: address_format
  function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . $address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    $firstname = addslashes($address['firstname']);
    $lastname = addslashes($address['lastname']);
    $street = addslashes($address['street_address']);
    $suburb = addslashes($address['suburb']);
    $city = addslashes($address['city']);
    $state = addslashes($address['state']);
    $country_id = $address['country_id'];
    $zone_id = $address['zone_id'];
    $postcode = addslashes($address['postcode']);
    $zip = $postcode;
    $country = tep_get_country_name($country_id);
    $state = tep_get_zone_code($country_id, $zone_id, $state);

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($firstname == '') $firstname = addslashes($address['name']);
    if ($country == '') $country = addslashes($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");
    $address = stripslashes($address);

    return $boln . $address . $eoln;
  }

////
// Return a formatted address
// TABLES: customers, address_book
  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customers_id . "' and address_book_id = '" . $address_id . "'");
    $address = tep_db_fetch_array($address_query);
    $format_id = tep_get_address_format_id($address['country_id']);
    return tep_address_format($format_id, $address, $html, $boln, $eoln);
  }

////
// Return a formatted address
// TABLES: customers, address_book, address_format_it
  function tep_address_summary($customers_id, $address_id) {
    $address_query = tep_db_query("select entry_suburb as suburb, entry_city as city, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $address_id . "' and customers_id = '" . $customers_id . "'");
    $address = tep_db_fetch_array($address_query);
    $country_id = $address['country_id'];
    $suburb = addslashes($address['suburb']);
    $city = addslashes($address['city']);
    $state = addslashes($address['state']);
    $zone_id = $address['zone_id'];
    $country = tep_get_country_name($country_id);
    $state = tep_get_zone_code($country_id, $zone_id, $state);
    $format_id = tep_get_address_format_id($country_id);
    $address_format_query = tep_db_query("select address_summary as summary from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . $format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);
    $fmt = $address_format['summary'];
    eval("\$address = \"$fmt\";");
    $address = stripslashes($address);
    return $address;
  }

  function tep_row_number_format($number) {
    if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

    return $number;
  }

////
// Return a select pull down list of categories and subcategories
// select_name =  value of the select's "name" attribute
// selected = array of categories_ids of category to be selected
//            (0=selects blank; when more than one ID is selected
//            the "multiple" attribute will be included)
// size = value of the select's "size" attribute
// multiple = include/exclude select's "mutliple" attribute
//            (0=exclude; 1=include)
// blank_text = string for displaying in the first option
//
// Example:  To display a drop-down with categories_id '5' selected:
// $selected[0] = 5;
// display_cat_select("category",$selected);
//
// To display a list box with 10 rows with categories_id '5' and '11' selected:
// $selected[0] = 5;
// $selected[1] = 11;
// display_cat_select("category",$selected, 10);
  function tep_display_cat_select($select_name, $selected, $size = 1, $multiple = 0, $blank_text = '') {
    $select_string = '<select name="' . $select_name . '" size="' . $size . '"';
    if ( (sizeof($selected) > 1) || ($multiple == 1) ) $select_string .= ' MULTIPLE';
    $select_string .= '><option value=""';
    if (tep_in_array(0, $selected)) $select_string .= ' SELECTED';
    $select_string .= '>' . $blank_text . '</option>';

    $output = '';
    tep_build_cat_options($output, $selected);
    $select_string .= $output . '</select>';

    return $select_string;
  }

////
// Recursively fo through the category tree and output each category in a select pull down option
  function tep_build_cat_options(&$output, $preselected, $parent_id = 0, $indent = '') {
    global $languages_id;

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . $parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $output .= '<option value="' . $categories['categories_id'] . '"';
      if (tep_in_array($categories['categories_id'], $preselected)) $output .= ' SELECTED';
      $output .= '>' . $indent . $categories['categories_name'] . '</option>';

      if ($categories['categories_id'] != $parent_id) {
        tep_build_cat_options($output, $preselected, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
      }
    }
  }

////
// Return all subcategory IDs
// TABLES: categories
  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $parent_id . "'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
      $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }

////
// Returns a date to YYYYMMDD format (will be removed later)
  function tep_reformat_date_to_yyyymmdd($date_to_reformat, $format_string) {
    $separator_idx = -1;
    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $format_string = strtolower($format_string);

    for ($i=0; $i<sizeof($separators); $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator) {
        $separator_idx = $i;
        break;
      }
    }

    if ($separator_idx != -1) {
      $format_string_array = explode($separators[$separator_idx], $format_string);
      $date_to_reformat_array = explode($separators[$separator_idx], $date_to_reformat);

      for ($i=0; $i<sizeof($format_string_array); $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_reformat_array[$i];
        if ($format_string_array[$i] == 'dd') $day = $date_to_reformat_array[$i];
        if ($format_string_array[$i] == 'yyyy') $year = $date_to_reformat_array[$i];
      }
    } else {
      $pos_month = strpos($format_string, 'mmm');
      if ($pos_month != false) {
        $month = substr( $date_to_reformat, $pos_month, 3 );
        for ($i=0; $i<sizeof($month_abbr); $i++) {
          if ($month == $month_abbr[$i]) {
            $month = $i;
            break;
          }
        }
      } else {
        $month = substr($date_to_reformat, strpos($format_string, 'mm'), 2);
      }

      $day = substr($date_to_reformat, strpos($format_string, 'dd'), 2);
      $year = substr($date_to_reformat, strpos($format_string, 'yyyy'), 2);
    }

    return sprintf('%04d%02d%02d', $year, $month, $day);
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function tep_date_long($raw_date) {
// remove the first digit if it is 0 - as php treats these as Octals
    $year = substr($raw_date, 0, 4);
    $month = substr($raw_date, 5, 2); if (substr($month, 0, 1) == '0') $month = substr($month, 1);
    $day = substr($raw_date, 8, 2); if (substr($day, 0, 1) == '0') $day =  substr($day, 1);
    $hour = substr($raw_date, 11, 2); if (substr($hour, 0, 1) == '0') $hour = substr($hour, 1);
    $minute = substr($raw_date, 14, 2); if (substr($minute, 0, 1) == '0') $minute = substr($minute, 1);
    $second = substr($raw_date, 17, 2); if (substr($second, 0, 1) == '0') $second = substr($second, 1);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

// remove the first digit if it is 0 - as php treats these as Octals
    $year = substr($raw_date, 0, 4);
    $month = substr($raw_date, 5, 2); if (substr($month, 0, 1) == '0') $month = substr($month, 1);
    $day = substr($raw_date, 8, 2); if (substr($day, 0, 1) == '0') $day =  substr($day, 1);
    $hour = substr($raw_date, 11, 2); if (substr($hour, 0, 1) == '0') $hour = substr($hour, 1);
    $minute = substr($raw_date, 14, 2); if (substr($minute, 0, 1) == '0') $minute = substr($minute, 1);
    $second = substr($raw_date, 17, 2); if (substr($second, 0, 1) == '0') $second = substr($second, 1);

    return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  }

////
// Parse search string into indivual objects
  function tep_parse_search_string($search_str = '', &$objects) {
    $search_str = strtolower($search_str);

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = split('[[:space:]]+', $search_str);

    $objects = array();
    $tmpstring = '';
    $flag = '';

    for ($k=0; $k<count($pieces); $k++) {
      while (substr($pieces[$k], 0, 1) == '(') {
        $objects[] = '(';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 1);
        } else {
          $pieces[$k] = '';
        }
      }

      $post_objects = array();

      while (substr($pieces[$k], -1) == ')')  {
        $post_objects[] = ')';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 0, -1);
        } else {
          $pieces[$k] = '';
        }
      }

// Check individual words

      if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
        $objects[] = trim($pieces[$k]);

        for ($j=0; $j<count($post_objects); $j++) {
          $objects[] = $post_objects[$j];
        }
      } else {
/* This means that the $piece is either the beginning or the end of a string.
   So, we'll slurp up the $pieces and stick them together until we get to the
   end of the string or run out of pieces.
*/

// Make sure the $tmpstring is empty
        $tmpstring = '';

// Add this word to the $tmpstring, starting the $tmpstring

        $tmpstring .= trim(ereg_replace('"', ' ', $pieces[$k]));

// Check for one possible exception to the rule. That there is a single quoted word.
        if (substr($pieces[$k], -1 ) == '"') {
// Turn the flag off for future iterations
          $flag = 'off';

          $objects[] = trim($pieces[$k]);

          for ($j=0; $j<count($post_objects); $j++) {
            $objects[] = $post_objects[$j];
          }

          unset($tmpstring);

// Stop looking for the end of the string and move onto the next word.
          continue;
        }

// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
        $flag = 'on';

// Move on to the next word
        $k++;

// Keep reading until the end of the string as long as the $flag is on

        while ( ($flag == 'on') && ($k < count($pieces)) ) {
          while (substr($pieces[$k], -1) == ')') {
            $post_objects[] = ')';
            if (strlen($pieces[$k]) > 1) {
              $pieces[$k] = substr($pieces[$k], 0, -1);
            } else {
              $pieces[$k] = '';
            }
          }

// If the word doesn't end in double quotes, append it to the $tmpstring.
          if (substr($pieces[$k], -1) != '"') {
// Tack this word onto the current string entity
            $tmpstring .= ' ' . $pieces[$k];

// Move on to the next word
            $k++;
            continue;
          } else {
/* If the $piece ends in double quotes, strip the double quotes, tack the
   $piece onto the tail of the string, push the $tmpstring onto the $haves,
   kill the $tmpstring, turn the $flag "off", and return.
*/
            $tmpstring .= ' ' . trim(ereg_replace('"', ' ', $pieces[$k]));

// Push the $tmpstring onto the array of stuff to search for
            $objects[] = trim($tmpstring);

            for ($j=0; $j<count($post_objects); $j++) {
              $objects[] = $post_objects[$j];
            }

            unset($tmpstring);

// Turn off the flag to exit the loop
            $flag = 'off';
          }
        }
      }
    }

// add default logical operators if needed
    $temp = array();
    for($i=0; $i<(count($objects)-1); $i++) {
      $temp[sizeof($temp)] = $objects[$i];

      if ( ($objects[$i] != 'and') &&
           ($objects[$i] != 'or') &&
           ($objects[$i] != '(') &&
           ($objects[$i] != ')') &&
           ($objects[$i+1] != 'and') &&
           ($objects[$i+1] != 'or') &&
           ($objects[$i+1] != '(') &&
           ($objects[$i+1] != ')') ) {
        $temp[sizeof($temp)] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
      }
    }
    $temp[sizeof($temp)] = $objects[$i];
    $objects = $temp;

    $keyword_count = 0;
    $operator_count = 0;
    for($i=0; $i<count($objects); $i++) {
      if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
        $operator_count ++;
      } elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
        $keyword_count ++;
      }
    }

    if ($operator_count < $keyword_count) {
      return true;
    } else {
      return false;
    }
  }

////
// Check date
  function tep_checkdate($date_to_check, $format_string, &$date_array) {
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);

    if (strlen($date_to_check) != strlen($format_string)) {
      return false;
    }

    for ($i=0; $i<sizeof($separators); $i++) {
      $pos_separator = strpos($date_to_check, $separators[$i]);
      if ($pos_separator != false) {
        $date_separator_idx = $i;
        break;
      }
    }

    for ($i=0; $i<sizeof($separators); $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator != false) {
        $format_separator_idx = $i;
        break;
      }
    }

    if ($date_separator_idx != $format_separator_idx) {
      return false;
    }

    if ($date_separator_idx != -1) {
      $format_string_array = explode( $separators[$date_separator_idx], $format_string );
      if (sizeof($format_string_array) != 3) {
        return false;
      }

      $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
      if (sizeof($date_to_check_array) != 3) {
        return false;
      }

      for ($i=0; $i<sizeof($format_string_array); $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'yyyy') $year = $date_to_check_array[$i];
      }
    } else {
      if (strlen($format_string) == 8 || strlen($format_string) == 9) {
        $pos_month = strpos($format_string, 'mmm');
        if ($pos_month != false) {
          $month = substr( $date_to_check, $pos_month, 3 );
          for ($i=0; $i<sizeof($month_abbr); $i++) {
            if ($month == $month_abbr[$i]) {
              $month = $i;
              break;
            }
          }
        } else {
          $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
        }
      } else {
        return false;
      }

      $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
      $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if (strlen($year) != 4) {
      return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
      return false;
    }

    if ($month > 12 || $month < 1) {
      return false;
    }

    if ($day < 1) {
      return false;
    }

    if (tep_is_leap_year($year)) {
      $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
      return false;
    }

    $date_array = array($year, $month, $day);

    return true;
  }

////
// Check if year is a leap year
  function tep_is_leap_year($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }

    return false;
  }

////
// Return table heading with sorting capabilities
  function tep_create_sort_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'x', 'y', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a'), 'NONSSL') . '" title="' . TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading . '">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

////
// Construct a category path to the product
// TABLES: products_to_categories
  function tep_get_product_path($products_id) {
    $cPath = '';

    $cat_count_sql = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'");
    $cat_count_data = tep_db_fetch_array($cat_count_sql);

    if ($cat_count_data['count'] == 1) {
      $categories = array();

      $cat_id_sql = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'");
      $cat_id_data = tep_db_fetch_array($cat_id_sql);
      tep_get_parent_categories($categories, $cat_id_data['categories_id']);

      for ($i=sizeof($categories)-1; $i>=0; $i--) {
        if ($cPath != '') $cPath .= '_';
        $cPath .= $categories[$i];
      }
      if ($cPath != '') $cPath .= '_';
      $cPath .= $cat_id_data['categories_id'];
    }

    return $cPath;
  }

////
// Return a product ID with attributes
  function tep_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        $uprid = $uprid . '{' . $option . '}' . $value;
      }
    }

    return $uprid;
  }

////
// Return a product ID from a product ID with attributes
  function tep_get_prid($uprid) {
    $pieces = split('[{]', $uprid, 2);

    return $pieces[0];
  }

////
// Return a customer greeting
  function tep_customer_greeting() {
    global $HTTP_COOKIE_VARS, $customer_id, $customer_first_name;

    if ($HTTP_COOKIE_VARS['first_name']) {
      $first_name = stripslashes($HTTP_COOKIE_VARS['first_name']);
    } elseif ($customer_first_name) {
      $first_name = $customer_first_name;
    }

    if ($first_name) {
      $greeting_string = sprintf(TEXT_GREETING_PERSONAL, $first_name, tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL'));
      if (!$customer_id) {
        $greeting_string .= '<br>' . sprintf(TEXT_GREETING_PERSONAL_RELOGON, $first_name, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
      }
    } else {
      $greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    return $greeting_string;
  }

////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient, 
//                    e.g. jan.wildeboer@gmx.de 
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender, 
//                    e.g. info@mytepshop.com

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $email_background) {
    if (SEND_EMAILS != 'true') return false;

    // Build all required headers
    // add From: header
    $headers = "From: $from_email_name <$from_email_address>\r\n";
//    $headers .= "To: $to_name <$to_email_address>\r\n";

    // Global check:
    // should we use HTML or not?
    if (EMAIL_USE_HTML == 'true') {

      // specify MIME version 1.0
      $headers .= "MIME-Version: 1.0\r\n";

      // generate the unique boundary
      $boundary = uniqid("TheExchangeProject");

      // tell e-mail client this e-mail contains alternate versions
      $headers .= "Content-Type: multipart/related;\r\n        type=\"multipart/alternative\";\r\n        boundary = $boundary\r\n\r\n";

      // message to people with clients who don't understand MIME
      $body .= "This is a multi-part message in MIME-format.\r\n\r\n";
      $innerboundary = uniqid("innerpart");
      $body .= "--$boundary\r\nContent-Type: multipart/alternative;\r\n        boundary=\"$innerboundary\"\r\n\r\n";
      // plain text version of message
      // strip all tags from the text
      $body .= "--$innerboundary\r\nContent-Type: text/plain; charset=" . CHARSET . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
      $body .= strip_tags($email_text);
      $body .= "\r\n\r\n";

      //HTML version of message
      $body .= "--$innerboundary\r\nContent-Type: text/html; charset=" . CHARSET . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
      $body .= chunk_split(base64_encode($email_text));

      //Close the MultiPart
      $body .= "\r\n--$innerboundary--\r\n";
      $body .= "\r\n--$boundary--\r\n";

    } else {
      // send the text-only version
      $body = strip_tags($email_text);
    }
    //send message
    mail($to_email_address, $email_subject, $body, $headers);

  }

////
// Check if product has attributes
  function tep_has_product_attributes($products_id) {
    $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "'");
    $attributes = tep_db_fetch_array($attributes_query);

    if ($attributes['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Get the number of times a word/character is present in a string
  function tep_word_count($string, $needle) {
    $temp_array = split($needle, $string);
    return sizeof($temp_array);
  }

////
// Get the name of the orders status
  function tep_get_orders_status_name($orders_status_id, $language_id) {
    $orders_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $orders_status_id . "' and language_id = '" . $language_id . "'");
    $orders_status = tep_db_fetch_array($orders_status_query);

    return $orders_status['orders_status_name'];
  }

  function tep_count_modules($modules = '') {
    if (!$modules) return '0';

    $modules_array = split(';', $modules);

    return sizeof($modules_array);
  }

  function tep_count_payment_modules() {
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
  }

  function tep_count_shipping_modules() {
    return tep_count_modules(MODULE_SHIPPING_INSTALLED);
  }

  function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    mt_srand((double) microtime() * 1000000);
    while (strlen($rand_value)<$length) {
      if ($type == 'digits') {
        $char = mt_rand(0,9);
      } else {
        $char = chr(mt_rand(0,255));
      }
      if ($type == 'mixed') {
        if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (eregi('^[a-z]$', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (ereg('^[0-9]$', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }
?>