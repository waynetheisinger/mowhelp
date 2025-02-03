<?php /*
Plugin Name: Captcha Pro by BestWebSoft
Plugin URI: http://bestwebsoft.com/products/captcha
Description: Protect WordPress website forms from spam entries by means of math logic.
Author: BestWebSoft
Text Domain: captcha-pro
Domain Path: /languages
Version: 0.1
Author URI: http://bestwebsoft.com/
License: Proprietary
*/

if ( ! function_exists( 'cptchpr_admin_menu' ) ) {
	function cptchpr_admin_menu() {
		global $submenu;
		if ( function_exists( 'bws_general_menu' ) ) {
			bws_general_menu();
		} elseif ( function_exists( 'bws_add_general_menu' ) ) {
			bws_add_general_menu( plugin_basename( __FILE__ ) );
		} else {
			require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );
			add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( "images/px.png", __FILE__ ), 1001 );
		}

		$main_page = ( isset( $submenu['bws_panel'] ) ) ? 'bws_panel' : 'bws_plugins';

		$settings_page = add_submenu_page( $main_page, 'Captcha Pro ' . __( 'Settings', 'bestwebsoft' ), 'Captcha Pro', 'manage_options', "captcha_pro.php", 'cptchpr_settings_page' );
		
		if ( function_exists( 'bws_help_tab' ) )
			add_action( "load-{$settings_page}", 'cptchpr_add_tabs' );
	}
}

/* add help tab */
if ( ! function_exists( 'cptchpr_add_tabs' ) ) {
	function cptchpr_add_tabs() {
		$args = array(
			'id'      => 'cptchpr',
			'section' => '200538879'
		);
		bws_help_tab( get_current_screen(), $args );
	}
}

if ( ! function_exists ( 'cptchpr_init' ) ) {
	function cptchpr_init() {
		global $cptchpr_plugin_info;

		if ( file_exists( dirname( __FILE__ ) . '/bws_menu/bws_include.php' ) ) {
			require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		}			

		if ( function_exists( 'bws_include_init' ) )
			bws_include_init( plugin_basename( __FILE__ ) );	
		
		if ( empty( $cptchpr_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$cptchpr_plugin_info = get_plugin_data( __FILE__ );
		}

		$current_plugin = 'captcha-pro/captcha_pro.php';	
		$free_ver_of_current_plugin = 'captcha/captcha.php';

		/* activate free version of plugin */
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		$all_plugins = get_plugins();
		$active_plugins  = get_option( 'active_plugins', array() );
		if ( 0 < count( preg_grep( '/captcha\/captcha.php/', $active_plugins ) ) || is_plugin_active_for_network( $free_ver_of_current_plugin ) ) {
			//
		} elseif ( array_key_exists( $free_ver_of_current_plugin, $all_plugins ) ) {
			array_push( $active_plugins, $free_ver_of_current_plugin );
			update_option( 'active_plugins', $active_plugins );
		}
	}
}

if ( ! function_exists ( 'cptchpr_admin_init' ) ) {
	function cptchpr_admin_init() {
		global $bws_plugin_info, $cptchpr_plugin_info;
		/* Add variable for bws_menu */
		if ( empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '72', 'version' => $cptchpr_plugin_info["Version"] );	
	}
}

if ( ! function_exists( 'cptchpr_plugin_action_links' ) ) {
	function cptchpr_plugin_action_links( $links, $file ) {
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename( __FILE__ );;

		if ( $file == $this_plugin ) {
			$settings_link = '<a href="admin.php?page=captcha_pro.php">' . __( 'Settings', 'bestwebsoft' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}
}

if ( ! function_exists( 'cptchpr_register_plugin_links' ) ) {
	function cptchpr_register_plugin_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			$links[] = '<a href="admin.php?page=captcha_pro.php">' . __( 'Settings', 'bestwebsoft' ) . '</a>';
			$links[] = '<a href="http://bestwebsoft.com/products/captcha/faq/" target="_blank">' . __( 'FAQ', 'bestwebsoft' ) . '</a>';
			$links[] = '<a href="http://support.bestwebsoft.com">' . __( 'Support', 'bestwebsoft' ) . '</a>';
		}
		return $links;
	}
}

/* Function for display settings page in the admin area */
if ( ! function_exists( 'cptchpr_settings_page' ) ) { 
	function cptchpr_settings_page() {
		global $wpdb, $wp_version;
		$error = $license_key = '';
		$update_plugin = false;

		$plugin_name = 'Captcha Pro';
		$current_plugin = 'captcha-pro/captcha_pro.php';	
		$current_plugin_explode = explode( '/', 'captcha-pro/captcha_pro.php' );
		$cron_name = str_replace( '-', '_', $plugin_name[0] ) . '_license_cron';

		if ( isset( $_POST['cptchpr_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'cptchpr_nonce_name' ) ) {
			$license_key = isset( $_POST['cptchpr_license_key'] ) ? trim( $_POST['cptchpr_license_key'] ) : ''; 
			if ( '' != $license_key ) { 
			 	if ( !function_exists( 'get_plugins' ) ) 
			 		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				$plugins_all = get_plugins();
				$current = get_site_transient( 'update_plugins' );
				if ( is_array( $plugins_all ) && !empty( $plugins_all ) && isset( $current ) && is_array( $current->response ) ) {
					$to_send = array();
					$to_send["plugins"][ $current_plugin ] = $plugins_all[ $current_plugin ];
					$to_send["plugins"][ $current_plugin ]["bws_license_key"] = $license_key;
					$to_send["plugins"][ $current_plugin ]["bws_illegal_client"] = 'illegal_use';
					$options = array(
						'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
						'body' => array( 'plugins' => serialize( $to_send ) ),
						'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ) );
					$raw_response = wp_remote_post( 'http://bestwebsoft.com/wp-content/plugins/paid-products/plugins/update-check/1.0/', $options );
					if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
						$error = __( "Something went wrong. Try again later. If the error will appear again, please, contact us", 'bestwebsoft' ) . ' <a href=http://support.bestwebsoft.com>BestWebSoft</a>. ' .  __( "We are sorry for inconvienience.", 'bestwebsoft' );
					} else {
						$response = maybe_unserialize( wp_remote_retrieve_body( $raw_response ) );
						if ( is_array( $response ) && !empty( $response ) ) {
							foreach ( $response as $key => $value ) {
								if ( "wrong_license_key" == $value->package ) {
									$error = __( "Wrong license key", 'bestwebsoft' );	
								} else if ( "wrong_domain" == $value->package ) {
									$error = __( "This license key is bind to another site", 'bestwebsoft' );	
								} else if ( "you_are_banned" == $value->package ) {
									$error = __( "Unfortunately, you have exceeded the number of available tries. Please, upload the plugin manually.", 'bestwebsoft' );
								} else if ( "time_out" == $value->package ) {
									$error = __( 'This license key is valid, but Your license has expired. Please, upload the plugin manually.', 'bestwebsoft' );
								}
							}
							if ( '' == $error ) {
								$bstwbsftwppdtplgns_options_defaults = array();
								if ( !get_option( 'bstwbsftwppdtplgns_options' ) )
									add_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options_defaults );
								$bstwbsftwppdtplgns_options = get_option( 'bstwbsftwppdtplgns_options' );
								$bstwbsftwppdtplgns_options[ $current_plugin ] = $license_key;
								unset( $bstwbsftwppdtplgns_options['wrong_license_key'][ $current_plugin ] );
								update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
								require_once( ABSPATH . 'wp-includes/update.php' );
								wp_update_plugins();
								$current = get_site_transient( 'update_plugins' );
								$new = get_site_transient( 'update_plugins' );
								$new->response = array_merge( $new->response, $response );
								set_site_transient( 'update_plugins', $new ); 
								wp_clear_scheduled_hook( $cron_name ); 
								include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
								include_once( ABSPATH . 'wp-admin/includes/file.php' );
								include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
								include_once( ABSPATH . 'wp-admin/includes/update.php' );
								$update_plugin = true;
							}
						} else {
							$error = __( "Something went wrong. Try again later. If the error will appear again, please, contact us", 'bestwebsoft' ) . ' <a href=http://support.bestwebsoft.com>BestWebSoft</a>. ' .  __( "We are sorry for inconvenience.", 'bestwebsoft' ); 
		 				}
		 			}
	 			}
		 	} else {
	 			$error = __( "Please, enter your license key", 'bestwebsoft' );	
	 		} 
	 	} ?>
		<div class="wrap">
			<h1><?php echo $plugin_name; ?></h1>
			<div class="error">
				<h3><?php _e( "WARNING: Illegal use notification", 'bestwebsoft' ); ?></h3><br />
				<p>
					<strong><?php echo $plugin_name; ?></strong> 
					<?php printf( __( 'plugin is deactivated. You can use one license of %s plugin for one domain only. If you use the plugin on a different domain, it is considered to be illegal use. Please check and edit your license or domain if necessary using you personal Client Area.', 'bestwebsoft' ),
					$plugin_name ); ?> 
					<a href="http://support.bestwebsoft.com/hc/en-us/articles/204240089" target="_blank"><?php _e( 'Learn More', 'bestwebsoft' ); ?></a>
				</p>
				<br />
			</div>
			<div class="error" <?php if ( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<form method="post" action="">
				<p><?php _e( 'Enter your license key into the field below to activate the', 'bestwebsoft' ); ?> <strong><?php echo $plugin_name; ?></strong>.</p>
				<p>
					<?php _e( 'License key', 'bestwebsoft' ); ?>: 
					<input type="text" name="cptchpr_license_key" value="<?php echo $license_key; ?>" />
					<input type="hidden" name="cptchpr_form_submit" value="submit" />
					<input type="submit" class="button-primary" value="<?php _e( 'Validate key and update plugin', 'bestwebsoft' ); ?>" />
					<?php wp_nonce_field( plugin_basename(__FILE__), 'cptchpr_nonce_name' ); ?>
				</p>
			</form> 
			<?php if ( $update_plugin === true ) {
				echo '<h4>' . __( 'License key is valid...', 'bestwebsoft' ) . '</h4>';
				echo '<h4>' . __( 'Updating plugin...', 'bestwebsoft' ) . '</h4>';
				$plugins_list = array();
				$plugins_list[] = $current_plugin; 
				$plugins_list = array_map( 'urldecode', $plugins_list );
				$upgrader = new Plugin_Upgrader( new Bulk_Plugin_Upgrader_Skin() );
				$upgrader->bulk_upgrade( $plugins_list );
			} ?>
		</div>
	<?php } 
}

if ( ! function_exists ( 'cptchpr_admin_head' ) ) {
	function cptchpr_admin_head() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == "captcha_pro.php" ) {
			wp_enqueue_style( 'cptchpr_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		}
	}
}

if ( ! function_exists ( 'cptchpr_plugin_update_row' ) ) {
	function cptchpr_plugin_update_row( $file, $plugin_data ) {
		global $wp_version;	
		if ( is_network_admin() || !is_multisite() ) {
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

			$plugin_key = plugin_basename( __FILE__ );
			$explode_plugin_key = explode( '/', $plugin_key );
			$class = ( $wp_version >= 4.6 ) ? 'active' : '';
			$style = ( $wp_version < 4.6 ) ? ' style="background-color: #FFEBE8;border-color: #CC0000;"' : '';
			$div_class = ( $wp_version >= 4.6 ) ? ' notice inline notice-warning notice-alt' : ''; ?>
			<tr class="bws-plugin-update-tr plugin-update-tr <?php echo $class; ?>" id="<?php echo $explode_plugin_key[0]; ?>-update" data-slug="<?php echo $explode_plugin_key[0]; ?>" data-plugin="<?php echo $plugin_key; ?>">
				<td colspan="<?php echo $wp_list_table->get_column_count(); ?>" class="plugin-update colspanchange">
					<div class="update-message<?php echo $div_class; ?>"<?php echo $style; ?>>
						<?php if ( $wp_version >= 4.6 )
							echo '<p>'; ?>
						<strong><?php _e( 'WARNING: Illegal use notification.', 'bestwebsoft' ); ?></strong> 
						Captcha Pro
						 <?php printf( __( 'plugin is deactivated. You can use one license of %s plugin for one domain only. If you use the plugin on a different domain, it is considered to be illegal use. Please check and edit your license or domain if necessary using you personal Client Area.', 'bestwebsoft' ),
						 '<strong>Captcha Pro</strong>' ); ?>
						  <a href="http://support.bestwebsoft.com/hc/en-us/articles/204240089" target="_blank"><?php _e( "Learn more", 'bestwebsoft' ); ?></a>
						<?php if ( $wp_version >= 4.6 )
							echo '</p>'; ?>
					</div>
				</td>
			</tr>
		<?php }
	}
}

if ( ! function_exists ( 'cptchpr_plugin_banner' ) ) {
	function cptchpr_plugin_banner() {
		global $hook_suffix;	
		if ( $hook_suffix == 'plugins.php' || ( isset( $_GET['page'] ) && $_GET['page'] == "captcha.php" ) ) { ?>
			<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
				<div class="bws_banner_on_plugin_page">
					<div class="icon">			
						<img title="" src="//ps.w.org/captcha/assets/icon-128x128.png" alt="" />
					</div>
					<div class="text">
						<?php _e( 'WARNING: Illegal use notification', 'bestwebsoft' ); ?>
						<p><strong>Captcha Pro</strong> 
						<?php printf( __( 'plugin is deactivated. You can use one license of %s plugin for one domain only. If you use the plugin on a different domain, it is considered to be illegal use. Please check and edit your license or domain if necessary using you personal Client Area.', 'bestwebsoft' ),
						'Captcha Pro' ); ?>
						 <a href="http://support.bestwebsoft.com/hc/en-us/articles/204240089" target="_blank"><?php _e( "Learn more", 'bestwebsoft' ); ?></a></p>
					</div>						
				</div>  
			</div>
		<?php }
	}
}

add_action( 'admin_menu', 'cptchpr_admin_menu' );
add_action( 'network_admin_menu', 'cptchpr_admin_menu' );

/* Additional links on the plugin page */
add_filter( 'plugin_action_links', 'cptchpr_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'cptchpr_register_plugin_links', 10, 2 );

add_action( 'admin_notices', 'cptchpr_plugin_banner' );
add_action( 'network_admin_notices', 'cptchpr_plugin_banner' );

add_action( 'init', 'cptchpr_init' );
add_action( 'admin_init', 'cptchpr_admin_init' );

add_action( 'admin_enqueue_scripts', 'cptchpr_admin_head' );

/* add notice about plugin license */
add_action( 'after_plugin_row_captcha-pro/captcha_pro.php', 'cptchpr_plugin_update_row', 10, 2 );

/* Function for delete plugin - register_uninstall_hook*/
if ( ! function_exists( 'cptch_plugin_deactivation' ) ) {
	function cptch_plugin_deactivation() {
		global $cptch_options;
		if( empty( $cptch_options ) )
			$cptch_options = is_network_admin() ? get_site_option( 'cptch_options' ) : get_option( 'cptch_options' );
		$cptch_options['activate_akismet'] = false;
		update_option( 'cptch_options', $cptch_options );
	}
}

/* Function for delete delete options */
if ( ! function_exists ( 'cptch_delete_options' ) ) {
	function cptch_delete_options() {
		global $wpdb;
		$all_plugins        = get_plugins();
		$is_another_captcha = array_key_exists( 'captcha/captcha.php', $all_plugins ) || array_key_exists( 'captcha-plus/captcha-plus.php', $all_plugins );

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );

		if ( is_multisite() ) {
			delete_site_option( 'cptch_options' );

			/**
			 * Remove old plugin options if the plugin was deactivated before updating to v.4.2.3 ad higher
			 * @deprecated since 4.2.3
			 * @todo       remove after 1.0.3.2017
			 */
			delete_site_option( 'cptchpr_options' );

		}

		/* do nothing if Plus or Pro BWS CAPTCHA are installed */
		if ( $is_another_captcha )
			return;

		if ( is_multisite() ) {
			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				delete_option( 'cptch_options' );

				/**
				 * Remove old plugin options if the plugin for current site was deactivated before updating to v.4.2.3 ad higher
				 * @deprecated since 4.2.3
				 * @todo       remove after 1.0.3.2017
				 */
				delete_option( 'cptchpr_options' );

				$prefix = 1 == $blog_id ? $wpdb->base_prefix : $wpdb->base_prefix . $blog_id . '_';
				$wpdb->query( "DROP TABLE `{$prefix}cptch_whitelist`;" );
			}
			switch_to_blog( 1 );
			$upload_dir = wp_upload_dir();
			switch_to_blog( $old_blog );
		} else {
			delete_option( 'cptch_options' );

			/**
			 * Remove old plugin options if the plugin was deactivated before updating to v.4.2.3 ad higher
			 * @deprecated since 4.2.3
			 * @todo       remove after 1.0.3.2017
			 */
			delete_option( 'cptchpr_options' );

			$wpdb->query( "DROP TABLE `{$wpdb->prefix}cptch_whitelist`;" );
			$upload_dir = wp_upload_dir();
		}

		/* delete images */
		$wpdb->query( "DROP TABLE `{$wpdb->base_prefix}cptch_images`, `{$wpdb->base_prefix}cptch_packages`;" );
		$images_dir = $upload_dir['basedir'] . '/bws_captcha_images';
		$packages   = scandir( $images_dir );
		if ( is_array( $packages ) ) {
			foreach ( $packages as $package ) {
				if ( ! in_array( $package, array( '.', '..' ) ) ) {
					/* remove all files from package */
					array_map( 'unlink', glob( "{$images_dir}/{$package}/*.*" ) );
					/* remove package */
					rmdir( "{$images_dir}/{$package}" );
				}
			}
		}
		rmdir( $images_dir );
	}
}
register_deactivation_hook( "captcha-pro/captcha_pro.php", 'cptch_plugin_deactivation' );
register_uninstall_hook( "captcha-pro/captcha_pro.php", 'cptch_delete_options' ); ?>