<?php
  class backupInfo {
    var $file, $date, $size, $compression;

// class constructor
    function backupInfo($buInfo_array) {
      $this->file = $buInfo_array['entry'];
      $this->date = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $buInfo_array['entry']));
      $this->size = number_format(filesize(DIR_FS_BACKUP . $buInfo_array['entry'])) . ' bytes';

      $extension = substr($buInfo_array['entry'], -3);
      switch ($extension) {
        case 'zip': $this->compression = 'ZIP'; break;
        case '.gz': $this->compression = 'GZIP'; break;
        default: $this->compression = 'None'; break;
      }
    }
  }
?>