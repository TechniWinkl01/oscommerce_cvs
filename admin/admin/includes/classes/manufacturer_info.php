<?
  class manufacturerInfo {
    var $id, $name, $image, $location, $products_count;

// class constructor
    function manufacturerInfo($mInfo_array) {
      $this->id = $mInfo_array['manufacturers_id'];
      $this->name = $mInfo_array['manufacturers_name'];
      $this->image = $mInfo_array['manufacturers_image'];
      $this->location = $mInfo_array['manufacturers_location'];
      $this->products_count = $mInfo_array['total'];
    }
  }
?>