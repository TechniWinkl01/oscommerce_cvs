<?
  class productInfo {
    var $id, $name, $image, $description, $quantity, $model, $url, $price, $date_added, $status, $tax_class, $weight, $manufacturer, $manufacturers_id, $manufacturers_image, $average_rating;

// class constructor
    function productInfo($pInfo_array) {
      $this->id = $pInfo_array['products_id'];
      $this->name = $pInfo_array['products_name'];
      $this->image = $pInfo_array['products_image'];
      $this->description = stripslashes($pInfo_array['products_description']);
      $this->quantity = $pInfo_array['products_quantity'];
      $this->model = $pInfo_array['products_model'];
      $this->url = $pInfo_array['products_url'];
      $this->price = $pInfo_array['products_price'];
      $this->date_added = $pInfo_array['products_date_added'];
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
