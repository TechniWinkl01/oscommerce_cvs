<?php
/*
  $Id: include_once.php,v 1.2 2001/09/20 19:27:18 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  if (!defined($include_file . '__')) {
    define($include_file . '__', 1);
	include($include_file);
  }
?>