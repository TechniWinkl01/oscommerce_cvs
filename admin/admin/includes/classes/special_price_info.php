<?
  class specialPriceInfo {
    var $id, $products_id, $products_price, $products_image, $products_name, $specials_price, $percentage, $date_added;

// class constructor
    function specialPriceInfo($sInfo_array) {
      $this->id = $sInfo_array['specials_id'];
      $this->products_id = $sInfo_array['products_id'];
      $this->products_price = $sInfo_array['products_price'];
      $this->products_image = $sInfo_array['products_image'];
      $this->products_name = $sInfo_array['products_name'];
      $this->specials_price = $sInfo_array['specials_new_products_price'];
      $this->percentage = (100 - (($this->specials_price / $this->products_price) * 100));
      $this->date_added = $sInfo_array['specials_date_added'];
    }
  }
?>