<?php
/*
  $Id: sessions.php,v 1.4 2001/09/30 16:22:15 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  function tep_session_start() {

    return session_start();

  }

  function tep_session_register($variable) {

    return session_register($variable);

  }

  function tep_session_is_registered($variable) {

    return session_is_registered($variable);

  }

  function tep_session_unregister($variable) {

    return session_unregister($variable);

  }

  function tep_session_id($sessid='') {

    if ($sessid) 
       return session_id($sessid);
    else
       return session_id();
      
  }

  function tep_session_name($name='') {

    if ($name)
      return session_name($name);
    else
      return session_name();

  }

  function tep_session_close() {

    if (function_exists('session_close')) {
      return session_close();
    }

  }

  function tep_session_destroy() {

    return session_destroy();

  }
?>