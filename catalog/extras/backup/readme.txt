$ID: $

TEP Backup Script For Non-Shell Users

Note:  This will not work for everyone, it relies on the 'dumpmysql'
program being available on your server.  If it's not then this has no
chance of working, sorry.

Make sure you change the values in the 'configuration.php' script. If your
PHP install supports GZip files (mine doesn't) then I highly recomend
setting USE_GZIP to 1 as it will cut down on your download time should you
wish to save the backup locally (highly recomended).

Make sure the directory this script is in has write access.  To do this,
go into your FTP program and select this directory and select "chmod" (or
type it, or whatever, depends on your software)  Use 777 as the chmod
value.

This currently only backs up the tables, I may add a 'restore' script
later.  If anyone wants to do that, please go right ahead, it should be
simple.
