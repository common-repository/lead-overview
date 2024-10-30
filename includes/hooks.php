<?php
// define the wpcf7_after_create callback, do code after a cf7 form is created
if( !function_exists( 'leovw_wpcf7aftercreate' ) ){
	function leovw_wpcf7aftercreate( $instance ) {	
		leovw_insertnewcf7($instance->id,$instance->title);
		leovw_createnewcf7table($instance->id);	
	}
}
add_action( 'wpcf7_after_create', 'leovw_wpcf7aftercreate', 10, 1 );

// define the wpcf7 delete form hook, this code runs after a cf7 form is deleted
add_action( 'before_delete_post', 'leovw_deletewpcf7form' );
if( !function_exists( 'leovw_deletewpcf7form' ) ){
	function leovw_deletewpcf7form( $postid ){
	    // We check if the post type isn't ours and just return 
	    if ( get_post_type( $postid ) != 'wpcf7_contact_form' ) return;
	    // Do your stuff here
	    global $wpdb;
	    $table_name = $wpdb->prefix.'leovw_lead_tables';
	    $structure = 'DELETE FROM '.$table_name.'
				WHERE contact_form_id = '.$postid;
	    $wpdb->query($structure);
	    
	    $structure_2 = "DROP TABLE ".$wpdb->prefix."leovw_cf7data_".$postid;
	    $wpdb->query($structure_2);    
	}
}

// run this code after the contact form 7 form is submitted
add_action('wpcf7_mail_sent', function ($cf7) {
	// Run code after the email has been sent
	global $wpdb;
	$submission = WPCF7_Submission::get_instance();
	if ( $submission ) {
		$data = $submission->get_posted_data();
		$first_name = isset($data['first-name']) ? $data['first-name'] : "";
		$last_name = isset($data['last-name']) ? $data['last-name'] : "";
		$email = isset($data['your-email']) ? $data['your-email'] : "";
		$kw_field = isset($data['kw_field']) ? $data['kw_field'] : "";
		$sitereferer = isset($data['site-referer']) ? $data['site-referer'] : "";
		$utm_campaign = isset($data['utm_campaign']) ? $data['utm_campaign'] : "";
		$utm_source = isset($data['utm_source']) ? $data['utm_source'] : "";
		$utm_medium = isset($data['utm_medium']) ? $data['utm_medium'] : "";
		$gclid = isset($data['GCLID__c']) ? $data['GCLID__c'] : "";
		$url = isset($data['url']) ? $data['url'] : "";
		$channel = leovw_checkleadchannel($sitereferer, $utm_campaign, $utm_source, $utm_medium);
	}
	
	if($first_name && $last_name && $email){
		$table = $wpdb->prefix."leovw_cf7data_".$data['_wpcf7'];
		$wpdb->insert( $table,
				array(	"first_name" => @$first_name,
						"last_name" => @$last_name,
						"email" => @$email,
						"keyword" => @$kw_field,
						"referer" => @$sitereferer,
						"utm_campaign" => @$utm_campaign,
						"utm_source" => @$utm_source,
						"utm_medium" => @$utm_medium,
						"GCLID" => @$gclid,
						"channel" => @$channel,
						"url" => @$url
				));
	}
});
?>