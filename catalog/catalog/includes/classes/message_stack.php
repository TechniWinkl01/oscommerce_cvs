<?php
/*
  $Id: message_stack.php,v 1.3 2003/12/04 14:12:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class messageStack {

// class constructor
    function messageStack() {
      global $osC_Session;

      $this->messages = array();

      if ($osC_Session->exists('messageToStack')) {
        $messageToStack = $osC_Session->value('messageToStack');

        for ($i=0, $n=sizeof($messageToStack); $i<$n; $i++) {
          $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }

        $osC_Session->remove('messageToStack');
      }
    }

// class methods
    function add($class, $message, $type = 'error') {
      $this->messages[] = array('class' => $class, 'type' => $type, 'message' => $message);
    }

    function add_session($class, $message, $type = 'error') {
      global $osC_Session;

      if ($osC_Session->exists('messageToStack')) {
        $messageToStack = $osC_Session->value('messageToStack');
      } else {
        $messageToStack = array();
      }

      $messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);

      $osC_Session->set('messageToStack', $messageToStack);
    }

    function reset() {
      $this->messages = array();
    }

    function output($class) {
      $messages = '<ul>';
      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          switch ($this->messages[$i]['type']) {
            case 'error':
              $bullet_image = DIR_WS_ICONS . 'error.gif';
              break;
            case 'warning':
              $bullet_image = DIR_WS_ICONS . 'warning.gif';
              break;
            case 'success':
              $bullet_image = DIR_WS_ICONS . 'success.gif';
              break;
            default:
              $bullet_image = DIR_WS_ICONS . 'bullet_default.gif';
          }

          $messages .= '<li style="list-style-image: url(\'' . $bullet_image . '\')">' . tep_output_string($this->messages[$i]['message']) . '</li>';
        }
      }
      $messages .= '</ul>';

      return '<div class="messageStack">' . $messages . '</div>';
    }

    function size($class) {
      $class_size = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $class_size++;
        }
      }

      return $class_size;
    }
  }
?>
