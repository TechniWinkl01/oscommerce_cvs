<?
/*
English Text for The Exchange Project Administration Tool Preview Release 1.1
Last Update: 12/06/2000
Author(s): Harald Ponce de Leon (hpdl@theexchangeproject.org)
*/

define('TOP_BAR_TITLE', 'Administration Information');
define('HEADING_TITLE', 'What Does This Button Do?');
define('SUB_BAR_TITLE', 'The Exchange Project: Administration Tool Preview Release 1.1');
define('TEXT_MAIN', 'Any changes made with the administration tool takes effect immediately on the database. If you are unsure of what this administration tool can, and will, do, then it is suggested that you read through the <a href="http://theexchangeproject.org/documentation_dbmodel.php" target="_blank"><u>database model documentation</u></a>.<br>&nbsp;<br>What you do with this tool is caused by your own actions. It is recommended that backups are made reguarly. To make backups with MySQL, use mysqldump:<br>&nbsp;<br>mysqldump catalog >./catalog.sql&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// dump the model + data to ./catalog.sql.<br>&nbsp;<br>mysql catalog <./catalog.sql&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// import the model + data to the database.<br>&nbsp;<br>This administration tool is made for Preview Release 1.1 of The Exchange Project. For quick notes on this administration tool, go to the support site.<br>&nbsp;<br><font color="#ff0000"><small><b>NOTE:</b></small></font> To upload or remove images, make sure the catalog/images directory has WRITE permission for the user that runs your Apache process (eg, nobody). This can be made by executing the following commanda:<br><br>cd catalog<br>chmod -R nobody.nobody images');
?>