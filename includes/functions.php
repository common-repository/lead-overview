<?php 
/* add css and js files*/
function leovw_enqueue_scripts() {
	wp_enqueue_script( 'leovw_cookie_js', plugin_dir_url( __FILE__ ).'../js/cookie.js', false );
	wp_enqueue_script( 'leovw_js',  plugin_dir_url( __FILE__ ).'../js/custom.js', false );
	$dataToBePassed = array(
			'website_url' => get_bloginfo('url')
	);
	wp_localize_script( 'leovw_js', 'php_vars', $dataToBePassed );
}
add_action( 'wp_enqueue_scripts', 'leovw_enqueue_scripts', 11);


/* get all contact from 7 forms*/
if ( !function_exists( 'leovw_getallcf7forms' ) ) {
	function leovw_getallcf7forms(){
		$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
		$cf7Forms = get_posts( $args );
		return $cf7Forms;
	}
}

/* check if salesforce tables exists*/
if ( !function_exists( 'leovw_checkiftableexists' ) ) {
	function leovw_checkiftableexists($tableid){
		global $wpdb;
		$table_name = 'leovw_lead_tables';	
		$table_check = $wpdb->get_var(
				$wpdb->prepare(
						"SELECT id FROM " . $wpdb->prefix . $table_name."
	                    WHERE contact_form_id = %d  LIMIT 1",
						$tableid
						)
				);	
		if ( $table_check > 0 ){
			return '1';
		}
		else{return '';}
	}
}

/* get all contact forms */
if( !function_exists( 'leovw_getallforms' ) ){
	function leovw_getallforms(){
		global $wpdb;
		$table_name = 'leovw_lead_tables';	
		$table_check = $wpdb->get_results(	"SELECT * FROM " . $wpdb->prefix . $table_name);	
		return $table_check;
	}
}

/* insert new line in salesforce table  for newly created forms*/
if ( !function_exists( 'leovw_insertnewcf7' ) ) {
	function leovw_insertnewcf7($formid, $formtitle){
		global $wpdb;
		$table_name = 'leovw_lead_tables';
		$table = $wpdb->prefix.$table_name;
		
		$wpdb->insert( $table,
				array(	"contact_form_id" => $formid,
						"contact_form_name" => $formtitle
				));
	}
}

/* create new table for contact form 7 form, based on the cf7 form id*/
if ( !function_exists( 'leovw_createnewcf7table' ) ) {
	function leovw_createnewcf7table($formid){
		global $wpdb;
		$table_name = $wpdb->prefix.'leovw_cf7data_'.$formid;
			$structure = "CREATE TABLE IF NOT EXISTS $table_name (
				id INT(9) NOT NULL AUTO_INCREMENT,
				first_name VARCHAR(200),
				last_name VARCHAR(200),
				email VARCHAR(200),
				keyword VARCHAR(200),
				referer VARCHAR(200),
				utm_campaign VARCHAR(200),
				utm_source VARCHAR(200),
				utm_medium VARCHAR(200),
				GCLID VARCHAR(200),
				channel VARCHAR(200),			
				url VARCHAR(200),			
				UNIQUE KEY id (id)
			);";
		$wpdb->query($structure);
	}
}

/* delete lead */
if( !function_exists( 'leovw_deletelead' ) ){
	function leovw_deletelead($formid, $leadid){
		global $wpdb;
		$delete = $wpdb->delete( $wpdb->prefix.'leovw_cf7data_'.$formid , array( 'id' => $leadid ) );
		if($delete){
			return '1';
		}
	}
}

/* add filter so you can insert shortcodes in contact from 7 editor */
add_filter( 'wpcf7_form_elements', 'leovw_mycustom_wpcf7_form_elements' );
if( !function_exists( 'leovw_mycustom_wpcf7_form_elements' ) ){
	function leovw_mycustom_wpcf7_form_elements( $form ) {
		$form = do_shortcode( $form );
		return $form;
	}
}

/* contact from 7 - hidden fields shortcode */
if( !function_exists( 'leovw_leadoverview' ) ){
	
	function leovw_leadoverview( $atts ) {
		$obj_id = get_queried_object_id();
		$current_url = get_permalink( $obj_id );
		$cont = '<div class="salesforce_leads_fields">
					<input name="site-referer" value="" type="hidden">
					<input name="utm_campaign" value="" type="hidden">
					<input name="utm_source" value="" type="hidden">
					<input name="utm_medium" value="" type="hidden">
					<input name="kw_field" value="" class="" id="kw_field" type="hidden">
					<input id="gclid" name="GCLID__c" value="" type="hidden">
					<input id="website_url" name="url" value="'.@$current_url.'" type="hidden" />
				</div>';
		return $cont;
	}
}
add_shortcode( 'lead_overview', 'leovw_leadoverview' );

/* check lead channel*/
if ( !function_exists( 'leovw_checkleadchannel' ) ){
	function leovw_checkleadchannel($referer = null, $utm = null, $source = null, $utm_mediu = null){
		$response = '';
	
		if($referer == '' && $source != ''){
			if((strpos($source, 'facebook') !== false) && $utm == 'organic'){
				$response = '2';
			}
			else if((strpos(strtolower($source), 'facebook') !== false) && strtolower($utm) != 'organic'){
				$response = '3';
			}
			else if((strpos(strtolower($source), 'instagram') !== false) && strtolower($utm) == 'organic'){
				$response = '4';
			}
			else if((strpos(strtolower($source), 'instagram') !== false) && strtolower($utm) != 'organic'){
				$response = '5';
			}
			else if((strpos(strtolower($source), 'twitter') !== false) && strtolower($utm) == 'organic'){
				$response = '6';
			}
			else if((strpos(strtolower($source), 'twitter') !== false) && strtolower($utm) != 'organic'){
				$response = '7';
			}
			else if((strpos(strtolower($source), 'linkedin') !== false) && strtolower($utm) == 'organic'){
				$response = '8';
			}
			else if((strpos(strtolower($source), 'linkedin') !== false) && strtolower($utm) != 'organic'){
				$response = '9';
			}
			else if( (strpos(strtolower($source), 'google') !== false) && strtolower($utm_mediu) == 'cpc' ){
				$response = '10';
			}
			else{
				$response = '0';
			}
		}
		else if(!$referer){
			$response = '1';
		}
		else if( (strpos($referer, 'google.') === false) && (strpos($referer, 'facebook.com') === false) && (strpos($referer, 'instagram.com') === false) && (strpos($referer, 'twitter.com') === false) && (strpos($referer, 'linkedin.com') === false)){
			$response = '11';
		}
		else if( (strpos($referer, 'google.') !== false) && !strtolower($utm) ){
			$response = '12';
		}
		else if( (strpos($referer, 'google.') !== false) && strtolower($utm_mediu) == 'cpc' ){
			$response = '10';
		}
		else if((strpos($referer, 'facebook.com') !== false) && strtolower($utm) == 'organic'){
			$response = '2';
		}
		else if((strpos($referer, 'facebook.com') !== false) && strtolower($utm) != 'organic'){
			$response = '3';
		}
		else if((strpos($referer, 'instagram.com') !== false) && strtolower($utm) == 'organic'){
			$response = '4';
		}
		else if((strpos($referer, 'instagram.com') !== false) && strtolower($utm) != 'organic'){
			$response = '5';
		}
		else if((strpos($referer, 'twitter.com') !== false) && strtolower($utm) == 'organic'){
			$response = '6';
		}
		else if((strpos($referer, 'twitter.com') !== false) && strtolower($utm) != 'organic'){
			$response = '7';
		}
		else if((strpos($referer, 'linkedin.com') !== false) && strtolower($utm) == 'organic'){
			$response = '8';
		}
		else if((strpos($referer, 'linkedin.com') !== false) && strtolower($utm) != 'organic'){
			$response = '9';
		}
		else{
			$response = '0';
		}
		return $response;
	}
}

/* function to check lead type*/
if ( !function_exists( 'leovw_checklead' ) ){
	function leovw_checklead($leadtype){
		$leads = array(	'0' => 'Other',
						'1' => 'Direct via Website',
						'2' => 'Facebook Organic via Website',
						'3' => 'Facebook Paid via Website',
						'4' => 'Instagram Organic via Website',
						'5' => 'Instagram Paid via Website',
						'6' => 'Twitter Organic via Website',
						'7' => 'Twitter Paid via Website',
						'8' => 'LinkedIn Organic via Website',
						'9' => 'LinkedIn Paid via Website',
					   '10' => 'Google Adwords via Website',
					   '11' => 'Referral via Website',
					   '12' => 'Organic Search via Website',
					);
		return $leads[$leadtype];
	}
}

if( !function_exists( 'leovw_leadadminnoticesuccess' ) ){
	function leovw_leadadminnoticesuccess() {	
		if(isset($_GET['action']) && $_GET['action'] == 'delete'){
			if(leovw_deletelead($_GET['contact_form'], $_GET['lead'])){?>
			<div class="notice notice-success is-dismissible">
		        <p><?php _e( 'Lead has been deleted successfully!', 'leadoverview' ); ?></p>
		    </div>
			<?php 			
			}
		}
	}
}
add_action( 'admin_notices', 'leovw_leadadminnoticesuccess' );
?>