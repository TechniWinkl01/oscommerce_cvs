# Brief: Adds a country code option to the "My Store" configuration page.
# $Id: shipping_3.sql,v 1.1 2001/02/03 08:47:19 pkellum Exp $

# My Store Update
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Country Code', 'STORE_ORIGIN_COUNTRY', 'NONE', 'Enter the &quot;ISO 3166&quot; Country Code of the Store to be used in shipping quotes.  To find your country code, visit the <A HREF="http://www.din.de/gremien/nas/nabd/iso3166ma/codlstp1/index.html" TARGET="_blank">ISO 3166 Maintenance Agency</A>.', '1', '9', now());
