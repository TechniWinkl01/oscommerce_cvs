<?php
/*
  $Id: cache.php,v 1.3 2001/06/05 15:04:02 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
/*
  phpCache v1.4 By nathan@0x00.org http://0x00.org/phpCache

  Modified into a class and for TEP standards by Harald Ponce de Leon
*/

  define('CACHE_VERSION', "1.4");
  define('CACHE_STORAGE_CHECKFILE', CACHE_DIR . "/.phpCache-storage-V" . CACHE_VERSION . "-HASH=" . CACHE_USE_STORAGE_HASH);
  define('CACHE_INFO', "phpCache v1.4 By nathan@0x00.org");	

  $CACHE_HAS = array('ob_start' => function_exists('ob_start'),
                     'realpath' => function_exists('realpath'),
                     'crc32' => function_exists('crc32'));

  class phpCache {

// class constructor
    function phpCache() {
      if ( (!CACHE_STORAGE_CREATED) && (!stat(CACHE_STORAGE_CHECKFILE)) ) {
        $this->cache_debug("Creating cache storage");
        $this->cache_create_storage();
        touch(CACHE_STORAGE_CHECKFILE);
      }

      mt_srand(time(NULL));
      $this->cache_reset();
    }

// class methods
////
// Reset cache state
    function cache_reset() {
      global $cache_pbufferlen, $cache_absfile, $cache_data, $cache_variables, $cache_headers, $cache_expire_cond, $cache_output_buffer;

      $cache_pbufferlen = false;
      $cache_absfile = null;
      $cache_data = array();
      $cache_fp = null;
      $cache_expire_cond = null;
      $cache_variables = array();
      $cache_headers = array();
      $cache_output_buffer = '';
	}

////
// Output the cache
// Should only be needed if you dont have output buffering (PHP3)
    function cache_output($str) {
      global $cache_output_buffer;

      if (!$GLOBALS['CACHE_HAS']['ob_start']) {
        $cache_output_buffer .= $str;
      }

      echo $str;
    }

////
// Saves header state between caching
    function cache_header($header) {
      global $cache_headers;

      header($header);
      $this->cache_debug('Adding header ' . $header);
      $cache_headers[] = $header;
    }

////
// Evaluate conditional expirations
// Allows the eval() to have its own simulated namespace so it doesnt conflict with any others.
    function cache_eval_expire($cond, &$vars) {
      extract($vars);
      $EXPIRE = false;
      eval($cond);

      return !!$EXPIRE;
    }

////
// Call this function before a call to cache() to evaluate a dynamic expiration on cache_expire_variable()'s
    function cache_expire_if($expr) {
      global $cache_expire_cond;

      $cache_expire_cond = $expr;
    }

////
// Call this function to add a variable to the expire variables store
    function cache_expire_variable($vn) {
      $this->cache_debug('Adding ' . $vn . ' to expire variable store');
      $this->cache_variable($vn);
    }

////
// Saves a variable state between caching
    function cache_variable($vn) {
      global $cache_variables;

      $this->cache_debug('Adding ' . $vn . ' to the variable store');
      $cache_variables[] = $vn;
    }

////
// Cache debug
    function cache_debug($s) {
      global $CACHE_DEBUG;

      if ($CACHE_DEBUG) {
        echo 'Debug: ' . $s . '<br>' . "\n";
      }
    }

////
// Returns the default key used by the helper functions
    function cache_default_key() {
      global $HTTP_POST_VARS, $HTTP_GET_VARS, $QUERY_STRING;

      return md5('POST=' . serialize($HTTP_POST_VARS) . ' GET=' . serialize($HTTP_GET_VARS) . 'QS=' . $QUERY_STRING);
    }

////
// Returns the default object used by the helper functions
    function cache_default_object() {
      global $REQUEST_URI, $SERVER_NAME, $SCRIPT_FILENAME;

      if ($GLOBALS['CACHE_HAS']['realpath']) {
        $sfn = realpath($SCRIPT_FILENAME);
      } else {
        $sfn = $SCRIPT_FILENAME;
      }
      $name = 'http://' . $SERVER_NAME . '/' . $sfn;

      return $name;
    }

////
// Caches the current page based on the page name and the GET/POST variables.
// All must match or else it will not be fectched from the cache!
    function cache_all($cachetime = 120) {
      $key = $this->cache_default_key();
      $object = $this->cache_default_object();
      return $this->cache($cachetime, $object, $key);
    }

////
// Same as cache_all() but it throws the session_id() into the equation
    function cache_session($cachetime = 120) {
      global $HTTP_POST_VARS, $HTTP_GET_VARS;

      $key = $this->cache_default_key() . 'SESSIONID=' . session_id();
      $object = $this->cache_default_object();
      return $this->cache($cachetime, $object, $key);
    }

////
// Manually purge an item in the cache
    function cache_purge($object, $key) {
      $thefile = $this->cache_storage($object, $key);
      $this->cache_lock($thefile, true);
      $ret = @unlink($thefile);
      $this->cache_lock($thefile, false);

      return $ret;
    }

////
// Manually purge all items in the cache
    function cache_purge_all() {
      return $this->cache_gc(null, 1, true);
    }

////
// Caches $object based on $key for $cachetime, will return 0 if the object has expired or the object does not exist.
    function cache($cachetime, $object, $key=NULL) {
      global $cache_pbufferlen, $cache_absfile, $cache_file, $cache_data, $cache_expire_cond;

      if (!CACHE_ON) {
        $this->cache_debug('Not caching, CACHE_ON is off');
        return 0;
      }

      $curtime = time();
      $this->cache_debug('Caching based on <b>OBJECT</b>=' . $object . ' <b>KEY</b>=' . $key);
      $cache_absfile = $this->cache_storage($object, $key);
      if ($buff = $this->cache_read($cache_absfile)) {
        $this->cache_debug('Opened the cache file');
        $cdata = unserialize($buff);
        if (is_array($cdata)) {
          $curco = $cdata['cache_object'];
          if ($curco != $cache_absfile) {
            $this->cache_debug('This is not my cache file! why? got=' . $curco . ' wanted=' . $cache_absfile);
          } else {
            $expireit = false;
            if ($cache_expire_cond) {
              $expireit = $this->cache_eval_expire($cache_expire_cond, &$cdata['variables']);
            }
            if ($cdata['cachetime'] != $cachetime) {
              $this->cache_debug('Expiring because cachetime changed');
              $expireit = true;
            }
            if ( (!$expireit) && (($cdata['cachetime'] == '0') || ($cdata['expire'] >= $curtime)) ) {
              $expirein = $cdata['expire'] - $curtime + 1;
              $this->cache_debug('Cache expires in ' . $expirein);
              if (is_array($cdata['variables'])) {
                while (list($k, $v) = each($cdata['variables'])) {
                  $this->cache_debug('Restoring variable ' . $k . ' to value ' .$v);
                  $GLOBALS[$k] = $v;
                }
              }
              if (is_array($cdata['headers'])) {
                while(list(, $h) = each($cdata['headers'])) {
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
        $cache_absfile = null;
      } else {
        @touch($cache_absfile);
//        $this->cache_lock($cache_absfile, true);
      }
      umask($oldum);
      $cache_data['expire'] = $curtime + $cachetime;
      $cache_data['cachetime'] = $cachetime;
      $cache_data['curtime'] = $curtime;
      $cache_data['version'] = CACHE_VERSION;
      $cache_data['key'] = $key;
      $cache_data['object'] = $object;
      if ($GLOBALS['CACHE_HAS']['ob_start']) {
        $cache_pbufferlen = ob_get_length();
// If ob_get_length() returns false, output buffering was not on.  turn it on.
        if ($this->cache_iftype($cache_pbufferlen, false)) ob_start();
      } else {
        $cache_pbufferlen = false;
      }

      return 0;
    }

////
// This *MUST* at the end of a cache() block or else the cache will not be stored!
    function endcache($store = true) {
      global $cache_pbufferlen, $cache_absfile, $cache_data, $cache_variables, $cache_headers, $cache_ob_handler, $cache_output_buffer;

      if (!CACHE_ON) {
        $this->cache_debug('Not caching, CACHE_ON is off');

        return 0;
      }

      if ($GLOBALS['CACHE_HAS']['ob_start']) {
        $content = ob_get_contents();
        if ($this->cache_iftype($cache_pbufferlen, false)) {
// Output buffering was off before this, we just need to turn it off again
          ob_end_flush();
        } else {
// Output buffering was already on, so get our chunk of data for caching
          $content = substr($content, $cache_pbufferlen);
        }
      } else {
        $content = $cache_output_buffer;
      }

      if (!$store) {
        $cache_absfile = null;
      }

      if ($cache_absfile != null) {
        $cache_data['content'] = $content;
        $variables = array();
        while(list(, $vn) = each($cache_variables)) {
          if (isset($GLOBALS[$vn])) {
            $val = $GLOBALS[$vn];
            $this->cache_debug('Setting variable ' . $vn . ' to ' . $val);
            $variables[$vn] = $val;
          }
        }
        $cache_data['cache_object'] = $cache_absfile;
        $cache_data['variables'] = $variables;
        $cache_data['headers'] = $cache_headers;
        $datas = serialize($cache_data);
        $this->cache_write($cache_absfile, $datas);
      }
//      $this->cache_lock($cache_absfile, false);
      $this->cache_reset();
    }

////
// Obtain a lock on the cache storage, this can be stripped out and changed to a different handler like a database or whatever
    function cache_lock($file, $open = true) {
      static $fp;

      if ($open) {
        $fp = fopen($file, 'r');
        $ret = flock($fp, 2);
      } else {
        flock($fp, 3);
        fclose($fp);
        $fp = null;
      }

      return $ret;
    }

////
// This is the function that writes out the cache
    function cache_write($file, $data) {
      $fp = @fopen($file, 'w');
      if (!$fp) {
        $this->cache_debug('Failed to open for write out to ' . $file);

        return false;
      }
      fwrite($fp, $data, strlen($data));
      fclose($fp);

      return true;
    }

////
// This function reads in the cache
    function cache_read($file) {
      $fp = @fopen($file, 'r');
      if (!$fp) return null;
      flock($fp, 1);
      $buff = '';
      while (($tmp = fread($fp, 4096))) {
        $buff .= $tmp;
      }
      fclose($fp);

      return $buff;
    }

////
// This function is called automatically by phpCache to create the cache directory structure
    function cache_create_storage() {
      $failed = 0;
      $failed |=! @mkdir(CACHE_DIR, CACHE_STORAGE_PERM);
      if (CACHE_USE_STORAGE_HASH) {
        for ($a=0; $a<CACHE_MAX_STORAGE_HASH; $a++) {
          $thedir = CACHE_DIR . "/$a/";
          $failed |=! @mkdir($thedir, CACHE_STORAGE_PERM);
          for ($b=0; $b<CACHE_MAX_STORAGE_HASH; $b++) {
            $thedir = CACHE_DIR . "/$a/$b/";
            $failed |=! @mkdir($thedir, CACHE_STORAGE_PERM);
            for ($c=0; $c<CACHE_MAX_STORAGE_HASH; $c++) {
              $thedir = CACHE_DIR . "/$a/$b/$c/";
              $failed |=! @mkdir($thedir, CACHE_STORAGE_PERM);
            }
          }
        }
      }

      return true;
    }

////
// This function hashes the cache object and places it in a cache dir.  This function also handles the GC probability (note that it is run on only *ONE* dir to save time.
    function cache_storage($object, $key) {
      $newobject = eregi_replace('[^A-Z,0-9,=]', 'X', $object);
      $newkey = eregi_replace('[^A-Z,0-9,=]', 'X', $key);
      $temp = "${newobject}=${newkey}";
      if (strlen($temp) >= CACHE_MAX_FILENAME_LEN) $temp = 'HUGE.' . md5($temp);
      $cacheobject = 'phpCache.' . $temp;
      $thedir = CACHE_DIR . '/';
      if (CACHE_USE_STORAGE_HASH) {
        $chunksize = 10;
        $ustr = md5($cacheobject);
        for ($i=0; $i<3; $i++) {
          if ($GLOBALS['CACHE_HAS']['crc32']) {
            $thenum = abs(crc32(substr($ustr,$i,4)))%CACHE_MAX_STORAGE_HASH;
          } else {
            $thenum = substr($ustr, $i, 4);
            $thenum = (ord($thenum[0]) . ord($thenum[1]) . ord($thenum[2]) . ord($thenum[3]))%CACHE_MAX_STORAGE_HASH;
          }
          $thedir .= "$thenum/";
        }
      }

      if (CACHE_GC > 0) {
        $precision = 100000;
        $r = (mt_rand()%$precision)/$precision;
        if ($r <= (CACHE_GC/100)) {
          $this->cache_gc($thedir);
        }
      }
      $theloc = $thedir . $cacheobject;

      return $theloc;
    }

////
// Cache garbage collection
    function cache_gc($dir = null, $start = 1, $purgeall = false) {
      static $dirs = 0, $files = 0, $deleted = 0, $ignored = 0, $faileddelete = 0, $empty = 0;

      if ($start==1) {
        $this->cache_debug('Running GC on ' . $dir);
        if (!function_exists('getcwd')) {
          $cwd = substr(`pwd`, 0, -1);
        } else {
          $cwd = getcwd();
        }
        $dirs = $files = $deleted = $ignored = $faileddelete = $empty = 0;
      }

      if ($this->cache_iftype($dir, null)) $dir = CACHE_DIR;
      $dp = opendir($dir);
      if (!$dp) {
        $this->cache_debug('Error opening ' . $dir . ' for cleanup');
        return false;
      }
      chdir($dir);
      $dirs++;
      while (!$this->cache_iftype(($de=readdir($dp)), false)) {
        if (is_dir($de)) {
          if ($de == '.' || $de == '..') continue;
          $this->cache_gc($de, 0, $purgeall);
          chdir('..');
          continue;
        }

        if (eregi('^phpCache.', $de)) {
          $files++;
          $absfile = $de;
          $cachestuff = $this->cache_read($absfile);
          $thecache = unserialize($cachestuff);
          if (is_array($thecache)) {
            if ( ($purgeall) || (($cdata['cachetime'] != '0') && ($thecache['expire'] <= time())) ) {
              $this->cache_lock($absfile, true);
              if (@unlink($absfile)) {
                $deleted++;
                $this->cache_debug($dir . ' deleted ' . $absfile);
              } else {
                $faileddelete++;
                $this->cache_debug($dir . ' failed to delete ' . $absfile);
              }
              $this->cache_lock($absfile, false);
            } else {
              $this->cache_debug($dir . ' ' . $absfile . ' expires in ' . ($thecache['expire']-time()));
            }
          } else {
            $this->cache_debug($dir . ' ' . $absfile . ' is empty, being processed in another process?');
            $empty++;
          }
        } else {
          $ignored++;
        }
      }
      closedir($dp);
      if ($start == 1) {
        $str = "$dir GC Processed: $dirs/dirs	$files/files	$deleted/deleted	$ignored/ignored	$faileddelete/faileddelete	$empty/empty";
        $this->cache_debug($str);
        chdir($cwd);

        return $str;
      }
    }

    function cache_iftype($a, $b) {
      if ( (gettype($a) == gettype($b)) && ($a == $b) ) return true;

      return false;
    }
  }
?>
