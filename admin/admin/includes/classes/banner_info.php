<?
  class bannerInfo {
    var $id, $title, $url, $image, $group, $status, $shown, $clicked, $expires_date, $expires_impressions, $date_status_change, $expires_date_caljs_year, $expires_date_caljs_month, $expires_date_caljs_day;

// class constructor
    function bannerInfo($bInfo_array) {
      $this->id = $bInfo_array['banners_id'];
      $this->title = $bInfo_array['banners_title'];
      $this->url = $bInfo_array['banners_url'];
      $this->image = $bInfo_array['banners_image'];
      $this->group = $bInfo_array['banners_group'];
      $this->status = $bInfo_array['status'];
      $this->shown = $bInfo_array['banners_shown'];
      $this->clicked = $bInfo_array['banners_clicked'];
      $this->expires_date = $bInfo_array['expires_date'];
      $this->expires_impressions = $bInfo_array['expires_impressions'];
      $this->date_status_change = $bInfo_array['date_status_change'];

      $this->expires_date_caljs_year = substr($this->expires_date, 0, 4);
      $this->expires_date_caljs_month = substr($this->expires_date, 5, 2);
      $this->expires_date_caljs_day = substr($this->expires_date, 8, 2);
    }
  }
?>