<?php
/*
  $Id: banner_daily.php,v 1.4 2004/10/30 22:49:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('external/panachart/panachart.php');

  $views = array();
  $clicks = array();
  $vLabels = array();

  $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
  $month = isset($_GET['month']) ? $_GET['month'] : date('n');

  $days = date('t', mktime(0, 0, 0, $month))+1;
  $stats = array();
  for ($i=1; $i<$days; $i++) {
    $stats[] = array($i, '0', '0');

    $views[$i-1] = 0;
    $clicks[$i-1] = 0;
    $vLabels[] = $i;
  }

  $Qstats = $osC_Database->query('select dayofmonth(banners_history_date) as banner_day, banners_shown as value, banners_clicked as dvalue from :table_banners_history where banners_id = :banners_id and month(banners_history_date) = :month and year(banners_history_date) = :year');
  $Qstats->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
  $Qstats->bindInt(':banners_id', $_GET['bID']);
  $Qstats->bindInt(':month', $month);
  $Qstats->bindInt(':year', $year);
  $Qstats->execute();

  while ($Qstats->next()) {
    $stats[($Qstats->valueInt('banner_day')-1)] = array($Qstats->valueInt('banner_day'), (($Qstats->valueInt('value') > 0) ? $Qstats->valueInt('value') : '0'), (($Qstats->valueInt('dvalue') > 0) ? $Qstats->valueInt('dvalue') : '0'));

    $views[($Qstats->valueInt('banner_day')-1)] = $Qstats->valueInt('value');
    $clicks[($Qstats->valueInt('banner_day')-1)] = $Qstats->valueInt('dvalue');
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf(TEXT_BANNERS_DAILY_STATISTICS, $Qbanner->value('banners_title'), strftime('%B', mktime(0, 0, 0, $month)), $year), '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, '');
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_daily-' . $_GET['bID'] . '.' . $image_extension);
?>
