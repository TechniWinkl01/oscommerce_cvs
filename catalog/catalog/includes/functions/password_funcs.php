<?php
/********************************************************************
*	password_funcs.php: Functions to handle encryption 
*   	and validation of user passwords.
*	Copyright (C) 2000 Darren McClelland. All rights reserved. 
*   	This program is free software licensed under the 
*   	GNU General Public License (GPL).
*
*
*    This program is free software; you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation; either version 2 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307
*    USA
*   
*   $Log: password_funcs.php,v $
*   Revision 1.1  2000/10/18 03:57:26  dmcclelland
*   Added one way ecrypted password support.
*   This alters the customer table by extending the
*   password filed to 40 chars long. One define and an additional
*   include are added to application_top.php
*   A new file password_funcs.php is included in functions/.
*   The fogottoen password script now will assign a random
*   new password to the user and then mail it to them.
*
*    
*
*   $Id: password_funcs.php,v 1.1 2000/10/18 03:57:26 dmcclelland Exp $ 
*********************************************************************/

/*  This funstion validates a candidate password.
*   $plain_pass is the plaintext password entered by the
*   user.
*   $db_pass is the contents of the customer_password field
*   in the customer table. $db_pass has this structure:
*   hash:salt Hash is an MD5 hash of the password + salt
*   and salt is a two character 'salt'.*/

function validate_password($plain_pass, $db_pass){
     
     /* split apart the hash / salt*/
     if(!($subbits = split(":", $db_pass, 2))){
     	return(FALSE);
    }
    
    $dbpassword = $subbits[0];
    $salt = $subbits[1];
    
    $passtring = $salt . $plain_pass;
    
    $encrypted = md5($passtring);
    if(strcmp($dbpassword, $encrypted) == 0)
    {
	return(TRUE);
    }else{
	return(FALSE);
    }
} // function validate_password($plain_pass, $db_pass)

/*  This function makes a new password from a plaintext password. An
*   encrypted password + salt is returned */

function crypt_password($plain_pass){
    /* create a semi random salt */
    
    $tarray = gettimeofday();
    $tstring += $tarray['usec'];
    $tstring  = $tarray['sec'];
    $salt = substr(md5($tstring),0, 2);
    
    $passtring = $salt . $plain_pass;
    
    $encrypted = md5($passtring);
    
    return($encrypted . ":" . $salt);
} // function crypt_password($plain_pass)

/* Function to create a random password of the specified length */
function random_password($length){
 $newpass = '';
 
 /** Init random num generator*/
 mt_srand ((double) microtime() * 1000000);
 while($length > 0){
    /*Create a new password using numbers and upper case letters ASCII 48-57
    * and 65 to 90 I have no idea how this will affect non ASCII users */
    $newchar = mt_rand(48, 90);
    if($newchar > 57 && $newchar < 65){ // Only use numbers and upper case letters
    	continue;
    }
    $newpass .= sprintf("%c",$newchar);
    $length--; 
 }
 return($newpass);
}

?>
