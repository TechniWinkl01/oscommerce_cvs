<?
  class productInfo {
    var $id, $name, $image, $description, $quantity, $model, $url, $price, $date_added, $date_available, $date_available_caljs_year, $date_available_caljs_month, $date_available_caljs_day, $status, $tax_class, $weight, $manufacturer, $manufacturers_id, $manufacturers_image, $average_rating, $last_modified;

// class constructor
    function productInfo($pInfo_array) {
      if ($pInfo_array['year'] && $pInfo_array['month'] && $pInfo_array['day']) {
        $pInfo_array['products_date_available'] = $pInfo_array['year'];
        $pInfo_array['products_date_available'] .= (strlen($pInfo_array['month']) == 1) ? '0' . $pInfo_array['month'] : $pInfo_array['month'];
        $pInfo_array['products_date_available'] .= (strlen($pInfo_array['day']) == 1) ? '0' . $pInfo_array['day'] : $pInfo_array['day'];
      }

      $this->id = $pInfo_array['products_id'];
      $this->name = $pInfo_array['products_name'];
      $this->image = $pInfo_array['products_image'];
      $this->description = stripslashes($pInfo_array['products_description']);
      $this->quantity = $pInfo_array['products_quantity'];
      $this->model = $pInfo_array['products_model'];
      $this->url = $pInfo_array['products_url'];
      $this->price = $pInfo_array['products_price'];
      $this->date_added = $pInfo_array['products_date_added'];
      $this->last_modified = $pInfo_array['products_last_modified'];

      if (strlen($pInfo_array['products_date_available']) < 8) {
        $this->date_available = date('Ymd');
      } else {
        $this->date_available = $pInfo_array['products_date_available'];
      }

      $this->date_available_caljs_year = substr($this->date_available, 0, 4);
      $this->date_available_caljs_month = substr($this->date_available, 5, 2);
      $this->date_available_caljs_day = substr($this->date_available, 8, 2);

      $this->weight = $pInfo_array['products_weight'];
      $this->tax_class = $pInfo_array['products_tax_class_id'];
      $this->status = $pInfo_array['products_status'];
      $this->manufacturer = $pInfo_array['manufacturers_name'];
      $this->manufacturers_id = $pInfo_array['manufacturers_id'];
      $this->manufacturers_image = $pInfo_array['manufacturers_image'];
      $this->average_rating = $pInfo_array['average_rating'];
    }
  }
?>
