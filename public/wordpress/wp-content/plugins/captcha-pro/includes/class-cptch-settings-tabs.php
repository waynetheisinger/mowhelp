<?php
/**
 * Displays the content on the plugin settings page
 */

require_once( dirname( dirname( __FILE__ ) ) . '/bws_menu/class-bws-settings.php' );

if ( ! class_exists( 'Cptch_Settings_Tabs' ) ) {
	class Cptch_Settings_Tabs extends Bws_Settings_Tabs {
		private $forms, $form_categories, $registered_forms, $package_list;

		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename
		 */
		public function __construct( $plugin_basename ) {
			global $cptch_options, $cptch_plugin_info;

			if ( is_network_admin() ) {
				$tabs = array(
					'settings'    => array( 'label' => __( 'Settings', 'captcha-pro' ) ),
					'messages'    => array( 'label' => __( 'Messages', 'captcha-pro' ) ),
					'misc' 	      => array( 'label' => __( 'Misc', 'captcha-pro' ) ),
					'license'     => array( 'label' => __( 'License Key', 'captcha-pro' ) )
				);
			} else {
				$tabs = array(
					'settings'    => array( 'label' => __( 'Settings', 'captcha-pro' ) ),
					'messages'    => array( 'label' => __( 'Messages', 'captcha-pro' ) ),
					'misc' 	      => array( 'label' => __( 'Misc', 'captcha-pro' ) ),
					'custom_code' => array( 'label' => __( 'Custom Code', 'captcha-pro' ) ),
					'license'     => array( 'label' => __( 'License Key', 'captcha-pro' ) )
				);
			}

			if ( ! function_exists( 'get_plugins' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( ! function_exists( 'cptch_get_plugin_status' ) )
				require_once( dirname(__FILE__) . '/helpers.php' );

			if ( ! function_exists( 'cptch_get_default_options' ) )
				require_once( dirname( __FILE__ ) . '/helpers.php' );

			parent::__construct( array(
				'plugin_basename'    => $plugin_basename,
				'plugins_info'       => $cptch_plugin_info,
				'prefix'             => 'cptch',
				'default_options'    => cptch_get_default_options(),
				'options'            => $cptch_options,
				'is_network_options' => is_network_admin(),
				'tabs'               => $tabs,
				'wp_slug'            => 'captcha-bws',
				'doc_link'           => 'https://docs.google.com/document/d/11_TUSAjMjG7hLa53lmyTZ1xox03hNlEA4tRmllFep3I/'
			) );

			$this->all_plugins = get_plugins();

			$this->options = $this->get_related_plugins_info( $this->options );

			if ( $this->is_multisite && ! $this->is_network_options && $network_options = get_site_option( 'cptch_options' ) ) {
				if ( 'all' == $network_options['network_apply'] && 0 == $network_options['network_change'] )
					$this->change_permission_attr = ' readonly="readonly" disabled="disabled"';
				if ( 'all' == $network_options['network_apply'] && 0 == $network_options['network_view'] )
					$this->forbid_view = true;
			}

			$this->forms = array(
				'general'                   => array( 'name' => __( 'General Options', 'captcha-pro' ) ),
				'wp_login'                  => array( 'name' => __( 'Login form', 'captcha-pro' ) ),
				'wp_register'               => array( 'name' => __( 'Registration form', 'captcha-pro' ) ),
				'wp_lost_password'          => array( 'name' => __( 'Reset password form', 'captcha-pro' ) ),
				'wp_comments'               => array( 'name' => __( 'Comments form', 'captcha-pro' ) ),
				'bws_contact'               => array( 'name' => 'Contact Form' ),
				'bws_subscriber'            => array( 'name' => 'Subscriber' ),
				'cf7_contact'               => array( 'name' => 'Contact Form 7' ),
				'buddypress_register'       => array( 'name' => __( 'Registration form', 'captcha-pro' ) ),
				'buddypress_comments'       => array( 'name' => __( 'Comments form', 'captcha-pro' ) ),
				'buddypress_group'          => array( 'name' => __( 'Create a Group form', 'captcha-pro' ) ),
				'woocommerce_login'         => array( 'name' => __( 'Login form', 'captcha-pro' ) ),
				'woocommerce_register'      => array( 'name' => __( 'Registration form', 'captcha-pro' ) ),
				'woocommerce_lost_password' => array( 'name' => __( 'Lost password form', 'captcha-pro' ) ),
				'woocommerce_checkout'      => array( 'name' => __( 'Checkout form', 'captcha-pro' ) )
			);

			/*
			 * Add users forms to the forms lists
			 */
			$user_forms = apply_filters( 'cptch_add_form', array() );
			if ( ! empty( $user_forms ) ) {
				/*
				 * Get default form slugs from defaults
				 * which have been added by hook "cptch_add_default_form" */
				$new_default_forms = array_diff( cptch_get_default_forms(), array_keys( $this->forms ) );
				/*
				 * Remove forms slugs form from the newly added
				 * which have not been added to defaults previously
				 */
				$new_forms = array_intersect( $new_default_forms, array_keys( $user_forms ) );
				/* Get the sub array with new form labels */
				$new_forms_fields = array_intersect_key( $user_forms, array_flip( $new_forms ) );
				$new_forms_fields = array_map( array( $this, 'sanitize_new_form_data' ), $new_forms_fields );
				if ( ! empty( $new_forms_fields ) ) {
					/* Add new forms labels to the registered */
					$this->forms = array_merge( $this->forms, $new_forms_fields );
					/* Add default settings in case if new forms settings have not been saved yet */
					foreach ( $new_forms as $new_form ) {
						if ( empty( $this->options['forms'][ $new_form ] ) )
							$this->options['forms'][ $new_form ] = $this->default_options['forms'][ $new_form ];
					}
				}
			}

			/**
			* form categories are used when compatible plugins are displayed
			*/
			$this->form_categories = array(
				'wp_default' => array(
					'title' => __( 'WordPress default', 'captcha-pro' ),
					'forms' => array(
						'wp_login',
						'wp_register',
						'wp_lost_password',
						'wp_comments'
					)
				),
				'external' => array(
					'title' => __( 'External plugins', 'captcha-pro' ),
					'forms' => array(
						'bws_contact',
						'bws_subscriber',
						'cf7_contact'
					)
				),
				'buddypress' => array(
					'title' => __( 'BuddyPress', 'captcha-pro' ),
					'forms' => array(
						'buddypress_register',
						'buddypress_comments',
						'buddypress_group'
					)
				),
				'woocommerce' => array(
					'title' => __( 'WooCommerce', 'captcha-pro' ),
					'forms' => array(
						'woocommerce_login',
						'woocommerce_register',
						'woocommerce_lost_password',
						'woocommerce_checkout'
					)
				)
			);

			/**
			* create list with default compatible forms
			*/
			$this->registered_forms = array_merge(
				$this->form_categories['wp_default']['forms'],
				$this->form_categories['external']['forms'],
				$this->form_categories['buddypress']['forms'],
				$this->form_categories['woocommerce']['forms']
			);

			$user_forms = array_diff( array_keys( $this->forms ), $this->registered_forms );
			if ( ! empty( $user_forms ) )
				$this->form_categories['external']['forms'] = array_merge( $this->form_categories['external']['forms'], $user_forms );

			/**
			* The option restoring have place later then $this->__constuct
			* so related plugins info will be lost without this add_filter
			*/
			add_filter( get_parent_class( $this ) . '_additional_restore_options', array( $this, 'additional_restore_options' ) );
		}

		/**
		 * Save plugin options to the database
		 * @see    self::display_content();
		 * @access private
		 * @param  void
		 * @return array    The action results
		 */
		public function save_options() {
			global $wpdb;
			$notices = array();

			if ( $this->forbid_view || ! empty( $this->change_permission_attr ) )
				return '';

			/*
			 * Display this notice in order to remind to the user that he need to go on the
			 * Contact Form 7 "edit form" page and insert [BWS_CAPTCHA] shortcode in to the form content
			 */
			if ( ! $this->options['forms']['cf7_contact']['enable'] && isset( $_REQUEST['cptch']['forms']['cf7_contact']['enable'] ) ) {
				$notices[] = sprintf( __( "Option for displaying Captcha with Contact Form 7 is enabled. For correct work, please don't forget to add the BWS CAPTCHA block to the necessary form (see %s).", 'captcha-pro' ), '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200538879" target="_blank">FAQ</a>' );
			}

			/*
			 * Prepare general options
			 */
			$general_arrays  = array( 
				'math_actions'		=> __( 'Arithmetic Actions', 'captcha-pro' ),
				'operand_format'	=> __( 'Complexity', 'captcha-pro' )
			);
			$general_bool    = array( 'load_via_ajax', 'display_reload_button', 'enlarge_images', 'use_several_packages', 'use_limit_attempts_whitelist' );
			$general_strings = array( 'type', 'title', 'required_symbol', 'no_answer', 'wrong_answer', 'time_limit_off', 'time_limit_off_notice', 'whitelist_message' );			

			foreach ( $general_bool as $option ) {
				$this->options[ $option ] = ! empty( $_REQUEST["cptch_{$option}"] );
			}

			foreach ( $general_strings as $option ) {
				$value = isset( $_REQUEST["cptch_{$option}"] ) ? trim( stripslashes( esc_html( $_REQUEST["cptch_{$option}"] ) ) ) : '';

				if ( ! in_array( $option, array( 'title', 'required_symbol' ) ) && empty( $value ) ) {
					/* The index has been added in order to prevent the displaying of this message more than once */
					$notices['a'] = __( 'Text fields in the "Messages" tab must not be empty.', 'captcha-pro' );
				} else {
					$this->options[ $option ] = $value;
				}
			}

			foreach ( $general_arrays as $option => $option_name ) {
				$value = isset( $_REQUEST["cptch_{$option}"] ) && is_array( $_REQUEST["cptch_{$option}"] ) ? array_map( 'esc_html', $_REQUEST["cptch_{$option}"] ) : array();

				/* "Arithmetic actions" and "Complexity" must not be empty */
				if ( empty( $value ) && 'recognition' != $this->options['type'] )
					$notices[] = sprintf( __( '"%s" option must not be fully disabled.', 'captcha-pro' ), $option_name );
				else
					$this->options[ $option ] = $value;
			}

			$this->options['images_count'] = isset( $_REQUEST['cptch_images_count'] ) ? absint( $_REQUEST['cptch_images_count'] ) : 4;
			/*
			 * Prepare forms options
			 */
			$forms = array_keys( $this->forms );
			$form_bool = array( 'enable', 'use_general', 'hide_from_registered', 'enable_time_limit' );
			foreach ( $forms as $form_slug ) {				
				$this->options['forms'][ $form_slug ]['used_packages'] =
						isset( $_REQUEST['cptch']['forms'][ $form_slug ]['used_packages'] ) &&
						is_array( $_REQUEST['cptch']['forms'][ $form_slug ]['used_packages'] )
					?
						array_map( 'absint', $_REQUEST['cptch']['forms'][ $form_slug ]['used_packages'] )
					:
						array();

				$this->options['forms'][ $form_slug ]['time_limit'] =
						isset( $_REQUEST['cptch']['forms'][ $form_slug ]['time_limit'] )
					?
						absint( $_REQUEST['cptch']['forms'][ $form_slug ]['time_limit'] )
					:
						120;

				foreach ( $form_bool as $option ) {
					/* For General Options "Enlarge" and "Use general" form options always must be set to 'true' */
					$this->options['forms'][ $form_slug ][ $option ] =
							'general' == $form_slug && 'enable_time_limit' != $option
						?
							true
						:
							isset( $_REQUEST['cptch']['forms'][ $form_slug ][ $option ] );
				}
			}

			/*
			 * If the user has selected images for the CAPTCHA
			 * it is necessary that at least one of the images packages was selected on the General Options tab
			 */
			if (
				( $this->images_enabled() || 'recognition' == $this->options['type'] ) &&
				empty( $this->options['forms']['general']['used_packages'] )
			) {
				if ( 'recognition' == $this->options['type'] ) {
					$notices[] = __( 'In order to use "Optical Character Recognition" type, please select at least one of the items in the option "Image Packages".', 'captcha-pro' );
					$this->options['type'] = 'math_actions';
				} else {
					$notices[] = __( 'In order to use images in the CAPTCHA, please select at least one of the items in the option "Image Packages". The "Images" checkbox in "Complexity" option has been disabled.', 'captcha-pro' );
				}
				$key = array_keys( $this->options['operand_format'], 'images' );
				unset( $this->options['operand_format'][$key[0]] );
				if ( empty( $this->options['operand_format'] ) )
					$this->options['operand_format'] = array( 'numbers', 'words' );
			}

			/*
			 * Update plugin option in the database
			 */
			if ( $this->is_network_options ) {
				if ( 'all' == $_REQUEST['cptch_network_apply'] ) {
					$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
					$old_blog = $wpdb->blogid;
					foreach ( $blogids as $blog_id ) {
						switch_to_blog( $blog_id );
						if ( $old_options = get_option( 'cptch_options' ) ) {
							$blog_options = array_merge( $old_options, $this->options );
							update_option( 'cptch_options', $blog_options );
						} else {
							add_option( 'cptch_options', array_merge( $this->default_options, $this->options ) );
						}
					}
					switch_to_blog( $old_blog );
				}
				$this->options['network_apply']  = esc_html( $_REQUEST['cptch_network_apply'] );
				$this->options['network_view']   = isset( $_REQUEST['cptch_network_view'] ) ? 1 : 0;
				$this->options['network_change'] = isset( $_REQUEST['cptch_network_change'] ) ? 1 : 0;
				update_site_option( 'cptch_options', $this->options );
			} else {
				update_option( 'cptch_options', $this->options );
			}

			$notice  = implode( '<br />', $notices );
			$message = __( "Settings saved.", 'captcha-pro' );

			return compact( 'message', 'notice' );
		}

		/**
		 * Displays 'settings' menu-tab
 		 * @access public
		 * @param void
		 * @return void
		 */
		public function tab_settings() { 
			$options = array(
				'type' => array(
					'type'             => 'radio',
					'title'            => __( 'Captcha Type', 'captcha-pro' ),
					'array_options'    => array(
						'math_actions'   => array( __( 'Arithmetic actions', 'captcha-pro' ) ),
						'recognition'    => array( __( 'Optical Character Recognition (OCR)', 'captcha-pro' ) ),
						'invisible'    	 => array( __( 'Invisible', 'captcha-pro' ) )
					)
				),
				'math_actions' => array(
					'type'          => 'checkbox',
					'title'         => __( 'Arithmetic Actions', 'captcha-pro' ),
					'array_options' => array(
						'plus'            => array( __( 'Addition', 'captcha-pro' ) . '&nbsp;(+)' ),
						'minus'           => array( __( 'Subtruction', 'captcha-pro' ) . '&nbsp;(-)' ),
						'multiplications' => array( __( 'Multiplication', 'captcha-pro' ) . '&nbsp;(x)' )
					),
					'class'         => 'cptch_for_math_actions'
				),
				'operand_format' => array(
					'type'              => 'checkbox',
					'title'             => __( 'Complexity', 'captcha-pro' ),
					'array_options'     => array(
						'numbers' => array( __( 'Numbers (1, 2, 3, etc.)', 'captcha-pro' ) ),
						'words'   => array( __( 'Words (one, two, three, etc.)', 'captcha-pro' ) ),
						'images'  => array( __( 'Images', 'captcha-pro' ) )
					),
					'class'             => 'cptch_for_math_actions'
				),
				'images_count' => array(
					'type'              => 'number',
					'title'             => __( 'Number of Images', 'captcha-pro' ),
					'min'               => 1,
					'max'               => 10,
					'block_description' => __( 'Set a number of images to display simultaneously as a captcha question.', 'captcha-pro' ),
					'class'             => 'cptch_for_recognition'
				),
				'use_limit_attempts_whitelist' => array(
					'type'              => 'radio',
					'title'             => __( 'Whitelist', 'captcha-pro' ),
					'block_description' => __( 'With a whitelist you can hide captcha field for your personal and trusted IP addresses.', 'captcha-pro' ),
					'array_options'     => array(
						'0' => array( __( 'Default', 'captcha-pro' ) ),
						'1' => array( __( 'Limit Attempts', 'captcha-pro' ) . ' ' . $this->get_form_message( 'limit_attempts' ) ),
					)
				),
				'used_packages' => array(
					'type'  => 'pack_list',
					'title' => __( 'Image Packages', 'captcha-pro' ),
					'class' => 'cptch_images_options cptch_for_math_actions cptch_for_recognition'
				),
				'use_several_packages' => array(
					'type'   => 'checkbox',
					'title' => __( 'Use several image packages at the same time', 'captcha-pro' ),
					'class' => 'cptch_images_options cptch_enable_to_use_several_packages cptch_for_math_actions cptch_for_recognition'
				),
				'enlarge_images' => array(
					'type'               => 'checkbox',
					'title'              => __( 'Enlarge Images', 'captcha-pro' ),
					'inline_description' => __( 'Enable to enlarge captcha images on mouseover.', 'captcha-pro' ),
					'class'              => 'cptch_images_options cptch_for_math_actions cptch_for_recognition'
				),
				'display_reload_button' => array(
					'type'               => 'checkbox',
					'title'              => __( 'Reload Button', 'captcha-pro' ),
					'inline_description' => __( 'Enable to display reload button for captcha.', 'captcha-pro' ),
					'class'              => 'cptch_for_math_actions cptch_for_recognition'
				),
				'title' => array(
					'type'  => 'text',
					'title' => __( 'Captcha Title', 'captcha-pro' ) ),
				'required_symbol' => array(
					'type'  => 'text',
					'title' => __( 'Required Symbol', 'captcha-pro' ) ),
				'load_via_ajax' => array(
					'type'               => 'checkbox',
					'title'              => __( 'Advanced Protection', 'captcha-pro' ),
					'inline_description' => __( 'Enable to display captcha when the website page is loaded.', 'captcha-pro' ),
					'class'              => 'cptch_for_math_actions cptch_for_recognition'
				)
			); ?>
			<h3 class="bws_tab_label"><?php _e( 'Captcha Settings', 'captcha-pro' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<?php if ( $this->forbid_view ) { ?>
				<div class="error inline bws_visible"><p><strong><?php _e( "Notice:", 'captcha-pro' ); ?></strong> <strong><?php _e( "It is prohibited to view Captcha Pro settings on this site in the Captcha Pro network settings.", 'captcha-pro' ); ?></strong></p></div>
			<?php } else { ?>
				<div class="bws_tab_sub_label"><?php _e( 'General', 'captcha-pro' ); ?></div>
				<?php if ( ! empty( $this->change_permission_attr ) ) { ?>
					<div class="error inline bws_visible"><p><strong><?php _e( "Notice:", 'captcha-pro' ); ?></strong> <strong><?php _e( "It is prohibited to change Captcha Pro settings on this site in the Captcha Pro network settings.", 'captcha-pro' ); ?></strong></p></div>
				<?php } ?>
				<table class="form-table">
					<?php if ( $this->is_network_options ) { ?>
						<tr valign="top" class="captch_network_settings">
							<th scope="row"><?php _e( 'Apply network settings', 'captcha-pro' ); ?></th>
							<td>
								<fieldset>
									<label><input<?php echo $this->change_permission_attr; ?>  type="radio" name="cptch_network_apply" value="all" <?php if ( "all" == $this->options['network_apply'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Apply to all sites and use by default', 'captcha-pro' ); ?> <span class="bws_info">(<?php _e( 'All current settings on separate sites will be replaced', 'captcha-pro' ); ?>)</span></label><br />
									<div class="bws_network_apply_all">
										<label><input<?php echo $this->change_permission_attr; ?>  type="checkbox" name="cptch_network_change" value="1" <?php if ( 1 == $this->options['network_change'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Allow changing the settings on separate websites', 'captcha-pro' ); ?></label><br />
										<label><input<?php echo $this->change_permission_attr; ?>  type="checkbox" name="cptch_network_view" value="1" <?php if ( 1 == $this->options['network_view'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Allow viewing the settings on separate websites', 'captcha-pro' ); ?></label><br />
									</div>
									<label><input<?php echo $this->change_permission_attr; ?>  type="radio" name="cptch_network_apply" value="default" <?php if ( "default" == $this->options['network_apply'] ) echo 'checked="checked"'; ?> /> <?php _e( 'By default', 'captcha-pro' ); ?> <span class="bws_info">(<?php _e( 'Settings will be applied to newly added websites by default', 'captcha-pro' ); ?>)</span></label><br />
									<label><input<?php echo $this->change_permission_attr; ?>  type="radio" name="cptch_network_apply" value="off" <?php if ( "off" == $this->options['network_apply'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Do not apply', 'captcha-pro' ); ?> <span class="bws_info">(<?php _e( 'Change the settings on separate sites of the multisite only', 'captcha-pro' ); ?>)</span></label>
								</fieldset>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<th scope="row"><?php _e( 'Enable Captcha for', 'captcha-pro' ); ?></th>
						<td>
							<?php foreach ( $this->form_categories as $fieldset_name => $fieldset_data ) { ?>
								<p>
									<i><?php echo $fieldset_data['title']; ?></i>
									<?php if (
										( 'buddypress' == $fieldset_name || 'woocommerce' == $fieldset_name ) &&
										'active' != $this->options['related_plugins_info'][ $fieldset_name ]['status']
									) {
										echo $this->get_form_message( $fieldset_name ); /* show "instal/activate" mesage */
									} ?>
								</p>
								<br>
								<fieldset id="<?php echo $fieldset_name; ?>">
									<?php /**
									* Get each form from current category
									*/
									foreach ( $fieldset_data['forms'] as $form_name ) {
										if ( 'general' == $form_name )
											continue;
										/**
										* if plugin is external and it is not active, it's checkbox should be disabled
										*/
										$disabled = in_array( $form_name, $this->registered_forms ) && (
												( isset( $this->options['related_plugins_info'][ $form_name ] ) &&
												'active' != $this->options['related_plugins_info'][ $form_name ]['status'] ) ||
												( isset( $this->options['related_plugins_info'][ $fieldset_name ] ) &&
												'active' != $this->options['related_plugins_info'][ $fieldset_name ]['status'] )
											); ?>
										<label class="cptch_related_form">
											<?php $value = $fieldset_name . '_' . $form_name;
											$id = 'cptch_' . $form_name . '_enable';
											$name = 'cptch[forms][' . $form_name . '][enable]';
											$checked = !! $this->options['forms'][ $form_name ]['enable'];
											$this->add_checkbox_input( compact( 'id', 'name', 'checked', 'value', 'disabled' ) );

											echo $this->forms[ $form_name ]['name'];

											if ( 'external' == $fieldset_name && $disabled ) {
												echo $this->get_form_message( $form_name ); /* show "instal/activate" mesage */
											} elseif ( 'bws_contact' == $form_name &&
												( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) ||
												is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) ) ) { ?>
												<span class="bws_info"> <?php _e( 'Enable to add the CAPTCHA to forms on their settings pages.', 'captcha-pro' ); ?></span>
											<?php } ?>
										</label>
										<br />
									<?php } ?>
								</fieldset>
								<hr>
							<?php } ?>
						</td>
					</tr>
					<?php foreach ( $options as $key => $data ) { ?>
						<tr<?php if ( ! empty( $data['class'] ) ) echo ' class="' . $data['class'] . '"'; ?>>
							<th scope="row"><?php echo ucwords( $data['title'] ); ?></th>
							<td>
								<fieldset>
									<?php $func = "add_{$data['type']}_input";
									if ( isset( $data['array_options'] ) ) {
										$name = 'radio' == $data['type'] ? 'cptch_' . $key : 'cptch_' . $key . '[]';
										foreach ( $data['array_options'] as $slug => $sub_data ) {
											$id = "cptch_{$key}_{$slug}"; ?>
											<label for="<?php echo $id; ?>">
												<?php if (
													'use_limit_attempts_whitelist' == $key &&
													$slug &&
													'active' != $this->options['related_plugins_info']['limit_attempts']['status']
												) { ?>
													<input type="radio" id="<?php echo $id . '" ' . 'name="' . $name . '" disabled="disabled"'; ?> />
												<?php } else {
													$checked = 'radio' == $data['type'] ? ( $slug == $this->options[ $key ] ) : in_array( $slug, $this->options[ $key ] );
													$value   = $slug;
													$this->$func( compact( 'id', 'name', 'value', 'checked' ) );
												}
												echo $sub_data[0]; ?>
											</label>
											<br />
										<?php }
									} else {
										$id = isset( $data['array_options'] ) ? '' : ( isset( $this->options[ $key ] ) ? "cptch_{$key}" : "cptch_form_general_{$key}" );
										if ( isset( $this->options[ $key ] ) ) {
											$name    = $id;
											$value   = $this->options[ $key ];
										} else {
											$name    = "cptch[forms][general][{$key}]";
											$value   = $this->options['forms']['general'][ $key ];
										}
										$checked = !! $value;
										if ( 'used_packages' == $key ) {
											$open_tag = $close_tag = "";
										} else {
											$open_tag = "<label for=\"{$id}\">";
											$close_tag = "</label>";
										}
										if ( isset( $data['min'] ) )
											$min = $data['min'];
										if ( isset( $data['max'] ) )
											$max = $data['max'];
										echo $open_tag;
										$this->$func( compact( 'id', 'name', 'value', 'checked', 'min', 'max' ) );
										echo $close_tag;
										if ( isset( $data['inline_description'] ) ) { ?>
											<span class="bws_info"><?php echo $data['inline_description']; ?></span>
										<?php }
									} ?>
								</fieldset>
								<?php if ( isset( $data['block_description'] ) ) { ?>
									<span class="bws_info"><?php echo $data['block_description']; ?></span>
								<?php } ?>
							</td>
						</tr>
					<?php }
					$this->display_time_limit_option( 'general' ); ?>
				</table>
				<?php foreach ( $this->forms as $form_slug => $data ) {
					if ( 'general' == $form_slug )
						continue;

					foreach ( $this->form_categories as $category_name => $category_data ) {
						if ( in_array( $form_slug, $category_data['forms'] ) ) {
							if ( 'wp_default' == $category_name )
								$category_title = 'WordPress - ';
							elseif ( 'external' == $category_name )
								$category_title = '';
							else
								$category_title = $category_data['title'] . ' - ';
							break;
						}
					} ?>
					<div class="cptch_<?php echo $form_slug; ?>_related_form bws_tab_sub_label"><?php echo $category_title . $data['name']; ?></div>
					<table class="form-table cptch_<?php echo $form_slug; ?>_related_form cptch_related_form_bloc">
						<?php $plugin = cptch_get_plugin( $form_slug );

						if ( ! empty( $plugin ) ) {

							/* Don't display form options if there is to old plugin version */
							if (
								 'not_installed' != $this->options['related_plugins_info'][ $plugin ]['status'] &&
								! $this->options['related_plugins_info'][ $plugin ]['compatible']
							) {
								$link        = $this->options['related_plugins_info'][ $plugin ]['link'];
								$plugin_name = $this->options['related_plugins_info'][ $plugin ]['plugin_info']['Name'];
								$recommended = __( 'update', 'captcha-pro' );
								$to_current  = __( 'to the current version', 'captcha-pro' );
							/* Don't display form options for deactivated or not installed plugins */
							} else {
								switch ( $this->options['related_plugins_info'][ $plugin ]['status'] ) {
									case 'not_installed':
										$link        = $this->options['related_plugins_info'][ $plugin ]['link'];
										$plugin_name = cptch_get_plugin_name( $plugin );
										$recommended = __( 'install', 'captcha-pro' );
										break;
									case 'deactivated':
										$link        = self_admin_url( '/plugins.php' );
										$plugin_name = $this->options['related_plugins_info'][ $plugin ]['plugin_info']['Name'];
										$recommended = __( 'activate', 'captcha-pro' );
										break;
									default:
										break;
								}
							}
							if ( ! empty( $recommended ) ) { ?>
									<tr>
										<td colspan="2">
											<?php echo __( 'You should', 'captcha' ) .
												"&nbsp;<a href=\"{$link}\" target=\"_blank\">{$recommended}&nbsp;{$plugin_name}</a>&nbsp;" .
												( empty( $to_current ) ? '' : $to_current . '&nbsp;' ) .
												__( 'to use this functionality.', 'captcha' ); ?>
										</td>
									</tr>
								</table>
								<?php unset( $recommended );
								continue;
							}
						}

						$options = array(
							'hide_from_registered' => array( 'checkbox',  __( 'Hide from Registered Users', 'captcha-pro' ) ),
							'use_general'          => array( 'checkbox',  __( 'General Settings', 'captcha-pro' ), __( 'Enable to use general captcha settings.', 'captcha-pro' ) ),
							'used_packages'        => array( 'pack_list', __( 'Image Packages', 'captcha-pro' ) )
						);
						$break = false;

						foreach ( $options as $key => $data ) {

							if ( 'hide_from_registered' == $key && preg_match( '/(general)|(login)|(register)|(password)|(buddypress)/', $form_slug ) )
								continue;

							$id           = "cptch_form_{$form_slug}_{$key}";
							$name         = "cptch[forms][{$form_slug}][{$key}]";
							$checked      = ! empty( $this->options['forms'][ $form_slug ][ $key ] );
							$style        = $info = '';

							/* Multisite uses common "register" and "lostpassword" forms all sub-sites */
							if (
								$this->is_multisite &&
								in_array( $form_slug, array( 'wp_register', 'wp_lost_password', 'buddypress_register' ) ) &&
								! in_array( get_current_blog_id(), array( 0, 1 ) )
							) {
								$break    = true;
								$info     = __( 'This option is available only for network or for main blog.', 'captcha-pro' );
							/*
							 * Hide option rows if displaying of the CAPTCHA for the currrent form is disabled
							 * or if the CAPTCHA uses general settings for the current form.
							 * "Use general settings" option will by hidden if displaying of the CAPTCHA for the currrent form is disabled only.
							 */
							} elseif (
								/* Hide other form options if the "Enable" option is disabled or the "Use general settings" option  is enabled */
								(
									'use_general' != $key &&
									'hide_from_registered' != $key &&
									! empty( $this->options['forms'][ $form_slug ]['use_general'] )
								)
							) {
								$style = ' style="display: none;"';
							} ?>
							<tr <?php echo $style; ?> class="cptch_form_option_<?php echo $key; ?>">
								<th scope="row"><label for="<?php echo $id; ?>"><?php echo $data[1]; ?></label></th>
								<td>
									<fieldset>
										<?php $func = "add_{$data[0]}_input";
										if ( empty( $this->options['forms'][ $form_slug ][ $key ] ) )  {
											$this->options['forms'][ $form_slug ][ $key ] = array();
										}

										$value = in_array( $key, array( 'used_packages', 'time_limit' ) ) ? $this->options['forms'][ $form_slug ][ $key ] : '';									
										$this->$func( compact( 'id', 'name', 'value', 'checked', 'readonly' ) );
										if ( 'used_packages' == $key ) {
											$style = ! empty( $value ) && ! $this->images_enabled() ? '' : ' style="display: none;"'; ?>
											<div class="bws_info cptch_enable_images_notice"<?php echo $style; ?>>
												<?php _e( 'To display images from selected packages, please enable "Images" in the "Complexity" block in "General" block.', 'captcha-pro' ); ?>
											</div>
										<?php } elseif ( ! empty( $info ) ) { ?>
											<span class="bws_info"><?php echo $info; ?></span>
										<?php } 
										if ( ! empty( $data[2] ) ) { ?>
											<span class="bws_info"><?php echo $data[2]; ?></span>
										<?php } ?>
									</fieldset>
								</td>
							</tr>
							<?php if ( $break )
								break;
						}

						if ( ! $break )
							$this->display_time_limit_option( $form_slug ); ?>
					</table>
				<?php }
			}
		}

		/**
		 * Displays 'messages' menu-tab
		 * @access public
		 * @param void
		 * @return void
		 */
		public function tab_messages() { ?>
			<h3 class="bws_tab_label"><?php _e( 'Messages Settings', 'captcha-pro' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<?php if ( $this->forbid_view ) { ?>
				<div class="error inline bws_visible"><p><strong><?php _e( "Notice:", 'captcha-pro' ); ?></strong> <strong><?php _e( "It is prohibited to view Captcha Pro settings on this site in the Captcha Pro network settings.", 'captcha-pro' ); ?></strong></p></div>
			<?php } else { ?>
				<div class="bws_tab_sub_label"><?php _e( 'General', 'captcha-pro' ); ?></div>
				<?php if ( ! empty( $this->change_permission_attr ) ) { ?>
					<div class="error inline bws_visible"><p><strong><?php _e( "Notice:", 'captcha-pro' ); ?></strong> <strong><?php _e( "It is prohibited to change Captcha Pro settings on this site in the Captcha Pro network settings.", 'captcha-pro' ); ?></strong></p></div>
				<?php } ?>
				<table class="form-table">
					<?php $messages = array(
						'no_answer'             => array(
							'title'         => __( 'Captcha Field is Empty', 'captcha-pro' ),
							'message'       => __( 'Please complete the captcha.', 'captcha-pro' )
						),
						'wrong_answer'             => array(
							'title'         => __( 'Captcha is Incorrect', 'captcha-pro' ),
							'message'       => __( 'Please enter correct captcha value.', 'captcha-pro' )
						),
						'time_limit_off'             => array(
							'title'         => __( 'Time Limit Exceeded', 'captcha-pro' ),
							'message'       => __( 'Time limit exceeded. Please complete the captcha once again.', 'captcha-pro' ),
						),
						'time_limit_off_notice' 	=> array(
							'title'         => __( 'Time Limit Exceeded', 'captcha-pro' ),
							'message'       => __( 'Time limit exceeded. Please complete the captcha once again.', 'captcha-pro' ),
							'description'   => __( 'This message will be displayed above the captcha field.', 'captcha-pro' )
						),
						'whitelist_message'             => array(
							'title'         => __( 'Whitelisted IP', 'captcha-pro' ),
							'message'       => __( 'Your IP address is Whitelisted.', 'captcha-pro' ),
							'description'   => __( 'This message will be displayed instead of the captcha field.', 'captcha-pro' )
						)
					);

					foreach ( $messages as $message_name => $data ) { ?>
						<tr>
							<th scope="row"><?php echo $data['title']; ?></th>
							<td>
								<textarea <?php echo 'id="cptch_' . $message_name . '" name="cptch_' . $message_name . '" ' . $this->change_permission_attr; ?>><?php echo trim( $this->options[ $message_name ] ); ?></textarea>
								<?php if ( isset( $data['description'] ) ) { ?>
									<div class="bws_info"><?php echo $data['description']; ?></div>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			<?php }
		}

		/**
		 * Displays the HTML radiobutton with the specified attributes
		 * @access private
		 * @param  array  $args   An array of HTML attributes
		 * @return void
		 */
		private function add_radio_input( $args ) { ?>
			<input
				type="radio"
				<?php echo $this->change_permission_attr; ?>
				id="<?php echo $args['id']; ?>"
				name="<?php echo $args['name']; ?>"
				value="<?php echo $args['value']; ?>"
				<?php echo $args['checked'] ? ' checked="checked"' : ''; ?>/>
		<?php }

		/**
		 * Displays the HTML checkbox with the specified attributes
		 * @access private
		 * @param  array  $args   An array of HTML attributes
		 * @return void
		 */
		private function add_checkbox_input( $args ) { ?>
			<input
				type="checkbox"
				<?php echo $this->change_permission_attr; ?>
				id="<?php echo $args['id']; ?>"
				name="<?php echo $args['name']; ?>"
				value="<?php echo ! empty( $args['value'] ) ? $args['value'] : 1; ?>"
				<?php echo $args['checked'] ? ' checked="checked" ' : ' ';
				echo ( isset( $args['disabled'] ) && $args['disabled'] ) ? 'disabled="disabled"' : ''; ?> />
		<?php }

		/**
		 * Displays the HTML number field with the specified attributes
		 * @access private
		 * @param  array  $args   An array of HTML attributes
		 * @return void
		 */
		private function add_number_input( $args ) { ?>
			<input
				type="number"
				<?php echo $this->change_permission_attr; ?>
				step="1"
				min="<?php echo $args['min']; ?>"
				max="<?php echo $args['max']; ?>"
				id="<?php echo $args['id']; ?>"
				name="<?php echo $args['name']; ?>"
				value="<?php echo $args['value']; ?>" />
		<?php }

		/**
		 * Displays the HTML text field with the specified attributes
		 * @access private
		 * @param  array  $args   An array of HTML attributes
		 * @return void
		 */
		private function add_text_input( $args ) { ?>
			<input
				type="text"
				<?php echo $this->change_permission_attr; ?>
				id="<?php echo $args['id']; ?>"
				name="<?php echo $args['name']; ?>"
				value="<?php echo $args['value']; ?>" />
		<?php }

		/**
		 * Displays the list of available package list on the form options tabs
		 * @access private
		 * @param  array  $args   An array of HTML attributes
		 * @return boolean
		 */
		private function add_pack_list_input( $args ) {

			if ( empty( $args['value'] ) ) {
				$args['value'] = array();
			}

			if ( empty( $this->package_list ) ) {
				global $wpdb;
				$this->package_list = $wpdb->get_results(
					"SELECT
						`{$wpdb->base_prefix}cptch_packages`.`id`,
						`{$wpdb->base_prefix}cptch_packages`.`name`,
						`{$wpdb->base_prefix}cptch_packages`.`folder`,
						`{$wpdb->base_prefix}cptch_packages`.`settings`,
						`{$wpdb->base_prefix}cptch_images`.`name` AS `image`
					FROM
						`{$wpdb->base_prefix}cptch_packages`
					LEFT JOIN
						`{$wpdb->base_prefix}cptch_images`
					ON
						`{$wpdb->base_prefix}cptch_images`.`package_id`=`{$wpdb->base_prefix}cptch_packages`.`id`
					GROUP BY `{$wpdb->base_prefix}cptch_packages`.`id`
					ORDER BY `name` ASC;",
					ARRAY_A
				);
			}

			if ( empty( $this->package_list ) ) { ?>
				<span><?php _e( 'The image packages list is empty. Please restore default settings or re-install the plugin to fix this error.', 'captcha-pro' ); ?></span>
				<?php return false;
			}

			if ( $this->is_multisite ) {
				switch_to_blog( 1 );
				$upload_dir = wp_upload_dir();
				restore_current_blog();
			} else {
				$upload_dir = wp_upload_dir();
			}
			$packages_url = $upload_dir['baseurl'] . '/bws_captcha_images'; ?>
			<div class="cptch_tabs_package_list">
				<ul class="cptch_tabs_package_list_items">
					<?php foreach ( $this->package_list as $pack ) {
						$styles = '';
						if ( ! empty( $pack['settings'] ) ) {
							$settings = unserialize( $pack['settings'] );
							if ( is_array( $settings ) ) {
								$styles = ' style="';
								foreach ( $settings as $propery => $value )
									$styles .= "{$propery}: {$value};";
								$styles .= '"';
							}
						}
						$id       = "{$args['id']}_{$pack['id']}";
						$name     = "{$args['name']}[]";
						$value    = $pack['id'];
						$checked  = in_array( $pack['id'], $args['value'] ); ?>
						<li>
							<span><?php $this->add_checkbox_input( compact( 'id', 'name', 'value', 'checked' ) ); ?></span>
							<span><label for="<?php echo $id; ?>"><img src="<?php echo "{$packages_url}/{$pack['folder']}/{$pack['image']}"; ?>" title="<?php echo $pack['name'] . '" ' . $styles; ?>/></label></span>
							<span><label for="<?php echo $id . '">' . $pack['name']; ?></label></span>
						</li>
					<?php } ?>
				</ul>
			</div>
			<?php return true;
		}

		/**
		 * Displays the content of time limit option for each form
		 * @access private
		 * @param  void
		 * @return void
		 */
		private function display_time_limit_option( $form_slug ) {
			$style =
					'general' != $form_slug &&
					(
						! $this->options['forms'][ $form_slug ]['enable'] ||
						$this->options['forms'][ $form_slug ]['use_general']
					)
				?
					' style="display: none;"'
				:
					'';
			if ( empty( $this->options['forms'][ $form_slug ]['enable_time_limit'] ) ) {
				$this->options['forms'][ $form_slug ]['enable_time_limit'] = array();
			}
			if ( empty( $this->options['forms'][ $form_slug ]['time_limit'] ) ) {
				$this->options['forms'][ $form_slug ]['time_limit'] = false;
			}
			$options = array(
				array(
					'id'                 => "cptch_form_{$form_slug}_enable_time_limit",
					'name'               => "cptch[forms][{$form_slug}][enable_time_limit]",
					'checked'            => $this->options['forms'][ $form_slug ]['enable_time_limit'],
					'inline_description' => __( 'Enable to activate a time limit requeired to complete captcha.', 'captcha-pro' )
				),
				array(
					'id'      => "cptch_form_{$form_slug}_time_limit",
					'name'    => "cptch[forms][{$form_slug}][time_limit]",
					'value'   => $this->options['forms'][ $form_slug ]['time_limit'],
					'min'     => 10,
					'max'     => 9999
				)
			); ?>
			<tr <?php echo $style; ?>>
				<th scope="row"><?php _e( 'Time Limit', 'captcha-pro' ); ?></th>
				<td>
					<?php $this->add_checkbox_input( $options[0] ); ?>
					<span class="bws_info"><?php echo $options[0]['inline_description']; ?></span>
				</td>
			</tr>
			<tr <?php echo $style; ?> class="cptch_time_limit" <?php echo $options[0]['checked'] ? '' : ' style="display: none"'; ?>>
				<th scope="row"><?php _e( 'Time Limit Thershold', 'captcha-pro' ); ?></th>
				<td>
					<span class="cptch_time_limit">
						<?php $this->add_number_input( $options[1] ); echo '&nbsp;' . _e( 'sec', 'captcha-pro' ); ?>
					</span>
				</td>
			</tr>
		<?php }

		/**
		 * Displays messages 'insall now'/'activate' for not active plugins
		 * @param  string $status
		 * @return string
		 */
		private function get_form_message( $slug ) {
			switch ( $this->options['related_plugins_info'][ $slug ]['status'] ) {
				case 'deactivated':
					return ' <a href="plugins.php">' . __( 'Activate', 'captcha-pro' ) . '</a>';
				case 'not_installed':
					return ' <a href="' . $this->options['related_plugins_info'][ $slug ]['link'] . '" target="_blank">' . __( 'Install Now', 'captcha-pro' ) . '</a>';
				default:
					return '';
			}
		}

		/**
		 * Form data from the user call function for the "cptch_add_form_tab" hook
		 * @access private
		 * @param  string|array   $form_data   Each new form data
		 * @return array                       Sanitized label
		 */
		private function sanitize_new_form_data( $form_data ) {
			$form_data = (array)$form_data;
			/**
			 * Return an array with the one element only
			 * to prevent the processing of potentially dangerous data
			 * @see self::_construct()
			 */
			return array( 'name' => esc_html( trim( $form_data[0] ) ) );
		}

		/**
		 * Whether the images are enabled for the CAPTCHA
		 * @access private
		 * @param  void
		 * @return boolean
		 */
		private function images_enabled() {
			return in_array( 'images', $this->options['operand_format'] );
		}

		/**
		 * Custom functions for "Restore plugin options to defaults"
		 * @access public
		 */
		public function additional_restore_options( $default_options ) {
			$default_options = $this->get_related_plugins_info( $default_options );

			/* do not update package selection */
			$default_options['forms']['general']['used_packages'] = $this->options['forms']['general']['used_packages'];

			return $default_options;
		}

		/**
		 * Using for adding related plugin's info during the restoring or creating this class
		 * @access public
		 * @param  array
		 * @return array
		 */
		public function get_related_plugins_info( $options ) {
			/**
			* default compatible plugins
			*/
			$compatible_plugins = array(
				'bws_contact'    => array( 'contact-form-plugin/contact_form.php', 'contact-form-pro/contact_form_pro.php' ),
				'bws_subscriber' => array( 'subscriber/subscriber.php', 'subscriber-pro/subscriber-pro.php' ),
				'buddypress'     => 'buddypress/bp-loader.php',
				'cf7_contact'    => 'contact-form-7/wp-contact-form-7.php',
				'woocommerce'    => 'woocommerce/woocommerce.php',
				'limit_attempts' => array( 'limit-attempts/limit-attempts.php', 'limit-attempts-pro/limit-attempts-pro.php' )
			);

			foreach ( $compatible_plugins as $plugin_slug => $plugin )
				$options['related_plugins_info'][ $plugin_slug ] = cptch_get_plugin_status( $plugin, $this->all_plugins, $this->is_network_options );

			return $options;
		}
	}
}