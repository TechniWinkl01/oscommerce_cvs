<?php
/*
  $Id: banner_monthly.php,v 1.4 2004/08/15 18:18:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('external/panachart/panachart.php');

  $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

  $stats = array();
  for ($i=1; $i<13; $i++) {
    $stats[] = array(strftime('%b', mktime(0,0,0,$i)), '0', '0');
  }

  $views = array();
  $clicks = array();

  $banner_stats_query = tep_db_query("select month(banners_history_date) as banner_month, sum(banners_shown) as value, sum(banners_clicked) as dvalue from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $_GET['bID'] . "' and year(banners_history_date) = '" . $year . "' group by banner_month");
  while ($banner_stats = tep_db_fetch_array($banner_stats_query)) {
    $stats[($banner_stats['banner_month']-1)] = array(strftime('%b', mktime(0,0,0,$banner_stats['banner_month'])), (($banner_stats['value']) ? $banner_stats['value'] : '0'), (($banner_stats['dvalue']) ? $banner_stats['dvalue'] : '0'));

    $views[($banner_stats['banner_month']-1)] = $banner_stats['value'];
    $clicks[($banner_stats['banner_month']-1)] = $banner_stats['dvalue'];
  }

  $vLabels = array();
  for ($i=1; $i<13; $i++) {
    $vLabels[] = strftime('%b', mktime(0,0,0,$i));

    if (!isset($views[$i-1])) {
      $views[$i-1] = 0;
    }

    if (!isset($clicks[$i-1])) {
      $clicks[$i-1] = 0;
    }
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf(TEXT_BANNERS_MONTHLY_STATISTICS, $Qbanner->value('banners_title'), $year), '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, $year);
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_monthly-' . $_GET['bID'] . '.' . $image_extension);
?>
