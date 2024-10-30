<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<p><?php _e( 'Set the default preference for sending email notification in Buddypress, user can override these setting individually. By default an email will be sent when:', self::return_plugin_namespace() ); ?></p>
<form name="<?php echo self::return_plugin_namespace(); ?>-backend_form" method="post" action="">
<?php wp_nonce_field( self::return_plugin_namespace().'-backend_nonce', self::return_plugin_namespace().'-backend_nonce' ); ?>
<table class="form-table">
<?php foreach(self::return_override_keys() as $key => $value){  ?>
<tr valign="top">
<th scope="row"><label><?php _e($value, self::return_plugin_namespace() ); ?></label></th>
<td>
<input type="radio" name="<?php echo self::return_plugin_namespace().'-'.$key; ?>" value="yes" <?php if (!empty($options[$key]) && ($options[$key] == 'yes')){ echo 'checked="checked"'; }   ?> />
<?php _e( ' Yes', self::return_plugin_namespace() ); ?>
<input type="radio" name="<?php echo self::return_plugin_namespace().'-'.$key; ?>" value="no" <?php if (!empty($options[$key]) && ($options[$key] == 'no')){ echo 'checked="checked"'; }   ?> />
<?php _e( ' No', self::return_plugin_namespace() ); ?>
</td>
</tr>
<?php } ?>
</table>
<?php submit_button(); ?>
</form>