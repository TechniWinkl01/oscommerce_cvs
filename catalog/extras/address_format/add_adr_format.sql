#
# Add the address ofrmat and address summary format strings to countries table
# also make postcode 10 chars long, USA ZIP+4 is 5 "-" 4 chars long... or 10
#

ALTER TABLE countries ADD COLUMN countries_address_format varchar(128) DEFAULT '' NOT NULL;
ALTER TABLE countries ADD COLUMN countries_address_summary varchar(48) DEFAULT '' NOT NULL;

ALTER TABLE customers CHANGE COLUMN customers_postcode customers_postcode varchar(10) DEFAULT '' NOT NULL;
ALTER TABLE orders CHANGE COLUMN customers_postcode customers_postcode varchar(10) DEFAULT '' NOT NULL;
ALTER TABLE orders CHANGE COLUMN delivery_postcode delivery_postcode varchar(10) DEFAULT '' NOT NULL;
ALTER TABLE address_book CHANGE COLUMN entry_postcode entry_postcode varchar(10) DEFAULT '' NOT NULL;

update countries set countries_address_format='$firstname $lastname$cr$streets$cr$city, $postcode$cr$state, $country' where countries_id > 0;
update countries set countries_address_summary='$city / $country' where countries_id > 0;

# for USA (223)
update countries set countries_address_format='$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country' where countries_id = '223';
update countries set countries_address_summary='$city, $state / $country' where countries_id = '223';

# for Spain (195)
update countries set countries_address_format='$firstname $lastname$cr$streets$cr$city$cr$postcode - $state, $country' where countries_id = '195';
update countries set countries_address_summary='$city, $state / $country' where countries_id = '195';
