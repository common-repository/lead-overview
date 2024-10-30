<?php
/*
Plugin Name: Contact Form 7 - Lead Tracking
Description: Plugin for listing contact form 7 leads
Author: Slash.Digital
Author URI: https://slash.digital
Text Domain: leadoverview
Domain Path: /languages/
Version: 1.1

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/


define( 'LEOVW_PLUGIN', __FILE__ );

define( 'LEOVW_PLUGIN_BASENAME',
		plugin_basename( LEOVW_PLUGIN ) );

define( 'LEOVW_PLUGIN_NAME',
		trim( dirname( LEOVW_PLUGIN_BASENAME ), '/' ) );

define( 'LEOVW_PLUGIN_DIR',
		untrailingslashit( dirname( LEOVW_PLUGIN ) ) );

require_once LEOVW_PLUGIN_DIR . '/includes/functions.php';
require_once LEOVW_PLUGIN_DIR . '/includes/class-tablelist.php';
require_once LEOVW_PLUGIN_DIR . '/includes/class-contactform.php';
require_once LEOVW_PLUGIN_DIR . '/includes/hooks.php';

if ( is_admin() ) {
	require_once LEOVW_PLUGIN_DIR . '/admin/admin.php';
}


/* Init */
add_action( 'init', 'lead_overview_init' );
function lead_overview_init() {
	do_action( 'lead_overview_init' );
}

function leovwinstall()
{
	Leovw_Contact::leovw_createtables();
	$cf7Forms = leovw_getallcf7forms();
	foreach ( $cf7Forms as $eachform ) {
		if(!leovw_checkiftableexists($eachform->ID)){
			leovw_insertnewcf7($eachform->ID,$eachform->post_title);
			leovw_createnewcf7table($eachform->ID);
		}
	}
}
add_action('activate_lead_overview/lead_overview.php', 'leovwinstall');