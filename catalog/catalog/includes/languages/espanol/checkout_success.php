<?php
/*
  $Id: checkout_success.php,v 1.4 2001/12/17 19:52:24 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Pedido');
define('NAVBAR_TITLE_2', 'Realizado con Exito');
define('TOP_BAR_TITLE', 'Pedido Completo!');
define('HEADING_TITLE', 'Su Pedido ha sido Procesado!');
define('TEXT_SUCCESS', 'Su pedido ha sido realizado con exito! Sus productos llegaran a su destino de 2 a 5 dias laborales.<br><br>Puede ver su historial de pedidos su pagina de <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Mi Cuenta\'</a> y haciendo click en su <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'Historial\'</a>.<br><br>Cualquier pregunta que tenga sobre el pedido puede hacerlas por Email a <a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER_EMAIL_ADDRESS . '</a>.<br><br><font size="3">Gracias por comprar con nosotros!</font>');
?>