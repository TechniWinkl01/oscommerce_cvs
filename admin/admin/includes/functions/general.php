<?
  function tep_exit() {
    if (EXIT_AFTER_REDIRECT == 1) {
     return exit();
    }
  }

  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    global $link;

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
    global $image;
    
    $image = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '"';
    if ($alt != '') {
      $image .= ' alt=" ' . $alt . ' "';
    }
    $image .= '>';

    return $image;
  }

  function tep_image_submit($src, $width, $height, $border, $alt) {
    global $image_submit;
    
    $image_submit = '<input type="image" src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '" alt=" ' . $alt . ' ">';
    
    return $image_submit;
  }

  function tep_black_line() {
    global $black_line;
    
    $black_line = tep_image(DIR_IMAGES . 'pixel_black.gif', '100%', '1', '0', '');
    
    return $black_line;
  }

  function tep_products_subcategories($products_id) {
    global $products_subcategories;

    $products_subcategories = '';
    $subcategories = tep_db_query("select subcategories.subcategories_name from subcategories, products_to_subcategories where products_to_subcategories.products_id = '" . $products_id . "' and products_to_subcategories.subcategories_id = subcategories.subcategories_id order by subcategories.subcategories_name");
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      $products_subcategories .= $subcategories_values['subcategories_name'] . ' / ';
    }
    $products_subcategories = substr($products_subcategories, 0, -3); // remove the last ' / '

    return $products_subcategories;
  }

  function tep_manufacturers_categories($manufacturers_id) {
    global $manufacturers_categories;

    $manufacturers_categories = '';
    $categories = tep_db_query("select category_top.category_top_name from manufacturers_to_category, category_top where manufacturers_to_category.manufacturers_id = '" . $manufacturers_id . "' and manufacturers_to_category.category_top_id = category_top.category_top_id");
    while ($categories_values = tep_db_fetch_array($categories)) {
      $manufacturers_categories .= $categories_values['category_top_name'] . ' / ';
    }
    $manufacturers_categories = substr($manufacturers_categories, 0, -3); // remove trailing ' / '

    return $manufacturers_categories;
  }

  function tep_subcategories_categories($subcategories_id) {
    global $subcategories_categories;

    $subcategories_categories = '';
    $categories = tep_db_query("select category_top.category_top_name from subcategories_to_category, category_top where subcategories_to_category.subcategories_id = '" . $subcategories_id . "' and subcategories_to_category.category_top_id = category_top.category_top_id");
    while ($categories_values = tep_db_fetch_array($categories)) {
      $subcategories_categories .= $categories_values['category_top_name'] . ' / ';
    }
    $subcategories_categories = substr($subcategories_categories, 0, -3); // remove trailing ' / '

    return $subcategories_categories;
  }

  function tep_products_name($products_id) {
    global $products_name;

    $products = tep_db_query("select products_name from products where products_id = '" . $products_id . "'");
    $products_values = tep_db_fetch_array($products);

    $manufacturers = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location from manufacturers, products_to_manufacturers where products_to_manufacturers.products_id = '" . $products_id . "' and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
    $products_manufacturers = '';
    if (tep_db_num_rows($manufacturers) > 1) {
      while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
        $products_manufacturers .= $manufacturers_values['manufacturers_name'] . ' / ';
        if ($manufacturers_values['manufacturers_location'] == '1') {
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
      $manufacturers_values = tep_db_fetch_array($manufacturers);
      $products_manufacturers = $manufacturers_values['manufacturers_name'];
      $manufacturers_location = $manufacturers_values['manufacturers_location'];
    }
    if ($manufacturers_location == '0') {
      $products_name = $products_manufacturers . ' ' . $products_values['products_name'];
    } else {
      $products_name = $products_values['products_name'] . ' (' . $products_manufacturers . ')';
    }

    return $products_name;
  }

  function tep_customers_name($customers_id) {
    global $customers_name;
    
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

  function tep_get_all_get_params($exclude = '', $exclude2 = '') {
    global $HTTP_GET_VARS;

    $get_url = '';
    foreach($HTTP_GET_VARS as $key => $value) {
      if (($key != $exclude) && ($key != $exclude2) && ($key != 'error')) $get_url .= $key . '=' . $value . '&';
    }

    return $get_url;
  }
?>
