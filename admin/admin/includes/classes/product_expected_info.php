<?php
  class productExpectedInfo {
    var $id, $products_name, $date_available;

// class constructor
    function productExpectedInfo($peInfo_array) {
      $this->id = $peInfo_array['products_id'];
      $this->products_name = $peInfo_array['products_name'];
      $this->date_available = $peInfo_array['products_date_available'];
    }
  }
?>