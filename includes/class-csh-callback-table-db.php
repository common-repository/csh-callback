<?php
/**
 * Change prefix to p.refix in this file.
 * Class to handle all custom post type definitions for Restaurant Reservations
 */

if ( !defined( 'ABSPATH' ) )
	exit;

class Csh_Callback_Table_Database {
		
	public function __construct() {

		// Call when plugin is initialized on every page load
		add_action( 'delete_post', array( $this, 'prefix_meta_delete_post') );

	}
	
	function create_table_payment() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'csh_callback_request';//
		//check table exist
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			email tinytext,
			phone tinytext NOT NULL,
			message text,
			from_time varchar(255) DEFAULT '00:00',
			to_time varchar(255) DEFAULT '00:00',
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			called mediumint(1) DEFAULT 0,
			PRIMARY KEY (id)
		) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
        else // update
        {

        }
	}
	
	function drop_table_payment() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'csh_callback_request';
		$sql = "DROP TABLE IF EXISTS $table_name;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$wpdb->query( $wpdb->prepare(( $sql )));
	}
	
}
