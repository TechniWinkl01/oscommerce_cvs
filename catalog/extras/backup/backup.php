<?php
	/* $Id: backup.php,v 1.1 2001/02/23 04:54:34 pkellum Exp $
	 *
	 * TEP Backup Script
	 */
	include('configuration.php');
	/*
	 * Tables used by TEP
	 *
	 * You could add additional tables to this list if you
	 * want to back them up as well.
	 *
	 */
	$dump_tables = array(
		'address_book',
		'address_book_to_customers',
		'categories',
		'configuration',
		'configuration_group',
		'counter',
		'counter_history',
		'address_format',
		'countries',
		'customers',
		'customers_basket',
		'customers_basket_attributes',
		'customers_info',
		'manufacturers',
		'orders',
		'orders_products',
		'orders_products_attributes',
		'products',
		'products_attributes',
		'products_expected',
		'products_options',
		'products_options_values',
		'products_options_values_to_products_options',
		'products_to_categories',
		'products_to_manufacturers',
		'reviews',
		'reviews_extra',
		'specials',
		'zones',
		'tax_class',
		'tax_rates'
	);
	$dump_tables = join(' ', $dump_tables);
	$sql_out = array();
	exec('mysqldump --add-drop-table -a -c -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p' . DB_SERVER_PASSWORD . ' ' . DB_DATABASE . ' ' . $dump_tables, $sql_out);
	if(count($sql_out) > 100) {
		if(USE_GZIP) {
			$fp = gzopen('tep_backup.sql', 'w');
		}
		else {
			$fp = fopen('tep_backup.sql', 'w');
		}
		foreach($sql_out as $line) {
			if(USE_GZIP) {
				gzputs($fp, $line . "\n");
			}
			else {
				fputs($fp, $line . "\n");
			}
		}
		if(USE_GZIP) {
			gzclose($fp);
		}
		else {
			fclose($fp);
		}
		print 'Backup complete.';
		if(USE_GZIP) {
			print '<P><A HREF="tep_backup.sql">Download</A>';
		}
		else {
			print '<P><A HREF="tep_backup.sql">View</A> You can use the &quot;Save as...&quot; selection in your browser to save this locally.';
		}
	}
	else {
		print 'Warning: Access to sql server failed.';
	}
?>