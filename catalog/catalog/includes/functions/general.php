<?
  function tep_exit() {
    if (EXIT_AFTER_REDIRECT == 1) {
     return exit();
    }
  }

  function tep_random_select($db_query) {
    global $select_products, $random_row, $random_product;

    $select_products = tep_db_query($db_query);
    srand((double)microtime()*1000000); // seed the random number generator
    $random_row = @rand(0, (tep_db_num_rows($select_products) - 1));
    tep_db_data_seek($select_products, $random_row);
    $random_product = tep_db_fetch_array($select_products);

    return $random_product;
  }

  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    global $link;

    if ($page == '') {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }
    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 1) {
        $link = HTTPS_SERVER . DIR_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_CATALOG;
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

  function tep_number_format($number) {
    global $f_number;

    $f_number = CURRENCY_BEFORE . number_format(($number * CURRENCY_VALUE), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;

    return $f_number;
  }

  function tep_products_name($manufacturers_location, $manufacturers_name, $products_name) {
    global $f_products_name;

    if ($manufacturers_location == '0') {
      $f_products_name = $manufacturers_name . ' ' . $products_name;
    } else {
      $f_products_name = $products_name . ' (' . $manufacturers_name . ')';
    }

    return $f_products_name;
  }

  function tep_products_in_cart() {
    global $customer_id;

    $products_in_cart = 0;
    if (tep_session_is_registered('customer_id')) {
      $check_cart = tep_db_query('select customers_basket_id from customers_basket where customers_id = ' . $customer_id);
      if (@tep_db_num_rows($check_cart)) {
        $products_in_cart = 1;
      }
    } elseif (tep_session_is_registered('nonsess_cart')) {
      $products_in_cart = 1;
    }

    return $products_in_cart;
  }

  function tep_break_string($string, $len) {
    global $l, $output, $i, $char;

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

  function tep_image($src, $width, $height, $border, $alt) {
    global $image;
    
    $image = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '" alt=" ' . $alt . ' ">';
    
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
?>
