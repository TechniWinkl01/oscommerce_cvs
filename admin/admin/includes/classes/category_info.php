<?
  class categoryInfo {
    var $id, $name, $image, $sort_order, $parent_id, $childs_count, $products_count;

// class constructor
    function categoryInfo($cInfo_array) {
      $this->id = $cInfo_array['categories_id'];
      $this->name = $cInfo_array['categories_name'];
      $this->image = $cInfo_array['categories_image'];
      $this->sort_order = $cInfo_array['sort_order'];
      $this->parent_id = $cInfo_array['parent_id'];
      $this->childs_count = $cInfo_array['childs_count'];
      $this->products_count = $cInfo_array['products_count'];
    }
  }
?>