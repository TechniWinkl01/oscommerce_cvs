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

  function tep_currency_format($number) {

    $number2currency = CURRENCY_BEFORE . number_format(($number * CURRENCY_VALUE), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;

    return $number2currency;
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

  function tep_get_countries($countries_id = '', $iso = '') {

    $list = array();
    if ($countries_id == '') {
      $countries = tep_db_query("select countries_id, countries_name from countries order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $list[] = array('countries_id' => $countries_values['countries_id'], 'countries_name' => $countries_values['countries_name']);
      }
    } else {
      if ($iso = '1') {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from countries where countries_id = '" . $countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $list = array('countries_name' => $countries_values['countries_name'], 'countries_iso_code_2' => $countries_values['countries_iso_code_2'], 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from countries where countries_id = '" . $countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $list = array('countries_name' => $countries_values['countries_name']);
      }
    }

    return $list;
  }

  function tep_get_countries_iso($countries_id) {
    return tep_get_countries($countries_id, '1');
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

  function tep_array_reverse($array) {
    if (phpversion() >= '4.0.1') {
      $array_reversed = array_reverse($array);
    } else {
      for($i=0; $i<sizeof($array); $i++) $array_reversed[$i] = $array[(sizeof($array)-$i-1)];
    }

    return $array_reversed;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_prev_next_setup
  //
  // Arguments   : cur_page_num        current page number        
  //               max_rows_per_page   maximum number of rows per page
  //               sql                 sql statement used to retrieve the data
  //               var_to_store_num    session variable used to store the total number of rows 
  //                                   that sql statement returns
  //
  // Return      : none
  //
  // Description : Function used to initialize variables for use in tep_prev_next_display
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_prev_next_setup(&$cur_page_num, $max_rows_per_page = 20, &$sql, $var_to_store_num) {
    global $$var_to_store_num;

    if (empty($cur_page_num)) {
    
      tep_session_unregister($var_to_store_num);
  
      $sql = strtolower($sql);
      $pos_from = strpos($sql, " from", 0);

      $pos_to = strlen($sql);
      $pos_group_by = strpos($sql, " group by", $pos_from);
      if ($pos_group_by < $pos_to && !($pos_group_by === false))
        $pos_to = $pos_group_by;
        
      $pos_having = strpos($sql, " having", $pos_from);
      if ($pos_having < $pos_to && !($pos_having === false))
        $pos_to = $pos_having;
        
      $pos_order_by = strpos($sql, " order by", $pos_from);
      if ($pos_order_by < $pos_to && !($pos_order_by === false))
        $pos_to = $pos_order_by;
        
      $pos_limit = strpos($sql, " limit", $pos_from);
      if ($pos_limit < $pos_to && !($pos_limit === false))
        $pos_to = $pos_limit;
        
      $pos_procedure = strpos($sql, " procedure", $pos_from);
      if ($pos_procedure < $pos_to && !($pos_procedure === false))
        $pos_to = $pos_procedure;

      $count_query = tep_db_query("select count(*) as count " . substr($sql, $pos_from, $pos_to - $pos_from));
      $count_values = tep_db_fetch_array($count_query);
      
      $$var_to_store_num = $count_values['count'];
      tep_session_register($var_to_store_num);
  
      $cur_page_num=1;
    }
    else {
      $numrows = $$var_to_store_num;
    }

    $offset = ($max_rows_per_page * ($cur_page_num - 1));

    $sql .= " limit $offset, $max_rows_per_page";
  }


  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_prev_next_display
  //
  // Arguments   : numrows             total number of rows        
  //               max_rows_per_page   maximum number of rows per page
  //               max_page_link       maximum number of page link to display; if number of
  //                                   page link exceeds the maximum, previous and/or next 
  //                                   'window' links would be displayed
  //               cur_page_num        current page number
  //               parameters          optional string of parameters to appended to the URL
  //
  // Return      : none
  //
  // Description : Function to display the Prev/Next Navigation bar
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_prev_next_display($numrows, $max_row_per_page, $max_page_link, $cur_page_num, $parameters = "") {

    $class = 'class="bluelink"';

    //-------------------------------------------------------------------------------
    // calculate number of pages needing links 
    //-------------------------------------------------------------------------------
    $num_pages=intval($numrows/$max_row_per_page);
  
    // $num_pages now contains int of pages needed unless there is a remainder from division 
    if ($numrows % $max_row_per_page) {
       // has remainder so add one page 
      $num_pages++;
    }

    //-------------------------------------------------------------------------------
    // FIRST button
    //-------------------------------------------------------------------------------
    if ($cur_page_num > 1) {  // bypass FIRST link if we are in first page
      echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=1" ' . $class . ' title="' . PREVNEXT_TITLE_FIRST_PAGE . '">' . PREVNEXT_BUTTON_FIRST . '</a>&nbsp;' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // PREV button
    //-------------------------------------------------------------------------------
    if ($cur_page_num > 1) {  // bypass PREV link if we are in first page
      echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=' . ($cur_page_num - 1) . '" ' . $class . ' title="' . PREVNEXT_TITLE_PREVIOUS_PAGE . '">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // Check if num_pages > $max_page_link
    //-------------------------------------------------------------------------------
    $cur_window_num = intval($cur_page_num / $max_page_link);
    if ($cur_page_num % $max_page_link)
      $cur_window_num++;
      
    $max_window_num = intval($num_pages / $max_page_link);
    if ($num_pages % $max_page_link)
      $max_window_num++;
  
    //-------------------------------------------------------------------------------
    // Previous window of pages
    //-------------------------------------------------------------------------------
    if ($cur_window_num > 1) {
      echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=' . (($cur_window_num - 1) * $max_page_link) . '" ' . $class . ' title="' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_link) . '">&nbsp;...&nbsp;</a>&nbsp;' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // page nn button
    //-------------------------------------------------------------------------------
    for ($jump_to_page=1 + (($cur_window_num - 1) * $max_page_link); ($jump_to_page<=($cur_window_num * $max_page_link)) && ($jump_to_page<=$num_pages); $jump_to_page++) {  // loop thru
      if ( $jump_to_page == $cur_page_num ) {
        print "<b>[$jump_to_page]</b>&nbsp;\n";
      }
      else {
        echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=' . $jump_to_page . '" ' . $class . ' title="' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . '">&nbsp;' . $jump_to_page . '&nbsp;</a>&nbsp;' . "\n";
      }
    }
  
    //-------------------------------------------------------------------------------
    // Next window of pages
    //-------------------------------------------------------------------------------
    if ($cur_window_num < $max_window_num) {
      echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=' . (($cur_window_num) * $max_page_link + 1) . '" ' . $class . ' title="' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_link) . '">&nbsp;...&nbsp;</a>&nbsp;' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // NEXT button
    //-------------------------------------------------------------------------------
    // check to see if last page 
    if ($cur_page_num<$num_pages && $num_pages!=1) {
      // not last page so give NEXT link 
      echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=' . ($cur_page_num + 1) . '" ' . $class . ' title="' . PREVNEXT_TITLE_NEXT_PAGE . '">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // LAST button
    //-------------------------------------------------------------------------------
    // check to see if last page 
    if ($cur_page_num<$num_pages && $num_pages!=1) {
      // not last page so give LAST link 
      echo '<a href="' . $PHP_SELF . '?' . $parameters . '&page=' . $num_pages . '" ' . $class . ' title="' . PREVNEXT_TITLE_LAST_PAGE . '">' . PREVNEXT_BUTTON_LAST . '</a>' . "\n";
    }

  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_prev_next_count
  //
  // Arguments   : numrows             total number of rows        
  //               max_rows_per_page   maximum number of rows per page
  //               cur_page_num        current page number
  //               text                text to display
  //
  // Return      : none
  //
  // Description : Function to display row count
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_prev_next_count($numrows, $max_row_per_page, $cur_page_num, $text) {

    $to_num = $max_row_per_page * $cur_page_num;
    if ($to_num > $numrows)
      $to_num = $numrows;
            
    echo sprintf($text, ($max_row_per_page * ($cur_page_num - 1))+1, $to_num, $numrows);
  }

?>
