<?php
/*
  $Id: language.php,v 1.8 2004/11/20 02:11:47 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  browser language detection logic Copyright phpMyAdmin (select_lang.lib.php3 v1.24 04/19/2002)
                                   Copyright Stephane Garin <sgarin@sgarin.com> (detect_language.php v0.1 04/02/2002)
*/

  class language {
    var $languages, $catalog_languages, $browser_languages, $language;

    function language($lng = '') {
      global $osC_Database;

      $this->languages = array('ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
                               'bg' => 'bg|bulgarian',
                               'br' => 'pt[-_]br|brazilian portuguese',
                               'ca' => 'ca|catalan',
                               'cs' => 'cs|czech',
                               'da' => 'da|danish',
                               'de' => 'de([-_][[:alpha:]]{2})?|german',
                               'el' => 'el|greek',
                               'en' => 'en([-_][[:alpha:]]{2})?|english',
                               'es' => 'es([-_][[:alpha:]]{2})?|spanish',
                               'et' => 'et|estonian',
                               'fi' => 'fi|finnish',
                               'fr' => 'fr([-_][[:alpha:]]{2})?|french',
                               'gl' => 'gl|galician',
                               'he' => 'he|hebrew',
                               'hu' => 'hu|hungarian',
                               'id' => 'id|indonesian',
                               'it' => 'it|italian',
                               'ja' => 'ja|japanese',
                               'ko' => 'ko|korean',
                               'ka' => 'ka|georgian',
                               'lt' => 'lt|lithuanian',
                               'lv' => 'lv|latvian',
                               'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
                               'no' => 'no|norwegian',
                               'pl' => 'pl|polish',
                               'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
                               'ro' => 'ro|romanian',
                               'ru' => 'ru|russian',
                               'sk' => 'sk|slovak',
                               'sr' => 'sr|serbian',
                               'sv' => 'sv|swedish',
                               'th' => 'th|thai',
                               'tr' => 'tr|turkish',
                               'uk' => 'uk|ukrainian',
                               'tw' => 'zh[-_]tw|chinese traditional',
                               'zh' => 'zh|chinese simplified');

      $this->catalog_languages = array();

      $Qlanguages = $osC_Database->query('select * from :table_languages order by sort_order, name');
      $Qlanguages->bindTable(':table_languages', TABLE_LANGUAGES);
      $Qlanguages->setCache('languages');
      $Qlanguages->execute();

      while ($Qlanguages->next()) {
        $this->catalog_languages[$Qlanguages->value('code')] = array('id' => $Qlanguages->valueInt('languages_id'),
                                                                     'name' => $Qlanguages->value('name'),
                                                                     'code' => $Qlanguages->value('code'),
                                                                     'image' => $Qlanguages->value('image'),
                                                                     'directory' => $Qlanguages->value('directory'));
      }

      $Qlanguages->freeResult();

      $this->browser_languages = '';
      $this->language = '';

      $this->set_language($lng);
    }

    function set_language($language) {
      global $osC_Session;

      if (empty($language) && $osC_Session->exists('language')) {
        foreach ($this->catalog_languages as $l) {
          if ($l['directory'] == $osC_Session->value('language')) {
            $language = $l['code'];
            break;
          }
        }
      }

      if (!empty($language) && $this->exists($language)) {
        $this->language = $this->get($language);
      } else {
        $this->language = $this->get(DEFAULT_LANGUAGE);
      }
    }

    function get_browser_language() {
      $this->browser_languages = explode(',', getenv('HTTP_ACCEPT_LANGUAGE'));

      for ($i=0, $n=sizeof($this->browser_languages); $i<$n; $i++) {
        reset($this->languages);
        while (list($key, $value) = each($this->languages)) {
          if (eregi('^(' . $value . ')(;q=[0-9]\\.[0-9])?$', $this->browser_languages[$i]) && isset($this->catalog_languages[$key])) {
            $this->language = $this->catalog_languages[$key];
            break 2;
          }
        }
      }
    }

    function get($language) {
      return $this->catalog_languages[$language];
    }

    function getAll() {
      return $this->catalog_languages;
    }

    function exists($language) {
      if (isset($this->catalog_languages[$language])) {
        return true;
      }

      return false;
    }
  }
?>
