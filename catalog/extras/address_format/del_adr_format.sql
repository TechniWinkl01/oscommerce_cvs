#
# This file is to Un-dp the first attempt at address_formats added by add_adr_format.sql
#
# If this is the first time adding this feature then use use new_adr_format.sql


ALTER TABLE countries DROP COLUMN countries_address_format; # was varchar(128) DEFAULT '' NOT NULL
ALTER TABLE countries DROP COLUMN countries_address_summary; # was varchar(48) DEFAULT '' NOT NULL
