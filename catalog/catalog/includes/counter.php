<?php
/*
  $Id: counter.php,v 1.6 2004/02/16 07:15:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  $Qcounter = $osC_Database->query('select startdate, counter from :table_counter');
  $Qcounter->bindRaw(':table_counter', TABLE_COUNTER);
  $Qcounter->execute();

  if ($Qcounter->numberOfRows()) {
    $counter_startdate = $Qcounter->value('startdate');
    $counter_now = $Qcounter->valueInt('counter') + 1;

    $Qcounterupdate = $osC_Database->query('update :table_counter set counter = :counter');
    $Qcounterupdate->bindRaw(':table_counter', TABLE_COUNTER);
    $Qcounterupdate->bindInt(':counter', $counter_now);
    $Qcounterupdate->execute();

    $Qcounterupdate->freeResult();
  } else {
    $counter_startdate = date('Ymd');
    $counter_now = 1;

    $Qcounterupdate = $osC_Database->query('insert into :table_counter (startdate, counter) values (:start_date, 1)');
    $Qcounterupdate->bindRaw(':table_counter', TABLE_COUNTER);
    $Qcounterupdate->bindValue(':start_date', $counter_startdate);
    $Qcounterupdate->execute();

    $Qcounterupdate->freeResult();
  }

  $Qcounter->freeResult();

  $counter_startdate_formatted = strftime(DATE_FORMAT_LONG, mktime(0, 0, 0, substr($counter_startdate, 4, 2), substr($counter_startdate, -2), substr($counter_startdate, 0, 4)));
?>
