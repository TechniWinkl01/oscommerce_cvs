<?
  class bannerInfo {
    var $id, $title, $url, $image, $group, $html_text, $expires_impressions, $expires_date, $date_scheduled, $date_added, $date_status_change, $status, $clicked, $shown, $expires_date_caljs_year, $expires_date_caljs_month, $expires_date_caljs_day, $scheduled_date_caljs_year, $scheduled_date_caljs_month, $scheduled_date_caljs_day;

// class constructor
    function bannerInfo($bInfo_array) {
      $this->id = $bInfo_array['banners_id'];
      $this->title = $bInfo_array['banners_title'];
      $this->url = $bInfo_array['banners_url'];
      $this->image = $bInfo_array['banners_image'];
      $this->group = $bInfo_array['banners_group'];
      $this->html_text = $bInfo_array['banners_html_text'];
      $this->expires_impressions = $bInfo_array['expires_impressions'];
      $this->expires_date = $bInfo_array['expires_date'];
      $this->date_scheduled = $bInfo_array['date_scheduled'];
      $this->date_added = $bInfo_array['date_added'];
      $this->date_status_change = $bInfo_array['date_status_change'];
      $this->status = $bInfo_array['status'];
      $this->clicked = $bInfo_array['banners_clicked'];
      $this->shown = $bInfo_array['banners_shown'];

      $this->expires_date_caljs_year = substr($this->expires_date, 0, 4);
      $this->expires_date_caljs_month = substr($this->expires_date, 5, 2);
      $this->expires_date_caljs_day = substr($this->expires_date, 8, 2);

      $this->scheduled_date_caljs_year = substr($this->date_scheduled, 0, 4);
      $this->scheduled_date_caljs_month = substr($this->date_scheduled, 5, 2);
      $this->scheduled_date_caljs_day = substr($this->date_scheduled, 8, 2);
    }
  }
?>