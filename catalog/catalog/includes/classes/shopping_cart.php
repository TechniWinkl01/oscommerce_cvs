<?
  class shoppingCart {
    var $contents, $total;

    function shoppingCart($contents='') {
      if (SESSION_OBJECTS_ALLOWED) {
        $this->reset();
      } else {
        global $cart, $cart_contents, $cart_total;
        if (!tep_session_is_registered('cart_contents')) {
          tep_session_register('cart');
          tep_session_register('cart_contents');
          tep_session_register('cart_total');
        }
        $this->string_to_contents($contents);
        $this->total = $cart_total;
      }
    }

    function contents_to_string() {
      $str = '';
      if ($this->contents) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
            $qty = $this->contents[$products_id]['qty'];
            $pstr = $products_id . '#' . $qty . '#';
            $ostr = '';
            if ($this->contents[$products_id]['attributes']) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                if ($ostr != '') $ostr .= '_';
                $ostr .= $option . '.' . $value;
              }
            }
            if ($str != '') $str .= '|';
             $str .= $pstr . $ostr;
        }
      }
      return $str;
    }

    function sync() {
      global $cart_contents, $cart_total;
      if (!SESSION_OBJECTS_ALLOWED) {
        $cart_contents = $this->contents_to_string();
        $cart_total = $this->total;
      }
    }

    function string_to_contents($cont) {
      $this->contents = array();
      if ($cont != '') {
        $this->total = 0;
        $ccont = explode('|', $cont);
        while (list( ,$prods) = each($ccont)) {
          $prod = explode('#', $prods);
          $product_id = $prod[0];
          $qty = $prod[1];
          $this->contents[$product_id]['qty'] = $qty;
          $options = $prod[2];
          if ($options != '') {
            $option = explode('_', $options);
            for ($i=0;$i<sizeof($option);$i++) {
              $ostr = $option[$i];
              $opt = explode('.', $ostr);
              $this->contents[$product_id]['attributes'][$opt[0]] = $opt[1];
            }
          }
        }
      }
    }

    function restore_contents() {
      global $customer_id;

      if (!$customer_id) return 0;

// insert current cart contents in database
      if ($this->contents) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
          $product_query = tep_db_query("select products_id from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $products_id . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into customers_basket (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . $customer_id . "', '" . $products_id . "', '" . $qty . "', '" . date('Ymd') . "')");
            if ($this->contents[$products_id]['attributes']) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                tep_db_query("insert into customers_basket_attributes (customers_id, products_id, products_options_id, products_options_value_id) values ('" . $customer_id . "', '" . $products_id . "', '" . $option . "', '" . $value . "')");
              }
            }
          } else {
            tep_db_query("update customers_basket set customers_basket_quantity = '" . $qty . "' where customers_id = '" . $customer_id . "' and products_id = '" . $products_id . "'");
          }
        }
      }

// reset per-session cart contents, but not the database contents
      $this->reset(FALSE);

      $products_query = tep_db_query("select products_id, customers_basket_quantity from customers_basket where customers_id = '" . $customer_id . "'");
      while ($products = tep_db_fetch_array($products_query)) {
        $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
// attributes
        $attributes_query = tep_db_query("select products_options_id, products_options_value_id from customers_basket_attributes where customers_id = '" . $customer_id . "' and products_id = '" . $products['products_id'] . "'");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
        }
      }

      $this->cleanup();
    }

    function reset($reset_database = TRUE) {
      global $customer_id;

      $this->contents = array();
      $this->total = 0;

      if ($customer_id && $reset_database) {
        tep_db_query("delete from customers_basket where customers_id = '" . $customer_id . "'");
        tep_db_query("delete from customers_basket_attributes where customers_id = '" . $customer_id . "'");
      }
      $this->sync();
    }

    function add_cart($products_id, $qty, $attributes = '') {
      global $new_products_id_in_cart, $customer_id;

      if ($this->in_cart($products_id)) {
        $this->update_quantity($products_id, $qty, $attributes);
      } else {
        $this->contents[] = array($products_id);
        $this->contents[$products_id] = array('qty' => $qty);
// insert into database
        if ($customer_id) tep_db_query("insert into customers_basket (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . $customer_id . "', '" . $products_id . "', '" . $qty . "', '" . date('Ymd') . "')");

        if ($attributes != '') {
          reset($attributes);
          while (list($option, $value) = each($attributes)) {
            $this->contents[$products_id]['attributes'][$option] = $value;
// insert into database
            if ($customer_id) tep_db_query("insert into customers_basket_attributes (customers_id, products_id, products_options_id, products_options_value_id) values ('" . $customer_id . "', '" . $products_id . "', '" . $option . "', '" . $value . "')");
          }
        }
      }
      $new_products_id_in_cart = $products_id;
      tep_session_register('new_products_id_in_cart');
      $this->cleanup();
    }

    function update_quantity($products_id, $quantity, $attributes = '') {
      global $customer_id;

      $this->contents[$products_id] = array('qty' => $quantity);
// update database
      if ($customer_id) tep_db_query("update customers_basket set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . $customer_id . "' and products_id = '" . $products_id . "'");

      if ($attributes != '') {
        reset($attributes);
        while (list($option, $value) = each($attributes)) {
          $this->contents[$products_id]['attributes'][$option] = $value;
// update database
          if ($customer_id) tep_db_query("update customers_basket_attributes set products_options_id = '" . $option . "' and products_options_value_id = '" . $value . "' where customers_id = '" . $customer_id . "' and products_id = '" . $products_id . "'");
        }
      }
      $this->sync();
    }

	function cleanup() {
      global $customer_id;

      reset($this->contents);
      while (list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);
// remove from database
          if ($customer_id) {
            tep_db_query("delete from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $key . "'");
            tep_db_query("delete from customers_basket_attributes where customers_id = '" . $customer_id . "' and products_id = '" . $key . "'");
          }
        }
      }
      $this->sync();
    }

    function count_contents() {
      $count = 0;
      for ($i=0; $i<sizeof($this->contents); $i++) {
        $count++;
      }
      return $count;
    }

    function get_quantity($products_id) {
      if ($this->contents[$products_id]) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      if ($this->contents[$products_id]) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {
      global $customer_id;

      unset($this->contents[$products_id]);
// remove from database
      if ($customer_id) {
        tep_db_query("delete from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $products_id . "'");
        tep_db_query("delete from customers_basket_attributes where customers_id = '" . $customer_id . "' and products_id = '" . $products_id . "'");
      }
      $this->sync();
    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents))
      {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }
      return substr($product_id_list, 2);
    }

    function calculate() {
      $this->total = 0;
      $sql_in = $this->get_product_id_list();
      if (empty($sql_in)) return 0;

// products price
      $product_query = tep_db_query("select products_id, products_price from products where products_id in (" . $sql_in . ")");
      while ($product = tep_db_fetch_array($product_query)) {
        $products_id = $product['products_id'];
        $products_price = $product['products_price'];

        $specials_query = tep_db_query("select specials_new_products_price from specials where products_id = '" . $products_id . "'");
        if (tep_db_num_rows ($specials_query)) {
          $specials = tep_db_fetch_array($specials_query);
          $products_price = $specials['specials_new_products_price'];
        }

        $this->total += ($this->contents[$products_id]['qty'] * $products_price);
      }

// attributes price
      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $qty = $this->contents[$products_id]['qty'];
        if ($this->contents[$products_id]['attributes']) {
          reset($this->contents[$products_id]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
            $attribute_price_query = tep_db_query("select options_values_price, price_prefix from products_attributes where products_id = '" . $products_id . "' and options_id = '" . $option . "' and options_values_id = '" . $value . "'");
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $this->total += $qty * $attribute_price['options_values_price'];
            } else {
              $this->total -= $qty * $attribute_price['options_values_price'];
            }
          }
        }
      }
    }

    function attributes_price($products_id) {
      if ($this->contents[$products_id]['attributes']) {
        reset($this->contents[$products_id]['attributes']);
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
          $attribute_price_query = tep_db_query("select options_values_price, price_prefix from products_attributes where products_id = '" . $products_id . "' and options_id = '" . $option . "' and options_values_id = '" . $value . "'");
          $attribute_price = tep_db_fetch_array($attribute_price_query);
          if ($attribute_price['price_prefix'] == '+') {
            $attributes_price += $this->contents[$products_id]['qty'] * $attribute_price['options_values_price'];
          } else {
            $attributes_price -= $this->contents[$products_id]['qty'] * $attribute_price['options_values_price'];
          }
        }
      }

      return $attributes_price;
    }

    function get_products() {
      $products_array = array();
      $sql_in = $this->get_product_id_list();
      if (empty($sql_in)) return 0;
      $products_query = tep_db_query("select products_id, products_name, products_model, products_price, products_weight, products_tax_class_id from products where products_id in (" . $sql_in . ")");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_id = $products['products_id'];
        $products_price = $products['products_price'];

        $specials_query = tep_db_query("select specials_new_products_price from specials where products_id = '" . $products_id . "'");
        if (tep_db_num_rows($specials_query)) {
          $specials = tep_db_fetch_array($specials_query);
          $products_price = $specials['specials_new_products_price'];
        }

        $products_array[] = array('id' => $products_id,
                                  'name' => $products['products_name'],
                                  'model' => $products['products_model'],
                                  'price' => $products_price,
                                  'quantity' => $this->contents[$products_id]['qty'],
                                  'weight' => $products['products_weight'],
                                  'final_price' => (($products_price * $this->contents[$products_id]['qty']) + $this->attributes_price($products_id)),
                                  'tax_class_id' => $products['products_tax_class_id']);
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }
  }
?>
