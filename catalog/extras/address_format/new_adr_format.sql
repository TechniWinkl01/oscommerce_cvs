## mysqldiff 0.25
## 
## run on Fri Nov 17 21:50:06 2000
##
## ---   db: catalog (user=root)
## +++ file: ../../mysql_catalog.sql

ALTER TABLE countries ADD COLUMN address_format_id int(5) DEFAULT '0' NOT NULL;

ALTER TABLE orders ADD COLUMN delivery_address_format_id int(5) DEFAULT '0' NOT NULL after delivery_country;
ALTER TABLE orders ADD COLUMN customers_address_format_id int(5) DEFAULT '0' NOT NULL after customers_email_address;

CREATE TABLE address_format (
  address_format_id int(5) NOT NULL auto_increment,
  address_format varchar(128) DEFAULT '' NOT NULL,
  address_summary varchar(48) DEFAULT '' NOT NULL,
  PRIMARY KEY (address_format_id)
);

#
# Dumping data for table 'address_format'
#
# 1 - Default, 2 - USA, 3 - Spain, 4 - Singapore

INSERT INTO address_format VALUES (1, '$firstname $lastname$cr$streets$cr$city, $postcode$cr$state, $country','$city / $country');
INSERT INTO address_format VALUES (2, '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country','$city, $state / $country');
INSERT INTO address_format VALUES (3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $state, $country','$city / $country');
INSERT INTO address_format VALUES (4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country');

# Update countries to use them

update countries set address_format_id='1';
update countries set address_format_id='2' where countries_id = '223';
update countries set address_format_id='3' where countries_id = '195';
update countries set address_format_id='4' where countries_id = '188';

ALTER TABLE customers CHANGE COLUMN customers_postcode customers_postcode varchar(10) DEFAULT '' NOT NULL;
ALTER TABLE orders CHANGE COLUMN customers_postcode customers_postcode varchar(10) DEFAULT '' NOT NULL;
ALTER TABLE orders CHANGE COLUMN delivery_postcode delivery_postcode varchar(10) DEFAULT '' NOT NULL;
ALTER TABLE address_book CHANGE COLUMN entry_postcode entry_postcode varchar(10) DEFAULT '' NOT NULL;
