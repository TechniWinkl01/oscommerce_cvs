<?php
/*
  $Id: statistics.php,v 1.1 2004/07/22 23:09:32 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Statistics {

// Private variables

    var $_icon, $_title, $_header, $_data, $_resultset;

// Public methods

    function &getIcon() {
      return $this->_icon;
    }

    function &getTitle() {
      return $this->_title;
    }

    function &getHeader() {
      return $this->_header;
    }

    function &getData() {
      return $this->_data;
    }

    function activate() {
      $this->_setHeader();
      $this->_setData();
    }

    function displayBatchLinksTotal($text) {
      return $this->_resultset->displayBatchLinksTotal($text);
    }

    function displayBatchLinksPullDown($batch_keyword = 'page', $parameters = '') {
      return $this->_resultset->displayBatchLinksPullDown($batch_keyword, $parameters);
    }

    function isBatchQuery() {
      return $this->_resultset->isBatchQuery();
    }
  }
?>
