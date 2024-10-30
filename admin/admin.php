<?php
add_action( 'admin_menu', 'leovw_leadsadmin_menu', 8 );

if ( !function_exists( 'leovw_leadsadmin_menu' ) ) {
	function leovw_leadsadmin_menu() {
		global $_wp_last_object_menu;
	
		$_wp_last_object_menu++;
		add_menu_page(
				__( 'Lead Overview', 'leadoverview' ),
				__( 'Lead Overview', 'leadoverview' ),
				'read', 'leovw',
				'leovw_contact_admin_page', 'dashicons-feedback',
				$_wp_last_object_menu );
	
		$contact_admin = add_submenu_page( 'leadoverview',
				__( 'Lead Overview', 'leadoverview' ),
				__( 'Lead Overview', 'leadoverview' ),
				'read', 'leovw',
				'leovw_contact_admin_page' );
	
				add_action( 'load-' . $contact_admin, 'leovw_load_leads_admin' );
	
				$inbound_admin = add_submenu_page( 'leovw',
						__( 'How to use', 'leadoverview' ),
						__( 'How to use', 'leadoverview' ),
						'read', 'leovw_readme',
						'leovw_inbound_admin_page' );	
	}
}

if( !function_exists( 'leovw_load_leads_admin' ) ){
	function leovw_load_leads_admin(){
		$screen = get_current_screen();
	
		global $leovw_table;
		$option = 'per_page';
		$args = array(
				'label' => __('Leads per page', 'leadoverview'),
				'default' => 20,
				'option' => 'leovw_per_page'
		);
		add_screen_option( $option, $args );
		$leovw_table = new Leovw_Table;
	}
}

if( !function_exists('leovw_set_screen_option') ){
	function leovw_set_screen_option($status, $option, $value) {
		if ( 'leovw_per_page' == $option ) return $value;
	}
}
add_filter('set-screen-option', 'leovw_set_screen_option', 10, 3);

if ( !function_exists( 'leovw_contact_admin_page' ) ){
	function leovw_contact_admin_page(){
		echo '<h1>Leads Overview</h1>';
		include LEOVW_PLUGIN_DIR . '/admin/listing-form.php';
		
		$leovw_table = new Leovw_Table();
		$leovw_table->prepare_items();
		$leovw_table->display();
	}	
}

if ( !function_exists( 'leovw_inbound_admin_page' ) ){
	function leovw_inbound_admin_page(){ ?>
		<h1>How to use</h1>	
		<h2>Requirements</h2>
		<p>Make sure you are using these shortcodes in your contact form, in order for the tracking to work properly:</p>
		<p>[text* first-name]</p>
		<p>[text* last-name]</p>
		<p>[email* your-email]</p>
		<br/><br/>
		<h2>Installation</h2>
		<p>Add the shortcode [lead_overview] at the top of every form you want to track.</p>
<?php 
	} 
}
?>