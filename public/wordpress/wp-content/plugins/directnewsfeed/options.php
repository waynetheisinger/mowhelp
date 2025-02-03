<?php


//add the administration menu
add_action('admin_menu', 'directnewsfeed_menu');

function directnewsfeed_menu() {
 add_options_page('Direct News Feed Options', 'Direct News Feed', 'manage_options', 'directnews-identifier', 'directnews_options');

//call register settings function
add_action( 'admin_init', 'register_dnsettings' );

}

function register_dnsettings() {
	//register our settings
	register_setting( 'dn-settings-group', 'new_feed_name' );
	register_setting( 'dn-settings-group', 'which_author' );
}

function directnews_options() {
	
?>
<div class="wrap">
<h2>Scheduling the Feed Retrieval</h2>         
      <p>Please follow the next few steps to make sure Direct News Feed works perfectly for you.</p>
    
      <ol id="setup_steps">    
      
        <li>
          <p>To allow Direct News Feed to pull in feeds and create posts, you will need to set up a <strong>cron job</strong>.</p>
        
          <p>Use your web control panel to add this line to your crontab.</p>
        
          <p><strong>20	*	*	*	*	wget <?php echo get_bloginfo("url"); ?>/wp-content/plugins/directnewsfeed/docron.php</strong></p>
        </li>
        <li>
          <p>There's, is another an option called <a href="http://webcron.org/index.php?&lang=en">WebCron</a>, a service that will request a webpage you specify at the time you specify, just like cron. We don't know about its reliability. But if you can't run cron, this might be a great option for you. Set it up for every hour and point it to this URL: 
                
          <p><strong><?php echo get_bloginfo("url"); ?>/wp-content/plugins/directnewsfeed/docron.php</strong></p>
        
        </li>
        </ol>
<h2>Category set up</h2>
<p>Direct News will have given you a list of agreed categories - you can either set them up manually within wordpress or let the plugin create them automatically for you from the data contained within the feed. There is an advantage to setting them up yourself in that you can set up the correct parent categories. When you have set them up the plugin will automatically assign them to the matching category. If you allow auto-creation all categories will be top level categories.</p>
<h2>Direct News Feed Settings</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'dn-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">DirectNews Feed Name</th>
        <td><input type="text" name="new_feed_name" value="<?php echo get_option('new_feed_name'); ?>" /></td>
        </tr>
        <tr>
        <th scope="row">Default Author</th>
        	<td>
        	<?php $args = array('selected' => get_option('which_author'),
								'name' => 'which_author'); 
			wp_dropdown_users($args);		
			?><br>Please note that only the following users are presently marked as authors:<strong> 
			<?php $args = array( 'html'  => false); 
			wp_list_authors($args); ?></strong>
       		</td>
        </tr>
     </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>
