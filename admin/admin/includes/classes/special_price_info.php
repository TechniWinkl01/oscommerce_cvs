<?php
  class specialPriceInfo {
    var $id, $products_id, $products_price, $products_image, $products_name, $specials_price, $percentage, $date_added, $last_modified, $expires_date, $date_status_change, $status, $expires_date_caljs_day, $expires_date_caljs_month, $expires_date_caljs_year;

// class constructor
    function specialPriceInfo($sInfo_array) {
      $this->id = $sInfo_array['specials_id'];
      $this->products_id = $sInfo_array['products_id'];
      $this->products_price = $sInfo_array['products_price'];
      $this->products_image = $sInfo_array['products_image'];
      $this->products_name = $sInfo_array['products_name'];
      $this->specials_price = $sInfo_array['specials_new_products_price'];
      $this->percentage = @(100 - (($this->specials_price / $this->products_price) * 100));
      $this->date_added = $sInfo_array['specials_date_added'];
      $this->last_modified = $sInfo_array['specials_last_modified'];
      $this->expires_date = $sInfo_array['expires_date'];
      $this->date_status_change = $sInfo_array['date_status_change'];
      $this->status = $sInfo_array['status'];

      $this->expires_date_caljs_year = substr($this->expires_date, 0, 4);
      $this->expires_date_caljs_month = substr($this->expires_date, 5, 2);
      $this->expires_date_caljs_day = substr($this->expires_date, 8, 2);
    }
  }
?>