<?php
/*
  $Id: manufacturers.php,v 1.7 2001/09/23 15:32:46 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Fabricantes');
define('HEADING_TITLE', 'Fabricantes');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_MANUFACTURERS', 'Fabricantes');
define('TABLE_HEADING_IMAGE', 'Imagen');
define('TABLE_HEADING_ACTION', 'Accion');

define('TEXT_MANUFACTURERS', 'Fabricantes:');
define('TEXT_DATE_ADDED', 'Añadido el:');
define('TEXT_LAST_MODIFIED', 'Ultima Modificacion:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_IMAGE_NONEXISTENT', 'NO EXISTE IMAGEN');

define('TEXT_NEW_INTRO', 'Introduzca los datos del nuevo fabricante');
define('TEXT_EDIT_INTRO', 'Haga los cambios necesarios');
define('TEXT_EDIT_MANUFACTURERS_ID', 'ID del Fabricante:');
define('TEXT_EDIT_MANUFACTURERS_NAME', 'Nombre del Fabricante:');
define('TEXT_EDIT_MANUFACTURERS_IMAGE', 'Imagen Fabricante:');
define('TEXT_EDIT_MANUFACTURERS_URL', 'Fabricante URL:');

define('TEXT_DELETE_INTRO', 'Seguro que desea eliminar este fabricante?');
define('TEXT_DELETE_PRODUCTS', 'Delete products from this manufacturer? (including product reviews, products on special, upcoming products)');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ADVERTENCIA:</b> Todavia hay productos que pertenecen a este fabricante!');

define('ERROR_ACTION', 'HA OCURRIDO UN ERROR! ULTIMA ACCION : ' . $HTTP_GET_VARS['error']);
?>