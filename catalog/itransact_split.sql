# MySQL-Dump
#
# For use in The Exchange Project Preview Release 2.2
#
# NOTE: * Please make any modifications to this file by hand!
#       * DO NOT use a mysqldump created file for new changes!
#       * Please take note of the table structure, and use this
#         structure as a standard for future modifications!
#       * To see the 'diff'erence between MySQL databases, use
#         the mysqldiff perl script located in the extras
#         directory of the 'catalog' module.

#
# Table structure for table 'orders_itransact_auth'
#

DROP TABLE IF EXISTS orders_itransact_auth;
CREATE TABLE orders_itransact_auth (
   customer_id int(5),
   status varchar(10) NOT NULL,
   auth_id int(9) NOT NULL auto_increment,
   orders_id int(5),
   gateway_id_begun int(15),
   gateway_id_complete int(15),
   authcode varchar(6) NOT NULL,
   datetime_begun datetime,
   datetime_itransact_timestamp datetime,
   total_begun decimal(8,2),
   total_complete decimal(8,2),
   cc_last_four varchar(4) NOT NULL,
   xid varchar(15) NOT NULL,
   test_mode int(1),
   avs_response varchar(15) NOT NULL,
   signature blob NOT NULL,
   sig_rand_begun varchar(16),
   sig_rand_complete varchar(16),
   sig_valid enum('no','yes'),
   err blob NOT NULL,
   die int(1) DEFAULT '0' NOT NULL,
   sesskey_begun varchar(32) NOT NULL,
   sesskey_complete varchar(32),
   UNIQUE auth_id (auth_id)
);
