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

  function tep_currency_format($number) {

    $number2currency = CURRENCY_BEFORE . number_format(($number * CURRENCY_VALUE), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;

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

  function tep_get_all_get_params($exclude = '', $exclude2 = '', $exclude3 = '', $exclude4 = '') {
    global $HTTP_GET_VARS;

    $get_url = '';
    foreach($HTTP_GET_VARS as $key => $value) {
      if (($key != $exclude) && ($key != $exclude2) && ($key != $exclude3) && ($key != $exclude4) && ($key != 'error')) $get_url .= $key . '=' . $value . '&';
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

  function tep_array_merge($array1, $array2, $array3 = array()) {

    if (function_exists('array_merge')) {
      $array_merged = array_merge($array1, $array2, $array3);
    } else {
      while (list($key,$val) = each($array1)) $array_merged[$key] = $val;
      while (list($key,$val) = each($array2)) $array_merged[$key] = $val;
      while (list($key,$val) = each($array3)) $array_merged[$key] = $val;
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
  }

  function tep_categories_pull_down($parameters, $exclude = '') {
    echo '<select ' . $parameters . '>';
    $categories_all_query = tep_db_query("select categories_id, categories_name, parent_id from categories order by categories_name");
    while ($categories_all = tep_db_fetch_array($categories_all_query)) {
      if ($categories_all['categories_id'] != $exclude) {
        $categories_parent_query = tep_db_query("select categories_name from categories where categories_id = '" . $categories_all['parent_id'] . "'");
        $categories_parent = tep_db_fetch_array($categories_parent_query);
        echo '<option value="' . $categories_all['categories_id'] . '">' . $categories_all['categories_name'];
        if (tep_db_num_rows($categories_parent_query) > 0) echo ' (' . $categories_parent['categories_name'] . ')';
        echo '</option>';
      }
    }
    echo '</select>';
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
?>
