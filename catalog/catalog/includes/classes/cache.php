<?php
/*
  $Id: cache.php,v 1.2 2001/04/01 00:51:21 hpdl Exp $

  phpCache v1.2 By nathan@0x00.org

  Modified into a class and for TEP standards by Harald Ponce de Leon

  phpCache WebSite: http://0x00.org/phpCache
*/

  class phpCache {

// class constructor
    function phpCache() {
      $this->cache_reset();

      if (CACHE_GC > 0) {
        mt_srand(time(NULL));
        $precision = 100000;
        $r = (mt_rand()%$precision)/$precision;
        if ($r <= (CACHE_GC/100)) {
          $this->cache_gc();
        }
      }
    }

// class methods
/* This resets the cache state */
    function cache_reset() {
      global $cache_pbuffer, $cache_absfile, $cache_data, $cache_variables, $cache_headers, $cache_expire_cond, $cache_expire_variables;

      $cache_pbuffer = '';
      $cache_absfile = NULL;
      $cache_data = array();
      $cache_fp = NULL;
      $cache_expire_cond = NULL;
      $cache_variables = array();
      $cache_expire_variables = array();
      $cache_headers = array();
    }

/* Saves a header state between caching */
    function cache_header($header) {
      global $cache_headers;

      header($header);
      $this->cache_debug('Adding header ' . $header);
      $cache_headers[] = $header;
    }

/* This is a function used internally by phpCache to evaluate the conditional expiration.  This allows the eval() to have its own simulated namespace so it doesnt conflict with any others. */
    function cache_eval_expire($cond, $vars) {
      extract($vars);
      $EXPIRE = FALSE;
      eval($cond);

      return (boolean)$EXPIRE;
    }

/* Call this function before a call to cache() to evaluate a dynamic expiration on cache_expire_variable()'s */
    function cache_expire_if($expr) {
      global $cache_expire_cond;

      $cache_expire_cond = $expr;
    }

/* Call this function to add a variable to the expire variables store */
    function cache_expire_variable($vn) {
      global $cache_expire_variables;

      $this->cache_debug('Adding ' . $vn . ' to cache variable store');
      $cache_expire_variables[] = $vn;
    }

/* Saves a variable state between caching */
    function cache_variable($vn) {
      global $cache_variables;

      $this->cache_debug('Adding ' . $vn . ' to the variable store');
      $cache_variables[] = $vn;
    }

/* duh ? */
    function cache_debug($s) {
      if (CACHE_DEBUG == true) {
        echo 'Debug: ' . $s . '<br>' . "\n";
      }
    }

/* Returns the default key used by the helper functions */
    function cache_default_key() {
      global $HTTP_POST_VARS, $HTTP_GET_VARS, $QUERY_STRING;

      return 'KEY=' . md5('POST=' . serialize($HTTP_POST_VARS) . ' GET=' . serialize($HTTP_GET_VARS) . 'QS=' . $QUERY_STRING);
    }

/* Returns the default object used by the helper functions */
    function cache_default_object() {
      global $SCRIPT_URI, $REQUEST_URI, $SERVER_NAME, $SCRIPT_FILENAME;

      $name = $SCRIPT_URI;
      if ($name == '') {
        $name = 'http://' . $SERVER_NAME . '/' . $SCRIPT_FILENAME;
      }

      return $name;
    }

/* Caches the current page based on the page name and the GET/POST variables.  All must match or else it will not be fectched from the cache! */
    function cache_all($cachetime = 120) {
      $key = $this->cache_default_key();
      $object = $this->cache_default_object();

      return $this->cache($cachetime, $object, $key);
    }

/* Same as cache_all() but it throws the session_id() into the equation */
    function cache_session($cachetime = 120) {
      global $HTTP_POST_VARS, $HTTP_GET_VARS;

      $key = $this->cache_default_key() . 'SESSIONID=' . session_id();
      $object = $this->cache_default_object();

      return $this->cache($cachetime, $object, $key);
    }

/* Caches $object based on $key for $cachetime, will return 0 if the object has expired or the object does not exist. */
    function cache($cachetime, $object, $key = NULL) {
      global $cache_pbuffer, $cache_absfile, $cache_file, $cache_data, $cache_expire_cond;

      if (CACHE_ON != true) {
        $this->cache_debug('Not caching, CACHE_ON is off');
        return 0;
      }

      $curtime = time();
      $cache_file = $object;
      $cache_file = eregi_replace('[^A-Z,0-9,=]', 'X', $cache_file);
      $key = eregi_replace('[^A-Z,0-9,=]', 'X', $key);
      $this->cache_debug('Caching based on <b>OBJECT</b>=' . $cache_file . ' <b>KEY</b>=' . $key);
      $cache_file = 'phpCache.' . $cache_file . '=' . $key;
      $cache_absfile = $this->cache_storage($cache_file);
      if ( ($buff = $this->cache_read($cache_absfile)) ) {
        $this->cache_debug('Opened the cache file');
        $cdata = unserialize($buff);
        if (is_array($cdata)) {
          $curco = $cdata['cache_object'];
          if ($curco != $cache_absfile) {
            $this->cache_debug('Holy shit that is not my cache file! why? got=' . $curco . ' wanted=' . $cache_absfile);
          } else {
            $expireit = FALSE;
            if (!($cache_expire_cond === NULL)) {
              $expireit = $this->cache_eval_expire($cache_expire_cond, $cdata['expire_variables']);
            }
            if ( (!$expireit) && ( ($cdata['cachetime'] == '0') || ($cdata['expire'] >= $curtime) ) ) {
              $expirein = $cdata['expire']-$curtime+1;
              $this->cache_debug('Cache expires in ' . $expirein);
              if (is_array($cdata['variables'])) {
                foreach ($cdata['variables'] as $k => $v) {
                  $this->cache_debug('Restoring variable ' . $k . ' to value ' . $v);
                  $GLOBALS[$k] = $v;
                }
              }
              if (is_array($cdata['headers'])) {
                foreach ($cdata['headers'] as $h) {
                  $this->cache_debug('Restoring header ' . $h);
                  header($h);
                }
              }
              echo $cdata['content'];
              $ret = $expirein;
              if ($cdata['cachetime'] == '0') $ret = 'INFINITE';
              $this->cache_reset();
              return $ret;
            }
          }
        }
      } else {
        $this->cache_debug('Failed to open previous cache of ' . $cache_absfile);
      }
      $oldum = umask();
      umask(0077);
      if (@readlink($cache_absfile)) {
        $this->cache_debug($cache_absfile . ' is a symlink! not caching!');
        $cache_absfile = NULL;
      } else {
        @touch($cache_absfile);
        $this->cache_lock($cache_absfile, TRUE);
      }
      umask($oldum);
      $cache_data['expire'] = $curtime + $cachetime;
      $cache_data['cachetime'] = $cachetime;
      $cache_pbuffer = ob_get_contents();
      ob_end_clean();
      ob_start();

      return 0;
    }

/* This *MUST* at the end of a cache() block or else the cache will not be stored! */ 
    function endcache($store = TRUE) {
      global $cache_pbuffer, $cache_absfile, $cache_data, $cache_variables, $cache_headers, $cache_expire_variables;

      if (CACHE_ON != true) {
        $this->cache_debug('Not caching, CACHE_ON is off');
        return 0;
      }

      $content = ob_get_contents();
      ob_end_clean();
      if (!($cache_pbuffer === FALSE)) {
        ob_start();
      }
      if (!$store) {
        if ($cache_absfile != NULL) $this->cache_lock($cache_absfile, FALSE);
        $cache_absfile = NULL;
      }
      if ($cache_absfile != NULL) {
        $cache_data['content'] = $content;
        $variables = array();
        $expire_variables = array();
        foreach ($cache_variables as $vn) {
          if (isset($GLOBALS[$vn])) {
            $val = $GLOBALS[$vn];
            $this->cache_debug('Setting variable ' . $vn . ' to ' . $val);
            $variables[$vn] = $val;
          }
        }
        foreach ($cache_expire_variables as $vn) {
          if (isset($GLOBALS[$vn])) {
            $val = $GLOBALS[$vn];
            $this->cache_debug('Setting expire variable ' . $vn . ' to ' . $val);
            $expire_variables[$vn] = $val;
          }
        }
        $cache_data['cache_object'] = $cache_absfile;
        $cache_data['variables'] = $variables;
        $cache_data['expire_variables'] = $expire_variables;
        $cache_data['headers'] = $cache_headers;
        $datas = serialize($cache_data);
        $this->cache_write($cache_absfile, $datas);
        $this->cache_lock($cache_absfile, FALSE);
      }
      echo $cache_pbuffer;
      echo $content;
      $this->cache_reset();
    }

/* Obtain a lock on the cache storage, this can be stripped out and changed to a different handler like a database or whatever */
    function cache_lock($file, $open = TRUE) {
      static $fp;

      if ($open) {
        $fp = fopen($file, 'r');
        $ret = flock($fp, LOCK_EX);
      } else {
        flock($fp, LOCK_UN);
        fclose($fp);
        $fp = NULL;
      }

      return $ret;
    }

/* This is the function that writes out the cache */
    function cache_write($file, $data) {
      $fp = @fopen($file, 'w');
      if (!$fp) {
        $this->cache_debug('Failed to open for write out to ' . $file);
        return FALSE;
      }
      fwrite($fp, $data, strlen($data));
      fclose($fp);

      return TRUE;
    }

/* This function reads in the cache, duh */
    function cache_read($file) {
      $fp = @fopen($file, 'r');
      if (!$fp) return NULL;
      flock($fp, LOCK_SH);
      $buff = '';
      while (($tmp = fread($fp, 4096))) {
        $buff .= $tmp;
      }
      fclose($fp);

      return $buff;
    }

    function cache_storage($cacheobject) {
      return CACHE_DIR . '/' . $cacheobject;
    }

/* Cache garbage collection */
    function cache_gc() {
      $cache_dir = CACHE_DIR;
      $this->cache_debug('Running gc');
      $dp = opendir(CACHE_DIR);
      if (!$dp) {
        $this->cache_debug('Error opening ' . $cache_dir . ' for cleanup');
        return FALSE;
      }
      while (!(($de = readdir($dp)) === FALSE)) {
        if (eregi('^phpCache.', $de)) {
          $absfile = $cache_dir . '/' . $de;
          $cachestuff = $this->cache_read($absfile);
          $thecache = unserialize($cachestuff);
          if (is_array($thecache)) {
            if ($thecache['expire']<=time()) {
              if (@unlink($absfile)) {
                $this->cache_debug('Deleted ' . $absfile);
              } else {
                $this->cache_debug('Failed to delete ' . $absfile);
              }
            } else {
              $this->cache_debug($absfile . ' expires in ' . ($thecache['expire']-time()));
            }
          } else {
            $this->cache_debug($absfile . ' is empty, being processed in another process?');
          }
        }
      }
    }

  }
?>
