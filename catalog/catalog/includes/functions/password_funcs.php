<?php
/*
  $Id: password_funcs.php,v 1.8 2002/11/19 00:53:42 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

////
// This funstion validates a candidate password.
//  $plain_pass is the plaintext password entered by the user.
//  $db_pass is the contents of the customer_password field
//  in the customer table. $db_pass has this structure:
//  hash:salt Hash is an MD5 hash of the password + salt
//  and salt is a two character 'salt'.
  function validate_password($plain_pass, $db_pass){
     
    /* Quick test to let this work on unencrypted passwords and NULL Passwords */
    if ($plain_pass == $db_pass) {
      return (true);
    }
     
    /* split apart the hash / salt */
    if(!($subbits = split(":", $db_pass, 2))){
      return (false);
    }
    
    $dbpassword = $subbits[0];
    $salt = $subbits[1];
    
    $passtring = $salt . $plain_pass;
    
    $encrypted = md5($passtring);
    if (strcmp($dbpassword, $encrypted) == 0) {
      return (true);
    } else {
      return (false);
    }
  }

////
// This function makes a new password from a plaintext password. 
// An encrypted password + salt is returned
  function crypt_password($plain_pass) {
    for ($i = 0; $i < 10; $i++) {
      $tstring .= tep_rand();
    }
    $salt = substr(md5($tstring), 0, 2);
    $passtring = $salt . $plain_pass;
    $encrypted = md5($passtring);
    
    return ($encrypted . ':' . $salt);
  }
?>
