<?php
/*
  $Id: banner_manager.php,v 1.12 2001/12/24 01:59:45 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Administrador de Banners');

define('TABLE_HEADING_BANNERS', 'Banners');
define('TABLE_HEADING_GROUPS', 'Grupos');
define('TABLE_HEADING_STATISTICS', 'Vistas / Clicks');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_ACTION', 'Accion');

define('TEXT_BANNERS_TITLE', 'Titulo:');
define('TEXT_BANNERS_URL', 'URL:');
define('TEXT_BANNERS_GROUP', 'Grupo:');
define('TEXT_BANNERS_NEW_GROUP', ', o introduzca un grupo nuevo');
define('TEXT_BANNERS_IMAGE', 'Imagen:');
define('TEXT_BANNERS_IMAGE_LOCAL', ', o introduzca un fichero local');
define('TEXT_BANNERS_IMAGE_TARGET', 'Destino de la Imagen (Grabar en):');
define('TEXT_BANNERS_HTML_TEXT', 'Texto HTML:');
define('TEXT_BANNERS_EXPIRES_ON', 'Caduca el:');
define('TEXT_BANNERS_OR_AT', ', o tras');
define('TEXT_BANNERS_IMPRESSIONS', 'vistas.');
define('TEXT_BANNERS_SCHEDULED_AT', 'Programado el:');
define('TEXT_BANNERS_BANNER_NOTE', '<b>Notas sobre el Banner:</b><ul><li>Use una imagen o texto HTML para el banner - no ambos.</li><li>Texto HTML tiene prioridad sobre una imagen</li></ul>');
define('TEXT_BANNERS_INSERT_NOTE', '<b>Notas sobre la Imagen:</b><ul><li>El directorio donde suba la imagen debe de tener confiurado los permisos de escritura necesarios!</li><li>No rellene el campo \'Grabar en\' si no va a subir una imagen al servidor (como cuando use una imagen ya existente en el servidor -fichero local).</li><li>El campo \'Grabar en\' debe de ser un directorio que exista y terminado en una barra (por ejemplo: banners/).</li></ul>');
define('TEXT_BANNERS_EXPIRCY_NOTE', '<b>Notas sobre la Caducidad:</b><ul><li>Solo se debe de rellenar uno de los dos campos</li><li>Si el banner no debe de caducar no rellene ninguno de los campos</li></ul>');
define('TEXT_BANNERS_SCHEDULE_NOTE', '<b>Notas sobre la Programacion:</b><ul><li>Si se configura una fecha de programacion el banner se activara en esa fecha.</li><li>Todos los banners programados se marcan como inactivos hasta que llegue su fecha, cuando se marcan activos.</li></ul>');

define('TEXT_BANNERS_DATE_ADDED', 'Añadido el:');
define('TEXT_BANNERS_SCHEDULED_AT_DATE', 'Programado el: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_DATE', 'Caduca el: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS', 'Caduca tras: <b>%s</b> vistas');
define('TEXT_BANNERS_STATUS_CHANGE', 'Cambio Estado: %s');

define('TEXT_BANNERS_DATA', 'D<br>A<br>T<br>A');
define('TEXT_BANNERS_LAST_3_DAYS', 'Last 3 Days');
define('TEXT_BANNERS_BANNER_VIEWS', 'Banner Views');
define('TEXT_BANNERS_BANNER_CLICKS', 'Banner Clicks');

define('TEXT_INFO_DELETE_INTRO', 'Seguro que quiere eliminar este banner?');
?>