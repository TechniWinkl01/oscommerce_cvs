<?php
/*
  USAGE
  By default, the module comes with support for 1 zone.  This can be
  easily changed by editing the line below in the zones constructor 
  that defines $this->num_zones.

  Next, you will want to activate the module by going to the Admin screen,
  clicking on Modules, then clicking on Shipping.  A list of all shipping
  modules should appear.  Click on the green dot next to the one labeled 
  zones.php.  A list of settings will appear to the right.  Click on the
  Edit button. 

  PLEASE NOTE THAT YOU WILL LOSE YOUR CURRENT SHIPPING RATES AND OTHER 
  SETTINGS IF YOU TURN OFF THIS SHIPPING METHOD.  Make sure you keep a 
  backup of your shipping settings somewhere at all times.

  To enable this shipping method, make sure to set "Enable Zones Method" to 
  a value of "1".  To turn it off, set it to "0".

  If you want an additional handling charge applied to orders that use this
  method, set the Handling Fee field.

  Next, you will need to define which countries are in each zone.  Determining
  this might take some time and effort.  You should group a set of countries
  that has similar shipping charges for the same weight.  For instance, when
  shipping from the US, the countries of Japan, Australia, New Zealand, and 
  Singapore have similar shipping rates.  As an example, one of my customers
  is using this set of zones:
    1: USA
    2: Canada
    3: Austria, Belgium, Great Britain, France, Germany, Greenland, Iceland,
       Ireland, Italy, Norway, Holland/Netherlands, Denmark, Poland, Spain,
       Sweden, Switzerland, Finland, Portugal, Israel, Greece
    4: Japan, Australia, New Zealand, Singapore
    5: Taiwan, China, Hong Kong

  When you enter these country lists, enter them into the Zone X Countries
  fields, where "X" is the number of the zone.  They should be entered as
  two character ISO country codes in all capital letters.  They should be
  separated by commas with no spaces or other punctuation. For example:
    1: US
    2: CA
    3: AT,BE,GB,FR,DE,GL,IS,IE,IT,NO,NL,DK,PL,ES,SE,CH,FI,PT,IL,GR
    4: JP,AU,NZ,SG
    5: TW,CN,HK

  Now you need to set up the shipping rate tables for each zone.  Again,
  some time and effort will go into setting the appropriate rates.  You
  will define a set of weight ranges and the shipping price for each
  range.  For instance, you might want an order than weighs more than 0
  and less than or equal to 3 to cost 5.50 to ship to a certain zone.  
  This would be defined by this:  0-3:5.5

  You should combine a bunch of these rates together in a comma delimited
  list and enter them into the "Zone X Shipping Table" fields where "X" 
  is the zone number.  For example, this might be used for Zone 1:
    0-1:3.5,1-2:3.95,2-3:5.2,3-4:6.45,4-5:7.7,5-6:10.4,6-7:11.85,
    7-8:13.3,8-9:14.75,9-10:16.2,10-11:17.65,11-12:19.1,12-13:20.55,
    13-14:22,14-15:23.45

  The above example includes weights over 0 and up to 15.  Note that
  units are not specified in this explanation since they should be
  specific to your locale.

  CAVEATS
  At this time, you must make sure to include all possible values in
  your ranges.  For instance, if you have 4-5:7.7,6-7:11.85, then you've
  left an undetermined rate for orders that weigh between 5 and 6.  The
  module does not currently deal with this elegantly.

  Also, it does not deal with weights that are above the highest amount
  defined.  This will probably be the next area to be improved with the
  module.  For now, you could have one last very high range with a very
  high shipping rate to discourage orders of that magnitude.  For 
  instance:  15-999:1000

  If you want to be able to ship to any country in the world, you will 
  need to enter every country code into the Country fields. For most
  shops, you will not want to enter every country.  This is often 
  because of too much fraud from certain places. If a country is not
  listed, then the module will add a $0.00 shipping charge and will
  indicate that shipping is not available to that destination.  
  PLEASE NOTE THAT THE ORDER CAN STILL BE COMPLETED AND PROCESSED!

  It appears that the TEP shipping system automatically rounds the 
  shipping weight up to the nearest whole unit.  This makes it more
  difficult to design precise shipping tables.  If you want to, you 
  can hack the shipping.php file to get rid of the rounding.

  Lastly, there is a limit of 255 characters on each of the Zone
  Shipping Tables and Zone Countries. 

*/

  class zones {
    var $code, $title, $description, $enabled, $num_zones;

// class constructor
    function zones() {
      $this->code = 'zones';
      $this->title = MODULE_SHIPPING_ZONES_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->enabled = MODULE_SHIPPING_ZONES_STATUS;

      // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
      $this->num_zones = 1;
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . '&nbsp; ' . MODULE_SHIPPING_ZONES_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                          '    <td align="right" class="main">&nbsp;' . tep_draw_checkbox_field('shipping_quote_zones', '1', true) . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }


    function quote() {
      global $shipping_quote_zones, $shipping_quote_all, $address_values, $shipping_weight, $shipping_quoted, $shipping_zones_cost, $shipping_zones_method;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_zones'] == '1') ) {
        $shipping_quoted = 'zones';
        
        $destination = tep_get_countries($address_values['country_id'], '1');
        $dest_country = $destination['countries_iso_code_2'];
        $dest_zone = 0;
        for ($i = 1; $i <= $this->num_zones; $i ++) {
          $countries_table = constant('MODULE_SHIPPING_ZONES_COUNTRIES_' . $i);
          $country_zones = split("[,]", $countries_table);
          if ( in_array($dest_country, $country_zones ) ) {
            $dest_zone = $i;
            break;
          }
        }
        if ($dest_zone == 0) {
          $shipping_zones_cost = 0;
          $shipping_zones_method = MODULE_SHIPPING_ZONES_INVALID_ZONE;
          return;
        }
        $shipping = -1;
        $zones_cost = constant('MODULE_SHIPPING_ZONES_COST_' . $i);
        $zones_table = split("[-:,]" , $zones_cost);
        $n=1;
        $y=2;
        for ($i = 0; $i < count($zones_table); $i ++) {
          if ( ($shipping_weight > $zones_table[$i]) && ($shipping_weight <= $zones_table[$n]) ) {
            $shipping = $zones_table[$y];
            $shipping_zones_method = MODULE_SHIPPING_ZONES_TEXT_WAY . ' ' . $dest_country . " : " . $shipping_weight . ' ' . MODULE_SHIPPING_ZONES_TEXT_UNITS;
            break;
          }
          $i = $i + 2;
          $n = $n + 3;
          $y = $y + 3;
        }
        if ( $shipping == -1) {
          $shipping_zones_cost = 0;
          $shipping_zones_method = MODULE_SHIPPING_ZONES_UNDEFINED_RATE;
        }
        else {
          $shipping_zones_cost = ($shipping + MODULE_SHIPPING_ZONES_HANDLING + SHIPPING_HANDLING);
        }
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_quote_zones, $shipping_quote_all, $shipping_cheapest, $shipping_cheapest_cost, $shipping_zones_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_zones) ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'zones';
          $shipping_cheapest_cost = $shipping_zones_cost;
        } else {
          if ($shipping_zones_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'zones';
            $shipping_cheapest_cost = $shipping_zones_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $HTTP_GET_VARS, $currencies, $shipping_cheapest, $shipping_zones_method, $shipping_zones_cost, $shipping_selected;

      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_zones'] == '1') ) {
        $display_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . '&nbsp;' . MODULE_SHIPPING_ZONES_TEXT_TITLE . ' <small><i>(' . $shipping_zones_method . ')</i></small>&nbsp;</td>' . "\n" .
                          '    <td align="right" class="main">&nbsp;' . $currencies->format($shipping_zones_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= '&nbsp;&nbsp;' . tep_draw_radio_field('shipping_selected', 'zones') .
                                              tep_draw_hidden_field('shipping_zones_cost', $shipping_zones_cost) .
                                              tep_draw_hidden_field('shipping_zones_method', $shipping_zones_method) . '&nbsp;</td>' . "\n";
        } else {
          $display_string .= '&nbsp;&nbsp;' . tep_draw_hidden_field('shipping_selected', 'zones') .
                                              tep_draw_hidden_field('shipping_zones_cost', $shipping_zones_cost) .
                                              tep_draw_hidden_field('shipping_zones_method', $shipping_zones_method) . '&nbsp;</td>' . "\n";
        }
        $display_string .= '  </tr>' . "\n" .
                           '</table>' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'zones') {
        $shipping_cost = $HTTP_POST_VARS['shipping_zones_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_zones_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Zones Method', 'MODULE_SHIPPING_ZONES_STATUS', '1', 'Do you want to offer zone rate shipping?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING', '0', 'Handling Fee for this shipping method', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
        $default_countries = '';
        if ($i == 1) {
          $default_countries = 'US,CA';
        }
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_" . $i ."', '" . $default_countries . "', 'Comma separated list of two character ISO country codes that are part of Zone " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zone " . $i ." Shipping Table', 'MODULE_SHIPPING_ZONES_COST_" . $i ."', '0-3:8.50,3-7:10.50,7-99:20.00', 'Shipping rates to Zone " . $i . " destinations based on a range of order weights. Example: 0-3:8.50,3-7:10.50,... Weights greater than 0 and less than or equal to 3 would cost 8.50 for Zone " . $i . " destinations.', '6', '0', now())");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES_HANDLING'");

      for ($i = 1; $i <= $this->num_zones; $i ++) { 
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES_COUNTRIES_" . $i ."'");
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ZONES_COST_" . $i ."'");
      }
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_ZONES_STATUS', 'MODULE_SHIPPING_ZONES_HANDLING');
      for ($i = 1; $i <= $this->num_zones; $i ++) {
        array_push($keys, 'MODULE_SHIPPING_ZONES_COUNTRIES_' . $i);
        array_push($keys, 'MODULE_SHIPPING_ZONES_COST_' . $i);
      }
      return $keys;
    }
  }
?>
