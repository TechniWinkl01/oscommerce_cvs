<?php
/*
  $Id: sessions_mysql.php,v 1.7 2001/12/28 13:57:35 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License

  session-mysql.phps (v1.00 2000/21/05) by Ying Zhang
   - Email Address: ying@zippydesign.com

  Terms of usage:
  You are free to use this library in any way you want, no warranties are
  expressed or implied.  This works for me, but I don't guarantee that it
  works for you, USE AT YOUR OWN RISK.

  While not required to do so, I would appreciate it if you would retain
  this header information.  If you make any modifications or improvements,
  please send them via email to Ying Zhang <ying@zippydesign.com>.

  Updated by dwatkins for TEP standards
*/

  if (!$SESS_LIFE = get_cfg_var("session.gc_maxlifetime")) {
    $SESS_LIFE = 1440;
  }

  function sess_open($save_path, $session_name) {
    return true;
  }

  function sess_close() {
    return true;
  }

  function sess_read($key) {
    $qry = "SELECT value FROM " . TABLE_SESSIONS . " WHERE sesskey = '$key' AND expiry > " . time();
    $qid = tep_db_query($qry);

    if ($value = tep_db_fetch_array($qid)) {
      return $value['value'];
    }

    return false;
  }

  function sess_write($key, $val) {
    global $SESS_LIFE;

    $expiry = time() + $SESS_LIFE;
    $value = addslashes($val);

    $qry = "SELECT count(*) as total FROM " . TABLE_SESSIONS . " WHERE sesskey = '$key'";
    $qid = tep_db_query($qry);
    $total = tep_db_fetch_array($qid);

    if ($total['total'] > 0) {
      $qry = "UPDATE " . TABLE_SESSIONS . " SET expiry = $expiry, value = '$value' WHERE sesskey = '$key'";
    } else {
      $qry = "INSERT INTO " . TABLE_SESSIONS . " VALUES ('$key', $expiry, '$value')";
    }
    $qid = tep_db_query($qry);

    return $qid;
  } 

  function sess_destroy($key) {
    $qry = "DELETE FROM " . TABLE_SESSIONS . " WHERE sesskey = '$key'";
    $qid = tep_db_query($qry);

    return $qid;
  }

  function sess_gc($maxlifetime) {
    $qry = "DELETE FROM " . TABLE_SESSIONS . " WHERE expiry < " . time();
    $qid = tep_db_query($qry);

    return mysql_affected_rows();
  }

  session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
?>
