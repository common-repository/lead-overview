<?php 
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	class Leovw_Table extends WP_List_Table {	
		var $example_data = '';
		
		function leovw_getleads($contactform, $currentpage, $results_per_page){
			global $wpdb;
			
			$starting_limit_number = ( $currentpage - 1 ) * $results_per_page;
			
			$qry = "SELECT * FROM ". $wpdb->prefix ."leovw_cf7data_".$contactform." LIMIT ".$starting_limit_number.','.$results_per_page;
			$leovw_leads = $wpdb->get_results( $qry, 'ARRAY_A' );
			return $leovw_leads;
		}
		
		function leovw_getleadscount($contactform){
			global $wpdb;
				
			$qry = "SELECT count(id) FROM ".$wpdb->prefix."leovw_cf7data_".$contactform;
			$leovw_leads_count = $wpdb->get_var( $qry );
			return $leovw_leads_count;
		}
		
		function get_columns(){
			$columns = array(
					'first_name'    => 'First Name',
					'last_name'      => 'Last Name',
					'email'      => 'Email',
					'keyword'      => 'Keyword',
					'referer'      => 'Referer',
					'utm_campaign'      => 'Utm campaign',
					'utm_source'      => 'Utm source',
					'utm_medium'      => 'Utm medium',
					'channel'      => 'Channel',
					'url' => 'Url'
			);
			return $columns;
		}
		
		function get_sortable_columns() {
			$sortable_columns = array(
					//'id'  => array('lead_id',false),
			);
			return $sortable_columns;
		}
		
		function prepare_items() {
			$columns = $this->get_columns();
			$hidden = array('id');
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);
			
			$user = get_current_user_id();			// get the current admin screen
			$screen = get_current_screen();			// retrieve the "per_page" option
			$screen_option = $screen->get_option('per_page', 'option');			// retrieve the value of the option stored for the current user
		
			$per_page = get_user_meta($user, $screen_option, true);
			if ( empty ( $per_page) || $per_page < 1 ) {
				$per_page = $screen->get_option( 'per_page', 'default' );
			}
			
			if(isset($_GET['contact_form'])){
				$contact_table = $_GET['contact_form'];
			}
			else{
				$all_forms = leovw_getallforms();
				$contact_table = $all_forms[0]->contact_form_id;
			}
			//$per_page = 20; // test with 20 listing per page
			$page = isset($_GET['paged']) ? $_GET['paged'] : 1;
			$total_items = $this->leovw_getleadscount($contact_table);
			
			$this->set_pagination_args( array(
					'total_items' => $total_items,                  //WE have to calculate the total number of items
					'per_page'    => $per_page                     //WE have to determine how many items to show on a page
			) );
			$this->example_data = $this->leovw_getleads($contact_table, $page, $per_page);
			$this->items = $this->example_data;
		}
		
		function column_default( $item, $column_name ) {
			switch( $column_name ) {
				case 'id':
				case 'first_name':
				case 'last_name':
				case 'email':
				case 'keyword':
				case 'referer':
				case 'utm_campaign':
				case 'utm_source':
				case 'url':
				case 'utm_medium':
					return $item[ $column_name ];
				case 'channel':
					return leovw_checklead($item[ $column_name ]);
				default:
					return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
			}
		}
		
		function column_first_name($item) {
			if(isset($_GET['contact_form'])){
				$actions = array(
						'delete'    => sprintf('<a href="?page=%s&action=%s&lead=%s&contact_form=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id'], @$_GET['contact_form']),
				);
			}
			else{
				$actions = array();
			}
				return sprintf('%1$s %2$s', $item['first_name'], $this->row_actions($actions) );
			
		}
		
	}
	
?>