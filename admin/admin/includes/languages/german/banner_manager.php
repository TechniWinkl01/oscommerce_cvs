<?php
/*
  $Id: banner_manager.php,v 1.14 2001/09/19 11:13:37 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Banner Manager');
define('HEADING_TITLE', 'Banner Manager');

define('TABLE_HEADING_BANNERS', 'Banner');
define('TABLE_HEADING_GROUPS', 'Gruppe');
define('TABLE_HEADING_STATISTICS', 'Anzeigen / Klicks');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_BANNERS_TITLE', 'Titel des Banners:'); 
define('TEXT_BANNERS_URL', 'Banner-URL:'); 
define('TEXT_BANNERS_GROUP', 'Banner-Gruppe:'); 
define('TEXT_BANNERS_NEW_GROUP', ', oder geben Sie unten eine neue Banner-Gruppe ein'); 
define('TEXT_BANNERS_IMAGE', 'Bild (Datei):'); 
define('TEXT_BANNERS_IMAGE_LOCAL', ', oder geben Sie unten die lokale Datei auf Ihrem Server an'); 
define('TEXT_BANNERS_IMAGE_TARGET', 'Bildziel (Speichern nach):'); 
define('TEXT_BANNERS_HTML_TEXT', 'HTML Text:');
define('TEXT_BANNERS_EXPIRES_ON', 'Gültigkeit bis:');
define('TEXT_BANNERS_OR_AT', ', oder bei');
define('TEXT_BANNERS_IMPRESSIONS', 'impressionen/anzeigen.');
define('TEXT_BANNERS_SCHEDULED_AT', 'Gültigkeit ab:');
define('TEXT_BANNERS_BANNER_NOTE', '<b>Banner Bemerkung:</b><ul><li>Sie können Bild- oder HTML-Text-Banner verwenden, beides gleichzeitig ist nicht möglich.</li><li>Wenn Sie beide Bannerarten gleichzeitig verwenden, wird nur der HTML-Text Banner angezeigt.</li></ul>');
define('TEXT_BANNERS_INSERT_NOTE', '<b>Bemerkung:</b><ul><li>Das Bildverzeichnis muss Schreibrecht besitzen!</li><li>F&uuml;llen Sie das Feld \'Bildziel (Speichern nach)\' nicht aus, wenn Sie kein Bild auf Ihren Server kopieren m&ouml;chten (z.B. wenn sich bereits das Bild auf dem Server befindet).</li><li>Das \'Bildziel (Speichern nach)\' Feld muss ein bereits existierendes Verzeichnis mit \'/\' am Ende sein (z.B. banners/).</li></ul>'); 
define('TEXT_BANNERS_EXPIRCY_NOTE', '<b>Gültigkeit Bemerkung:</b><ul><li>Nur ein Feld ausfüllen</li><li>Wenn der Banner unbegrenzt angezeigt werden soll, tragen Sie in diesen Feldern nichts ein</li></ul>');
define('TEXT_BANNERS_SCHEDULE_NOTE', '<b>Gültigkeit ab Bemerkung:</b><ul><li>Bei Verwendung von dieser Funktion, wird der Banner erst ab dem angegeben Datum angezeigt.</li><li>Alle Banner mit dieser Funktion werden bis ihrer Aktivierung, als Deaktiviert angezeigt.</li></ul>');

define('TEXT_BANNERS_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_BANNERS_SCHEDULED_AT_DATE', 'Gültigkeit ab: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_DATE', 'Gültigkeit bis zum: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS', 'Gültigkeit bis: <b>%s</b> impressionen/anzeigen');
define('TEXT_BANNERS_STATUS_CHANGE', 'Status geändert: %s');

define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie das Banner löschen wollen?');
?>
