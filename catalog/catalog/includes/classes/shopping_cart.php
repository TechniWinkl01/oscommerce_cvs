<?
  class shoppingCart {
    var $contents, $total;

    function shoppingCart() {
      $this->reset();
    }

    function reset() {
      $this->contents = array();
      $this->total = 0;
    }

    function add_cart($products_id, $qty, $attributes = '') {
      global $new_products_id_in_cart;

      if ($this->in_cart($products_id)) {
        $this->update_quantity($products_id, $qty, $attributes);
      } else {
        $this->contents[] = array($products_id);
        $this->contents[$products_id] = array('qty' => $qty);
        if ($attributes != '') {
          while(list($option, $value) = each($attributes)) {
            $this->contents[$products_id]['attributes'][$option] = $value;
          }
        }
      }
      $new_products_id_in_cart = $products_id;
      tep_session_register('new_products_id_in_cart');
      $this->cleanup();
    }

    function update_quantity($products_id, $quantity, $attributes = '') {
      $this->contents[$products_id] = array('qty' => $quantity);
      if ($attributes != '') {
        while(list($option, $value) = each($attributes)) {
          $this->contents[$products_id]['attributes'][$option] = $value;
        }
      }
    }

	function cleanup() {
      while(list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);
        }
      }
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
      unset($this->contents[$products_id]);
    }

    function remove_all() {
      $this->reset();
    }

	function get_product_id_list() {
      $product_id_list = '';
      reset($this->contents);
      while(list($products_id, ) = each($this->contents)) {
        $product_id_list .= ', ' . $products_id;
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
      while(list($products_id, ) = each($this->contents)) {
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