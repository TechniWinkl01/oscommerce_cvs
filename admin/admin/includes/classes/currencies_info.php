<?php
  class currenciesInfo {
    var $id, $title, $code, $symbol_left, $symbol_right, $decimal_point, $thousands_point, $decimal_places, $last_updated, $value;

// class constructor
    function currenciesInfo($cInfo_array) {
      $this->id = $cInfo_array['currencies_id'];
      $this->title = $cInfo_array['title'];
      $this->code = $cInfo_array['code'];
      $this->symbol_left = htmlspecialchars($cInfo_array['symbol_left']);
      $this->symbol_right = htmlspecialchars($cInfo_array['symbol_right']);
      $this->decimal_point = $cInfo_array['decimal_point'];
      $this->thousands_point = $cInfo_array['thousands_point'];
      $this->decimal_places = $cInfo_array['decimal_places'];
      $this->last_updated = $cInfo_array['last_updated'];
      $this->value = $cInfo_array['value'];
    }
  }
?>