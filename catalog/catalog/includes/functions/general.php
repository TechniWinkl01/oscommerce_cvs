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

  function tep_currency_format($number, $calculate_currency_value = true) {

    if ($calculate_currency_value == true) {
      $number2currency = CURRENCY_BEFORE . number_format(($number * CURRENCY_VALUE), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;
    } else {
      $number2currency = CURRENCY_BEFORE . number_format(($number), 2, CURRENCY_DECIMAL, CURRENCY_THOUSANDS) . CURRENCY_AFTER;
    }

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

    if (!IMAGE_REQUIRED) {
        if($src == "" || $src == "images/transparent.gif" || $src == "none") {
           $image = "<!-- NO IMAGE DEFINED -->";
        }
    }
 
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
    if (function_exists('array_reverse')) {
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
  //
  // Return      : none
  //
  // Description : Function used to initialize variables for use in tep_prev_next_display
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_prev_next_setup(&$cur_page_num, $max_rows_per_page = 20, &$sql) {
    //global $$var_to_store_num;

    if (empty($cur_page_num)) {
      $cur_page_num=1;
    }
    
    $sql = strtolower($sql);
    $pos_from = strpos($sql, " from", 0);

    $pos_to = strlen($sql);
    $pos_group_by = strpos($sql, " group by", $pos_from);
    if ($pos_group_by < $pos_to && !($pos_group_by == false))
      $pos_to = $pos_group_by;
      
    $pos_having = strpos($sql, " having", $pos_from);
    if ($pos_having < $pos_to && !($pos_having == false))
      $pos_to = $pos_having;
      
    $pos_order_by = strpos($sql, " order by", $pos_from);
    if ($pos_order_by < $pos_to && !($pos_order_by == false))
      $pos_to = $pos_order_by;
      
    $pos_limit = strpos($sql, " limit", $pos_from);
    if ($pos_limit < $pos_to && !($pos_limit == false))
      $pos_to = $pos_limit;
      
    $pos_procedure = strpos($sql, " procedure", $pos_from);
    if ($pos_procedure < $pos_to && !($pos_procedure == false))
      $pos_to = $pos_procedure;

    $count_query = tep_db_query("select count(*) as count " . substr($sql, $pos_from, $pos_to - $pos_from));
    $count_values = tep_db_fetch_array($count_query);
    
    $offset = ($max_rows_per_page * ($cur_page_num - 1));
    $sql .= " limit $offset, $max_rows_per_page";
    
    return $count_values['count'];
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
  //               page                filename of the page to be displayed
  //               parameters          optional string of parameters to appended to the URL
  //
  // Return      : none
  //
  // Description : Function to display the Prev/Next Navigation bar
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_prev_next_display($numrows, $max_row_per_page, $max_page_link, $cur_page_num, $page, $parameters = "") {

    $class = 'class="bluelink"';
    if ($parameters)
      $parameters .= '&';

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
      echo '<a href="' . $page . '?' . $parameters . 'page=1" ' . $class . ' title="' . PREVNEXT_TITLE_FIRST_PAGE . '">' . PREVNEXT_BUTTON_FIRST . '</a>' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // PREV button
    //-------------------------------------------------------------------------------
    if ($cur_page_num > 1) {  // bypass PREV link if we are in first page
      echo '<a href="' . $page . '?' . $parameters . 'page=' . ($cur_page_num - 1) . '" ' . $class . ' title="' . PREVNEXT_TITLE_PREVIOUS_PAGE . '">' . PREVNEXT_BUTTON_PREV . '</a>' . "\n";
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
      echo '<a href="' . $page . '?' . $parameters . 'page=' . (($cur_window_num - 1) * $max_page_link) . '" ' . $class . ' title="' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_link) . '">&nbsp;...&nbsp;</a>' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // page nn button
    //-------------------------------------------------------------------------------
    for ($jump_to_page=1 + (($cur_window_num - 1) * $max_page_link); ($jump_to_page<=($cur_window_num * $max_page_link)) && ($jump_to_page<=$num_pages); $jump_to_page++) {  // loop thru
      if ( $jump_to_page == $cur_page_num ) {
        print "<b>[$jump_to_page]</b>\n";
      }
      else {
        echo '<a href="' . $page . '?' . $parameters . 'page=' . $jump_to_page . '" ' . $class . ' title="' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . '">&nbsp;' . $jump_to_page . '&nbsp;</a>' . "\n";
      }
    }
  
    //-------------------------------------------------------------------------------
    // Next window of pages
    //-------------------------------------------------------------------------------
    if ($cur_window_num < $max_window_num) {
      echo '<a href="' . $page . '?' . $parameters . 'page=' . (($cur_window_num) * $max_page_link + 1) . '" ' . $class . ' title="' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_link) . '">&nbsp;...&nbsp;</a>&nbsp;' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // NEXT button
    //-------------------------------------------------------------------------------
    // check to see if last page 
    if ($cur_page_num<$num_pages && $num_pages!=1) {
      // not last page so give NEXT link 
      echo '<a href="' . $page . '?' . $parameters . 'page=' . ($cur_page_num + 1) . '" ' . $class . ' title="' . PREVNEXT_TITLE_NEXT_PAGE . '">' . PREVNEXT_BUTTON_NEXT . '</a>' . "\n";
    }
  
    //-------------------------------------------------------------------------------
    // LAST button
    //-------------------------------------------------------------------------------
    // check to see if last page 
    if ($cur_page_num<$num_pages && $num_pages!=1) {
      // not last page so give LAST link 
      echo '<a href="' . $page . '?' . $parameters . 'page=' . $num_pages . '" ' . $class . ' title="' . PREVNEXT_TITLE_LAST_PAGE . '">' . PREVNEXT_BUTTON_LAST . '</a>' . "\n";
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

  function tep_browser_detect($component) { 
    global $HTTP_USER_AGENT; 
    $result = stristr($HTTP_USER_AGENT,$component); 
    return $result; 
  } 

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //   tep_get_country_list
  //
  //   - make a popup list of countries
  //   - ISO standard abbreviations are used (ISO 3166)
  //   - Country Codes and Names are stored in table 'countries'
  //
  //   Written By: Kenneth Cheng
  //
  //   parameters
  //   ----------
  //
  //   popup_name: the name attribute you want for the <SELECT> tag
  //
  //   selected:   the default selected value [optional]
  //
  //   javascript: javascript for the <SELECT> tag, i.e.
  //               onChange="this.form.submit()" [optional]
  //
  //   size:       size [optional]
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_country_list ($popup_name, $selected="", $javascript="", $size=1) {

    // start building the popup menu
    $result = "<select name=\"$popup_name\"";
    
    if ($size != 1)
      $result .= " size=\"$size\"";
      
    if ($javascript)
      $result .= " " . $javascript;
    
    $result .= ">\n";
    
    $result .= "<option value=\"\">" . PLEASE_SELECT . "\n";

      // need to convert this to use tep_get_countries()
      $country_result = tep_db_query("select countries_name, countries_id from countries order by countries_name");
      
      while ($country_values = tep_db_fetch_array($country_result)) {

      // printed SELECTED if an item was previously selected
      // so we maintain the state
      if ($selected == $country_values[countries_id]) {
        $result .= "<option value=\"$country_values[countries_id]\" SELECTED>$country_values[countries_name]\n";
      } else {
        $result .= "<option value=\"$country_values[countries_id]\">$country_values[countries_name]\n";
      }
     }
    // finish the popup menu
    $result .= "</select>\n";
    
    echo $result;
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

    $state_prov_result = tep_db_query("select zone_id, zone_name from tax_zones where zone_country_id = '" . $country_code . "' order by zone_name");
      
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
    
    echo $result;

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
  function tep_js_zone_list($SelectedCountryVar, $FormName) {
    $country_query = tep_db_query("select distinct zone_country_id from tax_zones order by zone_country_id");
    $NumCountry=1;
    while ($country_values = tep_db_fetch_array($country_query)) {
      if ($NumCountry == 1)
        print ("  if (" . $SelectedCountryVar . " == \"" . $country_values['zone_country_id'] . "\") {\n");
      else 
        print ("  else if (" . $SelectedCountryVar . " == \"" . $country_values['zone_country_id'] . "\") {\n");
  
      $state_query = tep_db_query("select tax_zones.zone_name, tax_zones.zone_id from tax_zones where tax_zones.zone_country_id = '" . $country_values['zone_country_id'] . "' order by tax_zones.zone_name");
      
      $NumState = 1;
      while ($state_values = tep_db_fetch_array($state_query)) {
        if ($NumState == 1)
          print ("    " . $FormName . ".zone_id.options[0] = new Option(\"" . PLEASE_SELECT . "\", \"\");\n");
        print ("    " . $FormName . ".zone_id.options[$NumState] = new Option(\"" . $state_values['zone_name'] . "\", \"" . $state_values['zone_id'] . "\");\n");
        $NumState++;
      }
      $NumCountry++;
      print ("  }\n");
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_country_name
  //
  // Arguments   : country        country code string 
  //
  // Return      : none
  //
  // Description : Function to retrieve the country name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_country_name($country) {

    $country_query = tep_db_query("select countries_name from countries where countries_id = '" . $country . "'");

    if (!tep_db_num_rows($country_query)) {
      $country_name = $country;
    }
    else {
      $country_values = tep_db_fetch_array($country_query);
      $country_name = $country_values['countries_name'];
    }
    
    return $country_name;

  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_zone_name
  //
  // Arguments   : country           country code string
  //               zone              state/province zone_id
  //               def_state         default string if zone==0
  //
  // Return      : state_prov_name   state/province name
  //
  // Description : Function to retrieve the state/province name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_zone_name($country, $zone, $def_state) {

    $state_prov_query = tep_db_query("select zone_name from tax_zones where zone_country_id = '" . $country . "' and zone_id = '" . $zone . "'");

    if (!tep_db_num_rows($state_prov_query)) {
      $state_prov_name = $def_state;
    }
    else {
      $state_prov_values = tep_db_fetch_array($state_prov_query);
      $state_prov_name = $state_prov_values['zone_name'];
    }
    
    return $state_prov_name;
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

    $state_prov_query = tep_db_query("select zone_code from tax_zones where zone_country_id = '" . $country . "' and zone_id = '" . $zone . "'");

    if (!tep_db_num_rows($state_prov_query)) {
      $state_prov_code = $def_state;
    }
    else {
      $state_prov_values = tep_db_fetch_array($state_prov_query);
      $state_prov_code = $state_prov_values['zone_code'];
    }
    
    return $state_prov_code;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_tax_rate
  //
  // Arguments   : tax_zone_id, tax_class_id
  //
  // Return      : tax_rate DECIMAL
  //
  // Description : Function to retrieve the tax rate for a given zone/product_class
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_tax_rate($zone, $class) {
    $tax_query = tep_db_query("select tax_rate from tax_rates where tax_zone_id = '" . $zone . "' and tax_class_id = '" . $class . "'");

    $tax = TAX_VALUE;
    if (tep_db_num_rows($tax_query)) {
      $tax_values = tep_db_fetch_array($tax_query);
      $tax = $tax_values['tax_rate'];
    }
    return $tax;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_tax_description
  //
  // Arguments   : tax_zone_id, tax_class_id
  //
  // Return      : tax_description string
  //
  // Description : Function to retrieve the tax decription for a given zone/product_class
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_tax_description($zone, $class) {
    $tax_query = tep_db_query("select tax_description from tax_rates where tax_zone_id = '" . $zone . "' and tax_class_id = '" . $class . "'");

    $tax_des = "Unknown Tax Rate";
    if (tep_db_num_rows($tax_query)) {
      $tax_values = tep_db_fetch_array($tax_query);
      $tax_des = $tax_values['tax_description'];
    }
    return $tax_des;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_count_products_in_category
  //
  // Arguments   : catetories_id          catetories_id to count products for
  //               include_deactivated    1=includes deactivated products, 0=excludes (default)
  //
  // Return      : products count
  //
  // Description : Function to count products in a category including all child categories
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_count_products_in_category($categories_id, $include_deactivated=0) {
    $products_count = 0;

    if ($include_deactivated)
      $total_products = tep_db_query("select count(*) as total from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $categories_id . "'");
    else
      $total_products = tep_db_query("select count(*) as total from products p, products_to_categories p2c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $categories_id . "'");

    $total_products_values = tep_db_fetch_array($total_products);

    $products_count += $total_products_values['total'];
    
    $child_categories = tep_db_query("select categories_id from categories where parent_id = '" . $categories_id . "'");
    if (tep_db_num_rows($child_categories)) {
      while ($child_categories_values = tep_db_fetch_array($child_categories)) {
        $products_count += tep_count_products_in_category($child_categories_values['categories_id'], $include_deactivated);
      }
    }

    return $products_count;
  }

//////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_get_address_format_id
//
// Arguments	: country_id
//
// Return	: address_format_id
//
// Description	: For a given Countries_id return the address_format_id for that country
//
//////////////////////////////////////////////////////////////////////////////////////////

function tep_get_address_format_id($country_id)
{
  $format = tep_db_query("select address_format_id as format_id from countries where countries_id = '" . $country_id . "'");
  $format_values = tep_db_fetch_array($format);
  $fmt_id = $format_values['format_id'];
  if (!$fmt_id) $fmt_id = '1';
  return $fmt_id;
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

  $streets = $street;
  if ($suburb != '') $streets = $street . $cr . $suburb;
  if ($firstname == '') $firstname = addslashes($delivery_values['name']);
  if ($country == '') $country = addslashes($delivery_values['country']);
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

function tep_address_label($customers_id, $address_id, $html=0, $boln='', $eoln="\n") {
  if ($address_id == 0) {
    $delivery = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_city as city, customers_postcode as postcode, customers_state as state, customers_zone_id as zone_id, customers_country_id as country_id from customers where customers_id = '" . $customers_id . "'");
  } else {
    $delivery = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where address_book_id = '" . $address_id . "'");
  }
  $delivery_values = tep_db_fetch_array($delivery);
  $format_id = tep_get_address_format_id($delivery_values['country_id']);
  return tep_address_format($format_id, $delivery_values, $html, $boln, $eoln);
}

//////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: tep_address_summary
//
// Arguments	: customers_id, address_id
//
// Return	: properly formatted address summary
//
// Description	: This function will lookup the Addres format from the countries database
//		  and properly format the address summary.
//
//////////////////////////////////////////////////////////////////////////////////////////

function tep_address_summary($customers_id, $address_id) {
  if ($address_id == 0) {
    $delivery = tep_db_query("select customers_suburb as suburb, customers_city as city, customers_state as state, customers_zone_id as zone_id, customers_country_id as country_id from customers where customers_id = '" . $customers_id . "'");
  } else {
    $delivery = tep_db_query("select entry_suburb as suburb, entry_city as city, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where address_book_id = '" . $address_id . "'");
  }
  $delivery_values = tep_db_fetch_array($delivery);
  $country_id = $delivery_values['country_id'];
  $format_id = tep_get_address_format_id($country_id);
  $format = tep_db_query("select address_summary as summary from address_format where address_format_id = '" . $format_id . "'");
  $format_values = tep_db_fetch_array($format);
  $suburb = addslashes($delivery_values['suburb']);
  $city = addslashes($delivery_values['city']);
  $state = addslashes($delivery_values['state']);
  $zone_id = $delivery_values['zone_id'];
  $country = tep_get_country_name($country_id);
  $state = tep_get_zone_code($country_id, $zone_id, $state);

  $fmt = $format_values['summary'];
  eval("\$address = \"$fmt\";");
  $address = stripslashes($address);
  return $address;
}
?>
