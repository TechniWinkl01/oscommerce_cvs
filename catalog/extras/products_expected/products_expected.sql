# Brief: Adds sorting options for expected products to the admin configuration screen.
# /* $Id: products_expected.sql,v 1.1 2001/01/16 00:16:27 pkellum Exp $ */

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Expected Sort Order', 'EXPECTED_PRODUCTS_SORT', 'DESC', 'This is the sort order used in the &quot;expected products&quot; box.  It can be either ASC (ascending) or DESC (descending, the default).', '1', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Expected Sort Field', 'EXPECTED_PRODUCTS_FIELD', 'date_expected', 'The column to sort by in the &quot;expected products&quot; box.  Can be &quot;products_name&quot; or &quot;date_expected&quot; (the default)', '1', '7', now());
