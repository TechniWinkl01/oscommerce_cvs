<?
/* ------------------------------------------------------------------------
 * session_mysql.php
 * ------------------------------------------------------------------------
 * PHP4 MySQL Session Handler
 * Version 1.00
 * by Ying Zhang (ying@zippydesign.com)
 * Last Modified: May 21 2000
 *
 * ------------------------------------------------------------------------
 * TERMS OF USAGE:
 * ------------------------------------------------------------------------
 * You are free to use this library in any way you want, no warranties are
 * expressed or implied.  This works for me, but I don't guarantee that it
 * works for you, USE AT YOUR OWN RISK.
 *
 * While not required to do so, I would appreciate it if you would retain
 * this header information.  If you make any modifications or improvements,
 * please send them via email to Ying Zhang <ying@zippydesign.com>.
 *
 * ------------------------------------------------------------------------
 * DESCRIPTION:
 * ------------------------------------------------------------------------
 * This library tells the PHP4 session handler to write to a MySQL database
 * instead of creating individual files for each session.
 *
 * Create a new database in MySQL called "sessions" like so:
 *
 * CREATE TABLE sessions (
 *      sesskey char(32) not null,
 *      expiry int(11) unsigned not null,
 *      value text not null,
 *      PRIMARY KEY (sesskey)
 * );
 *
 * ------------------------------------------------------------------------
 * INSTALLATION:
 * ------------------------------------------------------------------------
 * Make sure you have MySQL support compiled into PHP4.  Then copy this
 * script to a directory that is accessible by the rest of your PHP
 * scripts.
 *
 * ------------------------------------------------------------------------
 * USAGE:
 * ------------------------------------------------------------------------
 * Include this file in your scripts before you call session_start(), you
 * don't have to do anything special after that.
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

    if (list($value) = tep_db_fetch_array($qid)) {
      return $value;
    }

    return false;
  }

  function sess_write($key, $val) {
    global $SESS_LIFE;

    $expiry = time() + $SESS_LIFE;
    $value = addslashes($val);

    $qry = "SELECT count(*) as total FROM " . TABLE_SESSIONS . " WHERE sesskey = '$key'";
    $qid = tep_db_query($qry);
    list($total) = tep_db_fetch_array($qid);

    if ($total > 0) {
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
