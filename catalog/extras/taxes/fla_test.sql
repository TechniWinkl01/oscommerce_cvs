INSERT INTO tax_class VALUES (1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', now(), now());
INSERT INTO tax_rates VALUES (1, 18, 1, 7.0, 'FL TAX 7.0%', now(), now());

update products set products_tax_class_id = 1 where products_tax_class_id = '0';

