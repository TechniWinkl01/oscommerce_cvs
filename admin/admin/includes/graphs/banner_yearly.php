<?php
/*
  $Id: banner_yearly.php,v 1.5 2004/10/30 22:49:52 hpdl Exp $

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

  $Qstats = $osC_Database->query('select year(banners_history_date) as year, sum(banners_shown) as value, sum(banners_clicked) as dvalue from :table_banners_history where banners_id = :banners_id group by year');
  $Qstats->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
  $Qstats->bindInt(':banners_id', $_GET['bID']);
  $Qstats->execute();

  while ($Qstats->next()) {
    $stats[] = array($Qstats->valueInt('year'), (($Qstats->valueInt('value') > 0) ? $Qstats->valueInt('value') : '0'), (($Qstats->valueInt('dvalue') > 0) ? $Qstats->valueInt('dvalue') : '0'));

    $views[] = $Qstats->valueInt('value');
    $clicks[] = $Qstats->valueInt('dvalue');
    $vLabels[] = $Qstats->valueInt('year');
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
