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
      $link = HTTP_SERVER . DIR_ADMIN;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 1) {
        $link = HTTPS_SERVER . DIR_ADMIN;
      } else {
        $link = HTTP_SERVER . DIR_ADMIN;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }
    if ($parameters == '') {
      $link = $link . $page . '?' . SID;
    } else {
      $link = $link . $page . '?' . $parameters . '&' . SID;
    }
    
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
    $black_line = tep_image(DIR_IMAGES . 'pixel_black.gif', '100%', '1', '0', '');

    return $black_line;
  }

  function tep_currency_format($number, $calculate_currency_value = true) {

    if ($calculate_currency_value == true) {
      $number2currency = CURRENCY_BEFORE . number_format(($number * CURRENCY_VALUE), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;
    } else {
      $number2currency = CURRENCY_BEFORE . number_format(($number), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;
    }

    return $number2currency;
  }

  function tep_products_name($products_id, $manufacturers_id = '', $products_name = '') {
    if (($products_id != '') && ($products_name == '')) {
      $products_query = tep_db_query("select products_name from products where products_id = '" . $products_id . "'");
      $products = tep_db_fetch_array($products_query);
      $products_name = $products['products_name'];

      $manufacturers_query = tep_db_query("select m.manufacturers_name, m.manufacturers_location from manufacturers m, products_to_manufacturers p2m where p2m.products_id = '" . $products_id . "' and p2m.manufacturers_id = m.manufacturers_id");
    } else {
      $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_location from manufacturers where manufacturers_id = '" . $manufacturers_id . "'");
    }

    $products_manufacturers = '';
    if (tep_db_num_rows($manufacturers_query) > 1) {
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
        $products_manufacturers .= $manufacturers['manufacturers_name'] . ' / ';
        if ($manufacturers['manufacturers_location'] == '1') {
          $manufacturers_location = '1';
        } else {
          if ($manufacturers_location == '1') {
            $manufacturers_location = '1';
          } else {
            $manufacturers_location = '0';
          }
        }
      }
      $products_manufacturers = substr($products_manufacturers, 0, -3); // remove last ' / '
    } else {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $products_manufacturers = $manufacturers['manufacturers_name'];
      $manufacturers_location = $manufacturers['manufacturers_location'];
    }
    if ($manufacturers_location == '0') {
      $products_name = $products_manufacturers . ' ' . $products_name;
    } elseif ($manufacturers_location == '1') {
      $products_name = $products_name . ' (' . $products_manufacturers . ')';
    }

    return $products_name;
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
    $date_formated = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, -2),substr($raw_date, 0, 4)));

    return $date_formated;
  }

  function tep_date_short($raw_date) {
    $date_formated = strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($raw_date, 4, 2),substr($raw_date, -2),substr($raw_date, 0, 4)));

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

  function tep_set_category_info($cInfo_array) {
    global $cInfo;

    $cInfo->id = $cInfo_array['categories_id'];
    $cInfo->name = $cInfo_array['categories_name'];
    $cInfo->image = $cInfo_array['categories_image'];
    $cInfo->sort_order = $cInfo_array['sort_order'];
    $cInfo->parent_id = $cInfo_array['parent_id'];
    $cInfo->childs_count = $cInfo_array['childs_count'];
    $cInfo->products_count = $cInfo_array['products_count'];
  }

  function tep_set_product_info($pInfo_array) {
    global $pInfo;

    $pInfo->id = $pInfo_array['products_id'];
    $pInfo->name = $pInfo_array['products_name'];
    $pInfo->image = $pInfo_array['products_image'];
    $pInfo->description = stripslashes($pInfo_array['products_description']);
    $pInfo->quantity = $pInfo_array['products_quantity'];
    $pInfo->model = $pInfo_array['products_model'];
    $pInfo->url = $pInfo_array['products_url'];
    $pInfo->price = $pInfo_array['products_price'];
    $pInfo->date_added = $pInfo_array['products_date_added'];
    $pInfo->weight = $pInfo_array['products_weight'];
    $pInfo->manufacturer = $pInfo_array['manufacturers_name'];
    $pInfo->manufacturers_id = $pInfo_array['manufacturers_id'];
    $pInfo->manufacturers_image = $pInfo_array['manufacturers_image'];
    $pInfo->average_rating = $pInfo_array['average_rating'];
  }

  function tep_set_manufacturer_info($mInfo_array) {
    global $mInfo;

    $mInfo->id = $mInfo_array['manufacturers_id'];
    $mInfo->name = $mInfo_array['manufacturers_name'];
    $mInfo->image = $mInfo_array['manufacturers_image'];
    $mInfo->location = $mInfo_array['manufacturers_location'];
    $mInfo->products_count = $mInfo_array['total'];
  }

  function tep_set_review_info($rInfo_array) {
    global $rInfo;

    $rInfo->id = $rInfo_array['reviews_id'];
    $rInfo->products_id = $rInfo_array['products_id'];
    $rInfo->products_name = tep_products_name($rInfo->products_id);
    $rInfo->products_image = $rInfo_array['products_image'];
    $rInfo->customers_id = $rInfo_array['customers_id'];
    $rInfo->author = tep_customers_name($rInfo_array['customers_id']);
    $rInfo->date_added = $rInfo_array['date_added'];
    $rInfo->read = $rInfo_array['reviews_read'];
    $rInfo->text_size = $rInfo_array['reviews_text_size'];
    $rInfo->rating = $rInfo_array['reviews_rating'];
    $rInfo->average_rating = $rInfo_array['average_rating'];
    $rInfo->text = stripslashes($rInfo_array['reviews_text']);
  }

  function tep_set_special_price_info($sInfo_array) {
    global $sInfo;

    $sInfo->id = $sInfo_array['specials_id'];
    $sInfo->products_id = $sInfo_array['products_id'];
    $sInfo->products_price = $sInfo_array['products_price'];
    $sInfo->products_image = $sInfo_array['products_image'];
    $sInfo->specials_price = $sInfo_array['specials_new_products_price'];
    $sInfo->percentage = (100 - (($sInfo->specials_price / $sInfo->products_price) * 100));
    $sInfo->date_added = $sInfo_array['specials_date_added'];
  }

  function tep_set_product_expected_info($peInfo_array) {
    global $peInfo;

    $peInfo->id = $peInfo_array['products_expected_id'];
    $peInfo->products_name = $peInfo_array['products_name'];
    $peInfo->date_expected = $peInfo_array['date_expected'];
  }

  function tep_set_customer_info($cuInfo_array) {
    global $cuInfo;

    $cuInfo->id = $cuInfo_array['customers_id'];
    $cuInfo->name = $cuInfo_array['customers_firstname'] . ' ' . $cuInfo_array['customers_lastname'];
    $cuInfo->country = $cuInfo_array['countries_name'];
    $cuInfo->date_account_created = $cuInfo_array['date_account_created'];
    $cuInfo->date_account_last_modified = $cuInfo_array['date_account_last_modified'];
    $cuInfo->date_last_logon = $cuInfo_array['date_last_logon'];
    $cuInfo->number_of_logons = $cuInfo_array['number_of_logons'];
    $cuInfo->number_of_reviews = $cuInfo_array['number_of_reviews'];
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
    $categories_query = tep_db_query("select categories_name, parent_id from categories where categories_id = '" . $categories_id . "'");
    $categories = tep_db_fetch_array($categories_query);
    
    $categories_parent_query = tep_db_query("select categories_name from categories where categories_id = '" . $categories['parent_id'] . "'");
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
    $select_string = '<select ' . $parameters . '>';
    $categories_all_query = tep_db_query("select categories_id, categories_name, parent_id from categories order by categories_name");
    while ($categories_all = tep_db_fetch_array($categories_all_query)) {
      if (!tep_in_array($categories_all['categories_id'], (array)$exclude)) {
        $categories_parent_query = tep_db_query("select categories_name from categories where categories_id = '" . $categories_all['parent_id'] . "'");
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
    $select_string = '<select ' . $parameters . '>';
    $products_query = tep_db_query("select products_id from products order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $select_string .= '<option value="' . $products['products_id'] . '">' . tep_products_name($products['products_id']) . '</option>';
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

  function tep_products_name_only($products_id) {
    global $products_name_only;

    $products_only = tep_db_query("select products_name from products where products_id = '" . $products_id . "'");
    $products_values_only = tep_db_fetch_array($products_only);

    $products_name_only = $products_values_only['products_name'];

    return $products_name_only;
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
    $image_size = @getimagesize(DIR_SERVER_ROOT . DIR_CATALOG . $image_source);
    if ($image_size) {
      $image = tep_image(DIR_CATALOG . $image_source, $image_size[0], $image_size[1], 0, $image_alt);
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
?>
