<?
  class productExpectedInfo {
    var $id, $products_name, $date_expected;

// class constructor
    function productExpectedInfo($peInfo_array) {
      $this->id = $peInfo_array['products_expected_id'];
      $this->products_name = $peInfo_array['products_name'];
      $this->date_expected = $peInfo_array['date_expected'];
    }
  }
?>