<?php
  class languagesInfo {
    var $id, $name, $code, $image, $directory, $sort_order;

// class constructor
    function languagesInfo($lInfo_array) {
      $this->id = $lInfo_array['languages_id'];
      $this->name = $lInfo_array['name'];
      $this->code = $lInfo_array['code'];
      $this->image = $lInfo_array['image'];
      $this->directory = $lInfo_array['directory'];
      $this->sort_order = $lInfo_array['sort_order'];
    }
  }
?>
