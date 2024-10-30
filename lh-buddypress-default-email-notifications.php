<?php
/**
 * Plugin Name:     LH Buddypress Default Email Notifications
 * Version:         1.00
 * Author:          Peter Shaw
 * Author URI:      https://shawfactor.com
 * Plugin URI:      https://lhero.org/portfolio/lh-buddypress-default-email-notifications/
 * Description:     Set the defaut email notification preferences for buddypress members
 * Text Domain:     lh_bp_den
 * Domain Path:     /languages
 */
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 


if (!class_exists('LH_Buddypress_default_email_notifications_plugin')) { 
 
class LH_Buddypress_default_email_notifications_plugin {

private static $instance;


static function return_plugin_namespace(){

    return 'lh_bp_den';

    }
    
static function return_opt_name(){
    
return self::return_plugin_namespace().'-options';
    
}

static function return_file_name(){
    
return plugin_basename( __FILE__ );    
    
}


    
static function is_this_plugin_network_activated(){

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_plugin_active_for_network( self::return_file_name() ) ) {
    // Plugin is activated

return true;

} else  {


return false;


}

}

static function get_plugin_options() {

if (self::is_this_plugin_network_activated()){

$options = get_site_option(self::return_opt_name());

} else {



$options = get_option(self::return_opt_name());

}

return $options;

}



	
public function network_plugin_menu() {

add_submenu_page('settings.php', 'LH Buddypress Default Email Notifications', 'BP Email Notifications', 'manage_options', self::return_file_name(), array($this,'plugin_options'));

}

public function plugin_menu() {
add_options_page('LH Buddypress Default Email Notifications', 'BP Email Notifications', 'manage_options', self::return_file_name(), array($this,"plugin_options"));

}


public function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
 // See if the user has posted us some information
    // If they did, the nonce will be set

	if( isset($_POST[ self::return_plugin_namespace()."-backend_nonce" ]) && wp_verify_nonce($_POST[ self::return_plugin_namespace()."-backend_nonce" ], self::return_plugin_namespace()."-backend_nonce" )) {


foreach(self::return_override_keys() as $key => $value){
    
    
if (!empty($_POST[self::return_plugin_namespace().'-'.$key]) && ((sanitize_text_field($_POST[self::return_plugin_namespace().'-'.$key]) == 'yes') or (sanitize_text_field($_POST[self::return_plugin_namespace().'-'.$key]) == 'no'))){
    
    
$options[$key] = sanitize_text_field($_POST[self::return_plugin_namespace().'-'.$key]);    
    
}
    
}

if (self::is_this_plugin_network_activated()){


if (update_site_option( self::return_opt_name(), $options )){

$options = get_site_option(self::return_opt_name());


?>
<div class="updated"><p><strong><?php _e('Settings saved', self::return_plugin_namespace() ); ?></strong></p></div>
<?php

} 


} else {

if (update_option( self::return_opt_name(), $options )){

$options = get_option(self::return_opt_name());


?>
<div class="updated"><p><strong><?php _e('Settings saved', self::return_plugin_namespace() ); ?></strong></p></div>
<?php

} 

}


}
	
$options = self::get_plugin_options();
	
	
// Now display the settings editing screen
include ('partials/settings.php');	
	
}

    
static function return_override_keys(){
    
    
    return array(
        'notification_activity_new_mention' => __( 'A member mentions the user using @username', self::return_plugin_namespace() ),
        'notification_activity_new_reply' => __( 'A member replies to a comment or activity posted by the user', self::return_plugin_namespace() ),
        'notification_messages_new_message' => __( 'A member sends the user a private message', self::return_plugin_namespace() ),
        'notification_friends_friendship_request' => __( 'A member sends the user a friendship request', self::return_plugin_namespace() ),
        'notification_friends_friendship_accepted' => __( 'A member accepts the users friendship request', self::return_plugin_namespace() ),
        'notification_groups_admin_promotion' => __( 'The user has been promoted to an admin or moderator', self::return_plugin_namespace()),
        'notification_groups_group_updated' => __( 'A group the user is admin or moderater is updated', self::return_plugin_namespace()),
        'notification_groups_invite' => __( 'The user is invited by a member to join a group', self::return_plugin_namespace()),
        'notification_groups_membership_request' => __( 'A member requests to join a group for which the user is an admin', self::return_plugin_namespace()),
        'notification_membership_request_completed' => __( 'The users request to join a group has been approved or denied', self::return_plugin_namespace()),
        );
    
}
    
public function set_default_value($value, $object_id, $meta_key, $single, $meta_type){
    
    $options = self::get_plugin_options();
    
    if (!empty($options[$meta_key]) && (($options[$meta_key] == 'yes') or ($options[$meta_key] == 'no'))){
        
        $value = $options[$meta_key];
        
    }
    
    
return $value;

}


	public function plugin_init() {
	    
	    load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	    
	    if ( self::is_this_plugin_network_activated() ) {
    
        //create a mennu under teh network management if network activated
        add_action('network_admin_menu', array($this,'network_plugin_menu'));

        } else {
    
         
        add_action('admin_menu', array($this,'plugin_menu'));

        }
	    
	    //set the default values 
	    add_filter( 'default_user_metadata', array( $this, 'set_default_value' ), 10, 5 );
		

	}
	
    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }


public function __construct() {
    
	 //run whatever on bp loaded
    add_action( 'bp_loaded', array($this,'plugin_init'));
 
}


}


$lh_buddypress_default_email_notifications_instance = LH_Buddypress_default_email_notifications_plugin::get_instance();

}

?>