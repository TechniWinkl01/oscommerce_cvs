## mysqldiff 0.25
## 
## run on Fri Nov  3 17:10:13 2000
##
## --- file: ./mysql_catalog.sql
## +++ file: ./new_catalog.sql

ALTER TABLE address_book ADD COLUMN entry_zone_id int(5) DEFAULT '0' NOT NULL;

ALTER TABLE customers ADD COLUMN customers_zone_id int(5) DEFAULT '0' NOT NULL;

ALTER TABLE orders DROP COLUMN products_tax; # was decimal(6,4) DEFAULT '0.0000' NOT NULL

ALTER TABLE orders_products ADD COLUMN products_tax decimal(7,4) DEFAULT '0.0000' NOT NULL after final_price;

ALTER TABLE products ADD COLUMN products_tax_class_id int(5) DEFAULT '0' NOT NULL;

CREATE TABLE tax_class (
  tax_class_id int(5) DEFAULT '0' NOT NULL auto_increment,
  tax_class_title varchar(32) DEFAULT '' NOT NULL,
  tax_class_description varchar(255) NOT NULL,
  last_modified timestamp(14) NULL,
  date_added timestamp(14) NOT NULL,
  PRIMARY KEY (tax_class_id)
);

CREATE TABLE tax_rates (
  tax_rates_id int(5) NOT NULL auto_increment,
  tax_zone_id int(5) DEFAULT '0' NOT NULL,
  tax_class_id int(5) DEFAULT '0' NOT NULL,
  tax_rate decimal(7,4) DEFAULT '0.0000' NOT NULL,
  tax_description varchar(255) DEFAULT '' NOT NULL,
  last_modified timestamp(14) NULL,
  date_added timestamp(14) NOT NULL,
  PRIMARY KEY (tax_rates_id)
);

CREATE TABLE zones (
  zone_id int(5) DEFAULT '0' NOT NULL auto_increment,
  zone_country_id int(5) DEFAULT '0' NOT NULL,
  zone_code varchar(5) DEFAULT '' NOT NULL,
  zone_name varchar(32) DEFAULT '' NOT NULL,
  PRIMARY KEY (zone_id)
);

