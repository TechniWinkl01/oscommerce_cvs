<?php
/*
  $Id: cache.php,v 1.6 2004/02/16 06:31:50 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  Class usage examples:

  - Caching HTML:
    if ($osC_Cache->read('key', 60) === false) {
      $osC_Cache->startBuffer();
      ------ PHP/HTML LOGIC HERE ------
      $osC_Cache->stopBuffer();
    }

    echo $osC_Cache->getCache();

  - Caching data (in memory):
    if ($osC_Cache->read('key', 60) {
      $variable = $osC_Cache->getCache();
    } else {
      $variable = array('some', 'data');

      $osC_Cache->writeBuffer($variable);
    }
*/

  class osC_Cache {
    var $cached_data,
        $cache_key;

    function write($key, &$data) {
      $filename = DIR_FS_WORK . $key . '.cache';

      if ($fp = @fopen($filename, 'w')) {
        flock($fp, 2); // LOCK_EX
        fputs($fp, serialize($data));
        flock($fp, 3); // LOCK_UN
        fclose($fp);

        return true;
      }

      return false;
    }

    function read($key, $expire = 0) {
      $this->cache_key = $key;

      $filename = DIR_FS_WORK . $key . '.cache';

      if (file_exists($filename)) {
        $difference = floor((time() - filemtime($filename)) / 60);

        if ( ($expire == '0') || ($difference < $expire) ) {
          if ($fp = @fopen($filename, 'r')) {
            $this->cached_data = unserialize(fread($fp, filesize($filename)));

            fclose($fp);

            return true;
          }
        }
      }

      return false;
    }

    function &getCache() {
      return $this->cached_data;
    }

    function startBuffer() {
      ob_start();
    }

    function stopBuffer() {
      $this->cached_data = ob_get_contents();

      ob_end_clean();

      $this->write($this->cache_key, $this->cached_data);
    }

    function writeBuffer(&$data) {
      $this->cached_data = $data;

      $this->write($this->cache_key, $this->cached_data);
    }
  }
?>
