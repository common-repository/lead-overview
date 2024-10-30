<?php
if ( !class_exists( 'Leovw_Contact' ) ) {
	class Leovw_Contact {
	
		const leadoverview_tbl = 'leovw_lead_tables';
	
		public static $found_items = 0;
	
		public $id;
		
		public static function leovw_createtables() {
			
			global $wpdb;
			$table = $wpdb->prefix.self::leadoverview_tbl;
			$structure = "CREATE TABLE IF NOT EXISTS $table (
							id INT(9) NOT NULL AUTO_INCREMENT,
							contact_form_id VARCHAR(200),
							contact_form_name VARCHAR(200),
							UNIQUE KEY id (id)
						);";
			$wpdb->query($structure);
		
		}
	}
}