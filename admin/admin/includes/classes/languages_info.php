<?php
  class languagesInfo {
    var $id, $name, $code, $image, $directory, $charset, $direction, $sort_order;

// class constructor
    function languagesInfo($lInfo_array) {
      $this->id = $lInfo_array['languages_id'];
      $this->name = $lInfo_array['name'];
      $this->code = $lInfo_array['code'];
      $this->image = $lInfo_array['image'];
      $this->directory = $lInfo_array['directory'];
      $this->charset = $lInfo_array['charset'];
      $this->direction = $lInfo_array['direction'];
      $this->sort_order = $lInfo_array['sort_order'];
    }
  }
?>