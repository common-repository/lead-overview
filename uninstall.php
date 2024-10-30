<?php 
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

// drop plugin database tables
global $wpdb;

$table_name = 'leovw_lead_tables';
$table_check = $wpdb->get_results(	"SELECT * FROM " . $wpdb->prefix . $table_name);
if($table_check){
	foreach($table_check as $each_table){
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}leovw_cf7data_{$each_table->contact_form_id}");
	}
}
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$table_name}");
?>