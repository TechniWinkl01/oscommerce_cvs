<?php
/*
  $Id: sessions.php,v 1.8 2001/06/08 22:36:31 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $qry = "select value from " . TABLE_SESSIONS . " where sesskey = '" . $key . "' and expiry > '" . time() . "'";
      $qid = tep_db_query($qry);

      if (list($value) = tep_db_fetch_array($qid)) {
        return $value;
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = addslashes($val);

      $qry = "select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . $key . "'";
      $qid = tep_db_query($qry);
      list($total) = tep_db_fetch_array($qid);

      if ($total > 0) {
        $qry = "update " . TABLE_SESSIONS . " set expiry = '" . $expiry . "', value = '" . $value . "' where sesskey = '" . $key . "'";
      } else {
        $qry = "insert into " . TABLE_SESSIONS . " values ('" . $key . "', '" . $expiry . "', '" . $value . "')";
      }
      $qid = tep_db_query($qry);

      return $qid;
    }

    function _sess_destroy($key) {
      $qry = "delete from " . TABLE_SESSIONS . " where sesskey = '" . $key . "'";
      $qid = tep_db_query($qry);

      return $qid;
    }

    function _sess_gc($maxlifetime) {
      $qry = "delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'";
      $qid = tep_db_query($qry);

      return mysql_affected_rows();
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

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

  function tep_session_id($sessid = '') {
    if ($sessid != '') {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if ($name != '') {
      return session_name($name);
    } else {
      return session_name();
    }
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