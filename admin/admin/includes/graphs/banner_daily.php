<?php
/*
  $Id: banner_daily.php,v 1.3 2004/08/15 18:18:35 hpdl Exp $

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

  $days = date('t', mktime(0,0,0,$month))+1;
  $stats = array();
  for ($i=1; $i<$days; $i++) {
    $stats[] = array($i, '0', '0');

    $views[$i-1] = 0;
    $clicks[$i-1] = 0;
    $vLabels[] = $i;
  }

  $banner_stats_query = tep_db_query("select dayofmonth(banners_history_date) as banner_day, banners_shown as value, banners_clicked as dvalue from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $_GET['bID'] . "' and month(banners_history_date) = '" . $month . "' and year(banners_history_date) = '" . $year . "'");
  while ($banner_stats = tep_db_fetch_array($banner_stats_query)) {
    $stats[($banner_stats['banner_day']-1)] = array($banner_stats['banner_day'], (($banner_stats['value']) ? $banner_stats['value'] : '0'), (($banner_stats['dvalue']) ? $banner_stats['dvalue'] : '0'));

    $views[($banner_stats['banner_day']-1)] = $banner_stats['value'];
    $clicks[($banner_stats['banner_day']-1)] = $banner_stats['dvalue'];
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf(TEXT_BANNERS_DAILY_STATISTICS, $Qbanner->value('banners_title'), strftime('%B', mktime(0,0,0,$month)), $year), '#000000', 2);
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
