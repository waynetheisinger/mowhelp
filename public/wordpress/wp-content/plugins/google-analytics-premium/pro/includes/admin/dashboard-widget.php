<?php
/**
 * The PRO instance of the Dashboard widget.
 *
 * @since 7.1
 *
 * @package MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MonsterInsights_Dashboard_Widget
 */
class MonsterInsights_Dashboard_Widget_Pro {

	const WIDGET_KEY = 'monsterinsights_reports_widget';
	/**
	 * The default options for the widget.
	 *
	 * @var array $default_options
	 */
	public static $default_options = array(
		'width'    => 'regular',
		'interval' => '30',
		'reports'  => array(
			'overview'  => array(
				'toppages'    => true,
				'newvsreturn' => true,
				'devices'     => true,
			),
			'publisher' => array(
				'landingpages'   => false,
				'exitpages'      => false,
				'outboundlinks'  => false,
				'affiliatelinks' => false,
				'downloadlinks'  => false,
			),
			'ecommerce' => array(
				'infobox'     => false, // E-commerce Overview.
				'products'    => false, // Top Products.
				'conversions' => false, // Top Products.
				'addremove'   => false, // Total Add/Remove.
				'days'        => false, // Time to purchase.
				'sessions'    => false, // Sessions to purchase.
			),
		),
	);

	/**
	 * MonsterInsights_Dashboard_Widget_Pro constructor.
	 */
	public function __construct() {

		// Allow dashboard widget to be hidden on multisite installs.
		$show_widget = is_multisite() ? apply_filters( 'monsterinsights_show_dashboard_widget', true ) : true;
		if ( ! $show_widget ) {
			return false;
		}

		// Check if reports should be visible.
		$dashboards_disabled = monsterinsights_get_option( 'dashboards_disabled', false );
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) || $dashboards_disabled ) {
			return false;
		}

		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'widget_scripts' ), 1000 );

		add_action( 'wp_ajax_monsterinsights_save_widget_state', array( $this, 'save_widget_state' ) );

		add_action( 'wp_ajax_monsterinsights_get_dashboard_widget_report', array( $this, 'get_report_data' ) );
	}

	/**
	 * Register the dashboard widget.
	 */
	public function register_dashboard_widget() {
		global $wp_meta_boxes;

		wp_add_dashboard_widget(
			self::WIDGET_KEY,
			esc_html__( 'MonsterInsights', 'ga-premium' ),
			array( $this, 'dashboard_widget_content' )
		);

		// Attept to place the widget at the top.
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$widget_instance  = array( self::WIDGET_KEY => $normal_dashboard[ self::WIDGET_KEY ] );
		unset( $normal_dashboard[ self::WIDGET_KEY ] );
		$sorted_dashboard                             = array_merge( $widget_instance, $normal_dashboard );
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	/**
	 * Load the widget content.
	 */
	public function dashboard_widget_content() {

		$is_authed = ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() );

		if ( ! $is_authed ) {
			$this->widget_content_no_auth();
		} else {
			$options = $this->get_options();

			if ( isset( $options['reports'] ) && is_array( $options['reports'] ) ) {
				$datepicker_options = array( 30, 7 );
				?>
				<div class="mi-dw-controls">
					<div class="mi-dw-datepicker mi-dw-btn-group" data-type="datepicker">
						<button class="mi-dw-btn-group-label">
							<?php
							// Translators: %d is the number of days.
							printf( esc_html__( 'Last %d days', 'ga-premium' ), absint( $options['interval'] ) );
							?>
						</button>
						<div class="mi-dw-btn-list">
							<?php foreach ( $datepicker_options as $datepicker_option ) { ?>
								<button class="mi-dw-btn <?php echo intval( $options['interval'] ) === $datepicker_option ? 'selected' : ''; ?>" data-value=" <?php echo esc_attr( $datepicker_option ); ?>">
									<?php
									// Translators: %d is the number of days.
									printf( esc_html__( 'Last %d days', 'ga-premium' ), esc_attr( $datepicker_option ) );
									?>
								</button>
							<?php } ?>
						</div>
					</div>
					<label class="mi-dw-styled-toggle mi-dw-widget-width-toggle-container" title="<?php esc_attr_e( 'Show in full-width mode', 'ga-premium' ); ?>">
						<input type="checkbox" class="mi-dw-widget-width-toggle"/>
					</label>
					<div class="mi-dw-dropdown">
						<button class="mi-dw-button-cog mi-dw-dropdown-toggle" data-target="#mi-dw-reports-options" type="button"></button>
						<ul class="mi-dw-reports-options" id="mi-dw-reports-options"></ul>
					</div>
					<img class="mi-dw-mascot" src="<?php echo esc_url( plugins_url( 'assets/css/images/mascot.png', MONSTERINSIGHTS_PLUGIN_FILE ) ); ?>" srcset="<?php echo esc_url( plugins_url( 'assets/css/images/mascot@2x.png', MONSTERINSIGHTS_PLUGIN_FILE ) ); ?> 2x"/>
				</div>
				<div class="mi-dw-reports-content"></div>
				<?php $this->widget_footer(); ?>
				<script id="mi-dw-report-template" type="text/html">
					<div class="mi-dw-report-toggle" data-report-type="" data-report-component="">
						<h2 class="mi-dw-report-title"></h2>
						<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="" data-tooltip-description=""></div>
					</div>
					<div class="mi-dw-report-content"></div>
				</script>
				<script id="mi-dw-error-template" type="text/html">
					<div class="mi-dw-error">
						<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>
						<h2 class="mi-dw-error-title"></h2>
						<div class="mi-dw-error-content"></div>
						<div class="mi-dw-error-footer"></div>
					</div>
				</script>
				<?php
			}
		}

	}

	/**
	 * Message to display when the plugin is not authenticated.
	 */
	public function widget_content_no_auth() {

		$url = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_settings' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
		?>
		<div class="mi-dw-not-authed">
			<h2><?php esc_html_e( 'Reports are not available', 'ga-premium' ); ?></h2>
			<p><?php esc_html_e( 'Please connect MonsterInsights to Google Analytics to see reports.', 'ga-premium' ); ?></p>
			<a href="<?php echo esc_url( $url ); ?>" class="mi-dw-btn-large"><?php esc_html_e( 'Configure MonsterInsights', 'ga-premium' ); ?></a>
		</div>
		<?php
	}


	/**
	 * Load widget-specific scripts.
	 */
	public function widget_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'dashboard' === $screen->id ) {
			wp_enqueue_style( 'monsterinsights-dashboard-widget-styles', plugins_url( 'pro/assets/css/admin-dashboard-widget' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );

			wp_register_style( 'monsterinsights-vendors-style', plugins_url( 'assets/css/vendors' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-vendors-style' );

			wp_register_script( 'monsterinsights-vendors-script', plugins_url( 'assets/js/vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
			wp_enqueue_script( 'monsterinsights-vendors-script' );
			wp_enqueue_script( 'jquery-ui-tooltip' );

			wp_enqueue_script( 'monsterinsights-dashboard-widget', plugins_url( 'pro/assets/js/admin-dashboard-widget' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version(), true );
			wp_localize_script( 'monsterinsights-dashboard-widget', 'monsterinsights_dashboard_widget', array(
				'options_nonce'    => wp_create_nonce( 'monsterinsights_dashboard_widget' ),
				'widget_state'     => $this->get_options(),
				'reports_names'    => array(
					'overview_top'   => esc_html__( 'Overview Report', 'ga-premium' ),
					'overview'       => esc_html__( 'Show overview reports', 'ga-premium' ),
					'toppages'       => esc_html__( 'Top Posts / Pages', 'ga-premium' ),
					'newvsreturn'    => esc_html__( 'New vs. Returning Visitors', 'ga-premium' ),
					'devices'        => esc_html__( 'Device Breakdown', 'ga-premium' ),
					'publisher'      => esc_html__( 'Show Publishers Reports', 'ga-premium' ),
					'landingpages'   => esc_html__( 'Top Landing Pages', 'ga-premium' ),
					'exitpages'      => esc_html__( 'Top Exit Pages', 'ga-premium' ),
					'outboundlinks'  => esc_html__( 'Top Outbound Links', 'ga-premium' ),
					'affiliatelinks' => esc_html__( 'Top Affiliate Links', 'ga-premium' ),
					'downloadlinks'  => esc_html__( 'Top Download Links', 'ga-premium' ),
					'ecommerce'      => esc_html__( 'Show eCommerce Reports', 'ga-premium' ),
					'infobox'        => esc_html__( 'Overview', 'ga-premium' ),
					'products'       => esc_html__( 'Top Products', 'ga-premium' ),
					'conversions'    => esc_html__( 'Top Conversion Sources', 'ga-premium' ),
					'addremove'      => esc_html__( 'Total Add/Remove', 'ga-premium' ),
					'days'           => esc_html__( 'Time to Purchase', 'ga-premium' ),
					'sessions'       => esc_html__( 'Sessions to Purchase', 'ga-premium' ),
				),
				'reports_tooltips' => array(
					'overview_top'   => '',
					'toppages'       => esc_html__( 'This list shows the most viewed posts and pages on your website.', 'ga-premium' ),
					'newvsreturn'    => esc_html__( 'This graph shows what percent of your user sessions come from new versus repeat visitors.', 'ga-premium' ),
					'devices'        => esc_html__( 'This graph shows what percent of your visitor sessions are done using a traditional computer or laptop, tablet or mobile device to view your site.', 'ga-premium' ),
					'landingpages'   => esc_html__( 'This list shows the top pages users first land on when visiting your website.', 'ga-premium' ),
					'exitpages'      => esc_html__( 'This list shows the top pages users exit your website from.', 'ga-premium' ),
					'outboundlinks'  => esc_html__( 'This list shows the top links clicked on your website that go to another website.', 'ga-premium' ),
					'affiliatelinks' => esc_html__( 'This list shows the top affiliate links your visitors clicked on.', 'ga-premium' ),
					'downloadlinks'  => esc_html__( 'This list shows the download links your visitors clicked the most.', 'ga-premium' ),
					'infobox'        => '',
					'products'       => esc_html__( 'This list shows the top selling products on your website.', 'ga-premium' ),
					'conversions'    => esc_html__( 'This list shows the top referral websites in terms of product revenue.', 'ga-premium' ),
					'addremove'      => esc_html__( 'The number of times products on your site were added to the cart.', 'ga-premium' ),
					'days'           => esc_html__( 'This list shows how many days from first visit it took users to purchase products from your site.', 'ga-premium' ),
					'sessions'       => esc_html__( 'This list shows the number of sessions it took users before they purchased a product from your website.', 'ga-premium' ),
				),
				'error_text'       => esc_html__( 'Error', 'ga-premium' ),
				'error_default'    => esc_html__( 'There was an issue loading the report data. Please try again.', 'ga-premium' ),
				'default_tab'      => $this->get_default_tab(),
			) );
		}
	}

	/**
	 * Store the widget state in the db using an Ajax call.
	 */
	public function save_widget_state() {

		check_ajax_referer( 'monsterinsights_dashboard_widget', 'security' );

		$options = isset( $_POST['widget_state'] ) ? $_POST['widget_state'] : array();
		array_walk( $options, 'sanitize_text_field' );
		update_user_meta( get_current_user_id(), 'monsterinsights_user_preferences', $options );

		wp_send_json_success();

	}

	/**
	 * Load report data based on the parameters from frontend.
	 */
	public function get_report_data() {

		check_ajax_referer( 'monsterinsights_dashboard_widget', 'security' );

		$report_name = ! empty( $_POST['report'] ) ? sanitize_text_field( $_POST['report'] ) : '';
		$component   = ! empty( $_POST['component'] ) ? sanitize_text_field( $_POST['component'] ) : '';

		if ( monsterinsights_is_pro_version() ) {
			if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
				wp_send_json_error( array( 'message' => esc_html__( 'You can\'t view MonsterInsights reports because you are not licensed.', 'ga-premium' ) ) );
			} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
				// good to go: site licensed.
			} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
				// good to go: network licensed.
			} else {
				wp_send_json_error( array( 'message' => esc_html__( 'You can\'t view MonsterInsights reports due to license key errors.', 'ga-premium' ) ) );
			}
		}

		if ( ! empty( $_POST['interval'] ) ) {
			$interval = sanitize_text_field( $_POST['interval'] );
			$end      = date( 'Y-m-d', strtotime( 'Yesterday' ) );

			if ( intval( $interval ) > 0 ) {
				$start = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' - ' . $interval . ' days ' ) );
			}
		}

		$report = MonsterInsights()->reporting->get_report( $report_name );

		if ( empty( $report ) ) {
			wp_send_json_error( array( 'message' => __( 'Unknown report. Try refreshing and retrying. Contact support if this issue persists.', 'ga-premium' ) ) );
		}

		if ( ! MonsterInsights()->license->license_can( $report->level ) ) {
			wp_send_json_success( array(
				'html'   => $report->get_upsell_notice(),
				'upsell' => true,
			) );
		}


		if ( isset( $start ) && isset( $end ) ) {
			$args = array(
				'start' => $start,
				'end'   => $end,
			);
		} else {
			$args = array(
				'default' => 1,
			);
		}
		$data   = $report->get_data( $args );
		$method = 'get_' . $component;

		if ( ! empty( $data['success'] ) && isset( $data['data'] ) && method_exists( $report, $method ) ) {
			ob_start();
			$report->$method( $data['data'] );
			$html = ob_get_clean();
			wp_send_json_success( array(
				'html' => $html,
			) );
		} else {
			wp_send_json_error( array(
				'message' => $data['error'],
				'data'    => $data['data'],
			) );
		}

	}

	/**
	 * Load & store the dashboard widget settings.
	 *
	 * @return array
	 */
	public function get_options() {
		if ( ! isset( $this->options ) ) {
			$this->options = self::wp_parse_args_recursive( get_user_meta( get_current_user_id(), 'monsterinsights_user_preferences', true ), self::$default_options );
		}

		return apply_filters( 'monsterinsights_dashboard_widget_options', $this->options );

	}

	/**
	 * Output the widget footer area.
	 */
	public function widget_footer() {
		$needs_wp_forms    = ! class_exists( 'WPForms' );
		$license_level     = MonsterInsights()->license->get_license_type();
		$needs_pro_version = ! in_array( $license_level, array( 'pro', 'master' ), true );

		if ( $needs_pro_version || $needs_wp_forms ) {
			?>
			<p class="mi-dw-footer">
				<?php
				if ( $needs_wp_forms ) {
					$wp_forms_url = wp_nonce_url( add_query_arg( array(
						'action' => 'install-plugin',
						'plugin' => 'wpforms-lite',
					), admin_url( 'update.php' ) ), 'install-plugin_wpforms-lite' );
					?>
					<a href="<?php echo esc_url( $wp_forms_url ); ?>" target="_blank">
						<?php
						// Translators: The recommended plugin is WPForms.
						printf( esc_html__( 'Recommended plugin: %s', 'ga-premium' ), 'WPForms' );
						?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ga-premium' ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
					<?php
				}
				// Check license type & only show upsell link to non-pro versions.
				if ( $needs_pro_version ) {
					if ( $needs_wp_forms ) {
						?>
						<span class="mi-dw-separator">|</span>
						<?php
					}
					?>
					<a href="<?php echo esc_url( monsterinsights_get_upgrade_link( 'dashboard-widget', 'footer-link', 'https://monsterinsights.com/my-account' ) ); ?>" target="_blank">
						<?php esc_html_e( 'Upgrade To Pro', 'ga-premium' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ga-premium' ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span>
					</a>
				<?php } ?>
			</p>
			<?php
		}
	}

	/**
	 * Recursive wp_parse_args.
	 *
	 * @param string|array|object $a Value to merge with $b.
	 * @param array               $b The array with the default values.
	 *
	 * @return array
	 */
	public static function wp_parse_args_recursive( $a, $b ) {
		$a      = (array) $a;
		$b      = (array) $b;
		$result = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = self::wp_parse_args_recursive( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}

		return $result;
	}

	/**
	 * Get the default tab for the overview report.
	 *
	 * @return string
	 */
	public function get_default_tab() {

		$default_tab = '';
		if ( class_exists( 'Jetpack_Google_Analytics_Options' ) && Jetpack_Google_Analytics_Options::has_tracking_code() ) {
			$default_tab = 'pageviews-tab';
		}

		return $default_tab;

	}
}
