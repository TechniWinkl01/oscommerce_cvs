# catalog/mysql_db_structure.sql
#
# Dumping data for table 'configuration_group'
#

# insert into configuration_group (configuration_group_title, configuration_group_description, sort_order) values ('Shipping Options', 'Shipping options available at my store', '7');

#
# Dumping data for table 'configuration'
#

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Pickup Method', 'SHIPPING_UPS_PICKUP', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', '7', '5', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Packaging?.', 'SHIPPING_UPS_PACKAGE', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', '7', '6', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Residential Delivery?', 'SHIPPING_UPS_RES', 'RES', 'Quote for Residential (RES) or Commerical Delivery (COM)', '7', '7', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS USERID', 'SHIPPING_USPS_USERID', 'NONE', 'Enter the USPS USERID assigned to you. Register at PriorityMail.com and also tell them you are an end user not developer.', '7', '11', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS Password', 'SHIPPING_USPS_PASSWORD', 'NONE', 'See USERID, above.', '7', '12', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS URL of the production Server', 'SHIPPING_USPS_SERVER', 'NONE', 'See above', '7', '13', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', '7', '14', now());

# My Store Update 
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Postal Code', 'STORE_ORIGIN_ZIP', 'NONE', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', '1', '8', now());

