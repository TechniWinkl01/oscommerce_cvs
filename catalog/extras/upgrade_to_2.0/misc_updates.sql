## mysqldiff 0.25
## 
## run on Fri Dec  1 11:13:09 2000
##
## ---   db: mpcatalog_20 (user=root)
## +++ file: ../../mysql_catalog.sql

ALTER TABLE customers CHANGE COLUMN customers_password customers_password varchar(40) DEFAULT '' NOT NULL; # was varchar(12) DEFAULT '' NOT NULL

ALTER TABLE manufacturers ADD INDEX IDX_MANUFACTURERS_NAME (manufacturers_name);

ALTER TABLE orders CHANGE COLUMN orders_status orders_status varchar(10) DEFAULT '' NOT NULL; # was varchar(10) DEFAULT 'Pending' NOT NULL
ALTER TABLE orders CHANGE COLUMN orders_date_finished orders_date_finished timestamp(14); # was varchar(14)
ALTER TABLE orders CHANGE COLUMN date_purchased date_purchased timestamp(14); # was varchar(8)
ALTER TABLE orders ADD COLUMN last_modified timestamp(14) after cc_expires;

ALTER TABLE products CHANGE COLUMN products_model products_model varchar(12); # was varchar(20)
ALTER TABLE products CHANGE COLUMN products_viewed products_viewed int(5); # was int(5) DEFAULT '0'
ALTER TABLE products CHANGE COLUMN products_status products_status tinyint(1) DEFAULT '0' NOT NULL; # was tinyint(1) DEFAULT '1' NOT NULL

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('State', 'ENTRY_STATE_MIN_LENGTH', '4', 'Minimum length of state', '2', '7', now());