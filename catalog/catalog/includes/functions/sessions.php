<?
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

  function tep_session_close() {

    return session_close();

  }

  function tep_session_destroy() {

    return session_destroy();

  }
?>