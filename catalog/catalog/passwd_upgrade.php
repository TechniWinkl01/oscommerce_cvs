<? 
/********************************************************************
*	passwd_upgrade.php: A script to upgrade TEP databases
*   	to use one way encrypted passwords. The script will
*   	modify the customer_password field to be 40 chars long,
*   	encrypt and update all the users passwords.
*   	The script contains a check to attempt to determine
*   	if it is being run on a database that has already been converted.
*   	If any of the passwords contain the ':' character
*   	it is assummed that the database already uses encrypted
*   	passwords.

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
*   $Log: passwd_upgrade.php,v $
*   Revision 1.1  2000/10/18 05:03:40  dmcclelland
*   Initial Entry into CVS.
*
*   A tool to upgrade a TEP database to use
*   one way MD5 encrypted passwords.
*   It will change the size of the customers_password
*   field in the customers table to be
*   a varchar(4) and encrypte the users old
*   password and update it in the database.
*   A simple check is included to try to prevent it from being
*   run more than once on a database.
*
*    
*
*   $Id: passwd_upgrade.php,v 1.1 2000/10/18 05:03:40 dmcclelland Exp $ 
*********************************************************************/
include('includes/application_top.php'); 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<HTML>

<HEAD>
<TITLE>Upgrade passwords</TITLE>
</HEAD>
<BODY BGCOLOR="#FFFFFF">

<?php if(!$continue_confirm): ?>

<H2><center>Warning!! This will change the password configuration of the database</center></H2>
<p>
All of the passwords will be converted to one way MD5 hashes. The original passwords
are not recoverable. It is reccomended that you back up your database before
continueing!
</p>
<p>
If you are absolutely sure that you wish to continue, follow
the this <a href="passwd_upgrade.php?continue_confirm=1">link</a> to begin the process.
</p>
<?php  exit(); endif; ?>

<?
    
    // Alter the database
    printf("<p>Changing password field to varchar(40) .....\n");
    tep_db_query("ALTER TABLE customers modify  customers_password varchar(40)");    
    printf(" Done!</P><br>\n");
    
    // Select all the users and grab their old passwords
    $passwords = tep_db_query("SELECT customers_id, customers_password, customers_firstname, customers_lastname FROM customers ORDER BY customers_lastname");
?>
<P>
Changing passwords....
<TABLE>
<TR>
    <TH>First Name</TH>
    <TH>Last Name</TH>
    <TH>Old Password</TH>
    <TH>New Password</TH>
</TR>
    
<?    
    while($password_vals = tep_db_fetch_array($passwords)){
    	$oldpass = $password_vals['customers_password'];
	// Test if the database has already been converted
	// Remove this check if an old style database has passwords with ':' in them
	if(strstr($oldpass, ":")){
	    printf("<b>Warning This database appears to already have been converted!<br>
	    If you are absoloutly sure that it has not been comment out this code block
	    in the upgrade script and continue.</b></table>");
	    exit();
	}
	$newpass = crypt_password($oldpass);
	printf("<TR><TD>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
	    $password_vals['customers_firstname'],
	    $password_vals['customers_lastname'],
	    $oldpass, $newpass);
	$sql = sprintf("UPDATE customers SET customers_password = '%s' WHERE customers_id = %d",
	    $newpass,
	    $password_vals['customers_id']);
	
	tep_db_query($sql);
    
    } // while
    
?>
</TABLE>
</p>
<p>
Upgrade Complete! You should now remove or rename this script so that it will 
not be  run by accident.
</p>
</BODY>
</HTML>
