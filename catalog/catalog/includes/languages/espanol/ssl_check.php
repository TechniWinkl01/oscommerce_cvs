<?php
/*
  $Id: ssl_check.php,v 1.2 2003/04/15 17:48:49 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Comprobacion de Seguridad');
define('HEADING_TITLE', 'Comprobacion de Seguridad');

define('TEXT_INFORMATION', 'Hemos detectado que su navegador ha generado un Identificativo de Sesion Seguro diferente al que ha estado usando a lo largo de su visita.<br><br>Por razones de seguridad debera entrar de nuevo en su cuenta para verificar su identidad.<br><br>Algunos navegadores como Konqueror 3.1 no tienen la habilidad de generar un Identificativo de Sesion Seguro automaticamente. Si usa este navegador, le recomendamos cambiar a otro como <a href="http://www.microsoft.com/ie/" target="_blank">Microsoft Internet Explorer</a>, <a href="http://channels.netscape.com/ns/browsers/download_other.jsp" target="_blank">Netscape</a>, o <a href="http://www.mozilla.org/releases/" target="_blank">Mozilla</a>, para continuar con su experiencia.<br><br>Hemos adoptado esta medida de seguridad en su beneficio y le pedimos disculpas anticipadas por cualquier inconveniente que pudiera ocasionarle.<br><br>Pongase en contacto con nosotros con cualquier pregunta que tenga al respecto o para realizar sus compras offline.');

define('BOX_INFORMATION_HEADING', 'Seguridad y Privacidad');
define('BOX_INFORMATION', 'Validamos el Identificativo de Sesion Seguro generado automaticamente por su navegador en cada peticion que realiza a este servidor.<br><br>Esta validacion asegura que es usted quien navega con su cuenta y no otra persona.');
?>
