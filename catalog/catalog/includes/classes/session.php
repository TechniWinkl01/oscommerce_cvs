<?php
/*
  $Id: session.php,v 1.6 2004/11/24 15:51:38 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Session {
    var $is_started,
        $save_path,
        $name,
        $id;

/* Private variables */
    var $_cookie_parameters;

// class constructor
    function osC_Session() {
      $this->setName('osCsid');
      $this->setSavePath(DIR_FS_WORK);
      $this->setCookieParameters();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler(array(&$this, '_open'),
                                 array(&$this, '_close'),
                                 array(&$this, '_read'),
                                 array(&$this, '_write'),
                                 array(&$this, '_destroy'),
                                 array(&$this, '_gc'));
      }

      $this->setStarted(false);
    }

// class methods
    function start() {
      $sane_session_id = true;

      if (isset($_GET[$this->name])) {
        if (preg_match('/^[a-zA-Z0-9]+$/', $_GET[$this->name]) == false) {
          unset($_GET[$this->name]);

          $sane_session_id = false;
        }
      } elseif (isset($_POST[$this->name])) {
        if (preg_match('/^[a-zA-Z0-9]+$/', $_POST[$this->name]) == false) {
          unset($_POST[$this->name]);

          $sane_session_id = false;
        }
      } elseif (isset($_COOKIE[$this->name])) {
        if (preg_match('/^[a-zA-Z0-9]+$/', $_COOKIE[$this->name]) == false) {
          unset($_COOKIE[$this->name]);

          $sane_session_id = false;
        }
      }

      if ($sane_session_id == false) {
        tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
      } elseif (session_start()) {
        $this->setStarted(true);

        $this->setID();

        return true;
      }

      return false;
    }

    function exists($variable) {
      if (isset($_SESSION[$variable])) {
        return true;
      }

      return false;
    }

    function set($variable, &$value) {
      if ($this->is_started == true) {
        $_SESSION[$variable] = $value;

        return true;
      }

      return false;
    }

    function remove($variable) {
      if ($this->exists($variable)) {
        unset($_SESSION[$variable]);

        return true;
      }

      return false;
    }

    function &value($variable) {
      if (isset($_SESSION[$variable])) {
        return $_SESSION[$variable];
      }

      return false;
    }

    function close() {
      if (function_exists('session_write_close')) {
        return session_write_close();
      }

      return true;
    }

    function destroy() {
      if (isset($_COOKIE[$this->name])) {
        unset($_COOKIE[$this->name]);
      }

      if (STORE_SESSIONS == '') {
        if (file_exists($this->save_path . $this->id)) {
          @unlink($this->save_path . $this->id);
        }
      }

      return session_destroy();
    }

    function recreate() {
      $session_backup = $_SESSION;

      $this->destroy();

      $this->osC_Session();

      $this->start();

      $_SESSION = $session_backup;

      unset($session_backup);
    }

    function getSavePath() {
      return $this->save_path;
    }

    function setName($name) {
      session_name($name);

      $this->name = session_name();

      return true;
    }

    function setID() {
      $this->id = session_id();

      return true;
    }

    function setSavePath($path) {
      if (substr($path, -1) == '/') {
        $path = substr($path, 0, -1);
      }

      session_save_path($path);

      $this->save_path = session_save_path();

      return true;
    }

    function setStarted($state) {
      if ($state == true) {
        $this->is_started = true;
      } else {
        $this->is_started = false;
      }
    }

    function setCookieParameters($lifetime = 0, $path = false, $domain = false, $secure = false) {
      global $request_type;

      if ($path === false) {
        $path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);
      }

      if ($domain === false) {
        $domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
      }

      return session_set_cookie_params($lifetime, $path, $domain, $secure);
    }

    function getCookieParameters($key = '') {
      if (isset($this->_cookie_parameters) === false) {
        $this->_cookie_parameters = session_get_cookie_params();
      }

      if (in_array($key, $this->_cookie_parameters)) {
        return $this->_cookie_parameters[$key];
      }

      return $this->_cookie_parameters;
    }

    function _open() {
      return true;
    }

    function _close() {
      return true;
    }

    function _read($key) {
      global $osC_Database;

      $Qsession = $osC_Database->query('select value from :table_sessions where sesskey = :sesskey and expiry > :expiry');
      $Qsession->bindRaw(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':sesskey', $key);
      $Qsession->bindRaw(':expiry', time());
      $Qsession->execute();

      if ($Qsession->numberOfRows() > 0) {
        $value = $Qsession->value('value');

        $Qsession->freeResult();

        return $value;
      }

      return false;
    }

    function _write($key, $value) {
      global $osC_Database;

      if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
        $SESS_LIFE = 1440;
      }

      $expiry = time() + $SESS_LIFE;

      $Qsession = $osC_Database->query('select count(*) as total from :table_sessions where sesskey = :sesskey');
      $Qsession->bindRaw(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':sesskey', $key);
      $Qsession->execute();

      if ($Qsession->valueInt('total') > 0) {
        $Qsession = $osC_Database->query('update :table_sessions set expiry = :expiry, value = :value where sesskey = :sesskey');
      } else {
        $Qsession = $osC_Database->query('insert into :table_sessions values (:sesskey, :expiry, :value)');
      }
      $Qsession->bindRaw(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':sesskey', $key);
      $Qsession->bindValue(':expiry', $expiry);
      $Qsession->bindValue(':value', $value);

      if ($Qsession->execute()) {
        $write = true;
      } else {
        $write = false;
      }

      $Qsession->freeResult();

      return $write;
    }

    function _destroy($key) {
      global $osC_Database;

      $Qsession = $osC_Database->query('delete from :table_sessions where sesskey = :sesskey');
      $Qsession->bindRaw(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':sesskey', $key);
      $Qsession->execute();

      $Qsession->freeResult();
    }

    function _gc($maxlifetime) {
      global $osC_Database;

      $Qsession = $osC_Database->query('delete from :table_sessions where expiry < :expiry');
      $Qsession->bindRaw(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':expiry', time());
      $Qsession->execute();

      $Qsession->freeResult();
    }
  }
?>
