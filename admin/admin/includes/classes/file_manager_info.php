<?php
  class fileManagerInfo {
    var $key, $name, $last_modified, $size, $is_dir;

// class constructor
    function fileManagerInfo($fmInfo_array) {
      $this->key = $fmInfo_array['key'];
      $this->name = $fmInfo_array['name'];
      $this->last_modified = $fmInfo_array['last_modified'];
      $this->is_dir = $fmInfo_array['is_dir'];
      $this->size = $fmInfo_array['size'];
    }
  }
?>
