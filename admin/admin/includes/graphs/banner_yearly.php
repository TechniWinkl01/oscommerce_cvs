<?php
/*
  $Id: banner_yearly.php,v 1.4 2004/08/15 18:18:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('external/panachart/panachart.php');

  $views = array();
  $clicks = array();
  $vLabels = array();

  $stats = array();
  $banner_stats_query = tep_db_query("select year(banners_history_date) as year, sum(banners_shown) as value, sum(banners_clicked) as dvalue from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $_GET['bID'] . "' group by year");
  while ($banner_stats = tep_db_fetch_array($banner_stats_query)) {
    $stats[] = array($banner_stats['year'], (($banner_stats['value']) ? $banner_stats['value'] : '0'), (($banner_stats['dvalue']) ? $banner_stats['dvalue'] : '0'));

    $views[] = $banner_stats['value'];
    $clicks[] = $banner_stats['dvalue'];
    $vLabels[] = $banner_stats['year'];
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf(TEXT_BANNERS_YEARLY_STATISTICS, $Qbanner->value('banners_title')), '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, '');
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_yearly-' . $_GET['bID'] . '.' . $image_extension);
?>
