<?php
/*
  $Id: breadcrumb.php,v 1.1 2002/07/21 23:38:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class breadcrumb {
    var $trail;

    function breadcrumb() {
      $this->reset();
    }

    function reset() {
      $this->trail = array();
    }

    function add($title, $link = '') {
      $this->trail[] = array('title' => $title, 'link' => $link);
    }

    function trail($separator = ' - ') {
      $trail_string = '';
      $trail_size = sizeof($this->trail);

      for ($i=0; $i<$trail_size; $i++) {
        if (tep_not_null($this->trail[$i]['link'])) {
          $trail_string .= '<a href="' . $this->trail[$i]['link'] . '" class="headerNavigation">' . $this->trail[$i]['title'] . '</a>';
        } else {
          $trail_string .= $this->trail[$i]['title'];
        }

        if (($i+1) < $trail_size) $trail_string .= $separator;
      }

      return $trail_string;
    }
  }
?>
