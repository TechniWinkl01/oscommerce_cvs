# catalog/mysql_db_structure.sql
#
# Dumping data for table 'configuration_group'
#

insert into configuration_group (configuration_group_title, configuration_group_description, sort_order) values ('Payment Options', 'Payment options available at my store', '6');

#
# Dumping data for table 'configuration'
#

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Cash On Delivery (COD)', 'PAYMENT_SUPPORT_COD', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '1', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Credit Card', 'PAYMENT_SUPPORT_CC', '1', 'Do you want to accept credit card payments?', '6', '2', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow PayPal', 'PAYMENT_SUPPORT_PAYPAL', '1', 'Do you want to accept PayPal payments?', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal ID', 'PAYPAL_ID', 'you@yourbuisness.com', 'Your buisness ID at PayPal.  Usually the email address you signed up with.  You can create a free PayPal account <a href="http://www.paypal.com" target="_blank"><u>here</u></a>.', '6', '4', now());
