# catalog/mysql_db_structure.sql
#
# Dumping data for table 'configuration_group'
#

insert into configuration_group (configuration_group_title, configuration_group_description, sort_order) values ('Shipping Options', 'Shipping options available at my store', '7');

#
# Dumping data for table 'configuration'
#

insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Methods to offer to customer', 'SHIPPING_MODULES', 'ups.php flat.php item.php', 'List the file names of the methods as listed in the shipping directory. ups.php flat.php item.php, etc', '7', '1', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '3', 'What is the weight of typical packaging of small to medium packages?', '7', '2', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Larger packages may be based upon a percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', '7', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'SHIPPING_HANDLING', '5.00', 'Enter the handling fee you may charge.', '7', '4', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Flate Cost', 'SHIPPING_FLAT_COST', '5.00', 'What is the Shipping cost? The Handling fee will also be added.', '7', '5', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Per Item shipping cost', 'SHIPPING_ITEM_COST', '2.50', 'How much will be charged for each item ordered?', '7', '6', now());

