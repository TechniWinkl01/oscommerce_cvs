<?php
/*
  $Id: banner_infobox.php,v 1.5 2004/08/15 18:18:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('external/panachart/panachart.php');

  $views = array(0, 0, 0);
  $clicks = array(0, 0, 0);
  $vLabels = array(0, 0, 0);

  $index = 2;

  $banner_stats_query = tep_db_query("select date_format(banners_history_date, '%e-%b') as name, banners_shown as value, banners_clicked as dvalue from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $banner_id . "' order by banners_history_date desc limit " . $days);
  while ($banner_stats = tep_db_fetch_array($banner_stats_query)) {
    $views[$index] = $banner_stats['value'];
    $clicks[$index] = $banner_stats['dvalue'];
    $vLabels[$index] = $banner_stats['name'];

    $index--;
  }

  $ochart = new chart(200, 220, 5, '#eeeeee');
  $ochart->setTitle(TEXT_BANNERS_LAST_3_DAYS, '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, '');
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_infobox-' . $banner_id . '.' . $image_extension);
?>
