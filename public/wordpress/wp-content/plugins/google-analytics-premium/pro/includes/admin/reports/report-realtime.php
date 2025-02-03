<?php
/**
 * Real-Time Report  
 *
 * Ensures all of the reports have a uniform class with helper functions.
 *
 * @since 7.2.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Report_RealTime extends MonsterInsights_Report {

	public $title;
	public $class   = 'MonsterInsights_Report_RealTime';
	public $name    = 'realtime';
	public $version = '1.0.0';
	public $level   = 'plus';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		$this->title = __( 'Real-Time', 'ga-premium' );
		add_filter( 'monsterinsights_report_transient_expiration', array( $this, 'sixty_seconds' ), 10, 2 );
		add_filter( 'monsterinsights_report_use_cache', array( $this, 'use_cache' ), 10, 2 );
		parent::__construct();
	}

	public function sixty_seconds( $length, $name ) {
		return $this->name === $name ? 60 : $length;
	}

	public function use_cache( $use_cache, $name ) {
		return $this->name === $name ? true : $use_cache;
	}

	// Outputs the report.
	protected function get_report_html( $data = array() ){
		if ( empty( $data['realtime'] ) ) {
			$data['realtime'] = array();
		}
		$data = $data['realtime'];
		ob_start();
		echo $this->get_first_row( $data );
		echo $this->get_page_list(  $data );
		echo $this->get_traffic_list( $data );
		echo $this->get_geo_row( $data );
		$html = ob_get_clean();
		return $html;
	}

	public function get_first_row( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-container row monsterinsights-clear" style="margin-bottom: 20px;">
			<div class="monsterinsights-reports-2-column-item col-md-6">
				<?php $this->get_real_time_number( $data ); ?>
			</div>
			<div class="monsterinsights-reports-2-column-item col-md-6">
				<?php $this->get_over_time_graph( $data ); ?>
				<div id="monsterinsights-realtime-chartjs-tooltip" style="opacity: 0"></div>
			</div>
		</div>
		<?php
	}

	public function get_real_time_number( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-panel monsterinsights-white-bg-panel panel nopadding">
			<div class="monsterinsights-reports-panel-title">
				<?php echo esc_html__( 'Right Now', 'ga-premium' ); ?>
			</div>
			<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Active Users', 'ga-premium' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This is the number of active users currently on your site.', 'ga-premium' ) ); ?>">
			</div>
			<div id="monsterinsights-reports-realtime-report-now-number-container">
				<?php if ( isset ( $data['now'] ) ) { ?>
					<p id="monsterinsights-reports-realtime-report-now-number">
						<?php echo $data['now']; ?>
					</p>
					<div id="monsterinsights-reports-realtime-report-now-on-site">
						<?php esc_html_e( 'active users on site', 'ga-premium' ); ?>
					</div>
					<?php } else { ?>
					<p id="monsterinsights-reports-realtime-report-now-number-out">
						<?php esc_html_e( 'The real-time graph of visitors over time is not currently available for this site. Please try again later. ', 'ga-premium' ); ?>
					</p>
					<?php } ?>
				<p id="monsterinsights-reports-realtime-report-now-number-explainer">
					<?php esc_html_e( 'Important: this only includes users who are tracked in real-time. Not all users are tracked in real-time including (but not limited to) logged in site administrators, certain mobile users, and users who match a Google Analytics filter.', 'ga-premium' ); ?>
				</p>

				<p id="monsterinsights-realtime-report-timebox">
					<?php esc_html_e( 'The real-time report automatically updates approximately every 60 seconds.', 'ga-premium' ); ?>
					<?php echo sprintf( esc_html__( 'The real-time report was last updated %s seconds ago.', 'ga-premium' ), '<span id="monsterinsights-realtime-report-timer">0</span>'); ?>
					<?php esc_html_e( 'The latest data will be automatically shown on this page when it becomes available.', 'ga-premium' ); ?>
					<?php esc_html_e( 'There is no need to refresh the browser (doing so won\'t have any effect).', 'ga-premium' ); ?>
				</p>
			</div>
		</div>
		<?php
	}

	public function get_over_time_graph( $data ) {
			?>
			<div class="monsterinsights-reports-2-column-panel monsterinsights-white-bg-panel panel nopadding list-no-icons realtime-report-graph">
				<div class="monsterinsights-reports-panel-title">
					<?php echo esc_html__( 'Pageviews Per Minute', 'ga-premium' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Pageviews Per Minute', 'ga-premium' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This graph shows the number of pageviews for each of the last 30 minutes.', 'ga-premium' ) ); ?>">
				</div>
				<div class="monsterinsights-realtime-report-overview-graph-panel panel panel-default chart-panel">
					<div class="monsterinsights-tabbed-nav-panel monsterinsights-active">
						<div class="panel-body">
							<?php if ( ! empty ( $data['pageviewsovertime'] ) ) { ?>
							<?php echo $this->get_overview_report_js( $data['pageviewsovertime'] ); ?>
							<?php } else { ?>
								<?php esc_html_e( 'The real-time graph of pageviews over time is not currently available for this site. Please try again later. ', 'ga-premium' ); ?>
							<?php } ?>
						</div>
					</div>
					<div id="monsterinsights-chartjs-tooltip" style="opacity: 0"></div>
				</div>
			</div>
		<?php
	}

	public function get_overview_report_js( $data ) {
		$up         = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up.png';
		$up2x       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up@2x.png';
		$down       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down.png';
		$down2x     = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down@2x.png';
		$uplabel    = esc_attr__( 'UP', 'ga-premium' );
		$downlabel  = esc_attr__( 'DOWN', 'ga-premium' );
		$descriptor = esc_js( __( 'Visitors On Site', 'ga-premium' ) );
		$descriptor = "'" . $descriptor . "'";

		$labels = array();
		foreach ( $data['labels'] as $timestamp ) {
			$labels[] = "'" . esc_js( sprintf( esc_html__( '%s minutes ago', 'ga-premium'), $timestamp ) ) . "'";
		}

		$datapoints = array();
		foreach ( $data['datapoints'] as $datapoint ) {
			$datapoints[] = esc_js( $datapoint );
		}

		$trendpoints = array();
		foreach ( $data['trendpoints'] as $trendpoint ) {
			$trendpoints[] = esc_js( $trendpoint );
		}
		$class = 'realtime';
		ob_start(); ?>
		<div class="monsterinsights-reports-box-datagraph" style="position:relative;">
			<canvas id="monsterinsights-overview-<?php echo $class; ?>" width="400px" height="400px"></canvas>
			<script>
				jQuery( document ).ready( function () {
					if ( window.uorigindetected != null ) {

						Chart.defaults.LineWithLine = Chart.defaults.line;
						Chart.controllers.LineWithLine = Chart.controllers.line.extend( {
							draw: function ( ease ) {
								Chart.controllers.line.prototype.draw.call( this, ease );

								if ( this.chart.tooltip._active && this.chart.tooltip._active.length ) {
									var activePoint = this.chart.tooltip._active[0],
										ctx = this.chart.ctx,
										x = activePoint.tooltipPosition().x,
										topY = this.chart.scales['y-axis-0'].top,
										bottomY = this.chart.scales['y-axis-0'].bottom;

									// draw line
									ctx.save();
									ctx.beginPath();
									ctx.moveTo( x, topY );
									ctx.lineTo( x, bottomY );
									ctx.lineWidth = 1;
									ctx.strokeStyle = '#6db0e9';
									ctx.setLineDash( [10, 10] );
									ctx.stroke();
									ctx.restore();
								}
							}
						} );

						var ctx = document.getElementById( "monsterinsights-overview-<?php echo $class;?>" );
						var data = {
							labels: [<?php echo implode( ', ', $labels ); ?>],
							datasets: [
								{
									lineTension: 0, // ChartJS doesn't make nice curves like in the PSD so for now leaving straight on
									borderColor: '#5fa6e7',

									backgroundColor: 'rgba(	109, 176, 233, 0.2)',
									fillOpacity: 0.2,
									fillColor: 'rgba(	109, 176, 233, 0.2)',

									pointRadius: 4,
									pointBorderColor: '#3783c4',
									pointBackgroundColor: '#FFF',


									hoverRadius: 1,

									pointHoverBackgroundColor: '#FFF',// Point background color when hovered.
									pointHoverBorderColor: '#3783c4',//Point border color when hovered.
									pointHoverBorderWidth: 4,//Border width of point when hovered.
									pointHoverRadius: 6,//The radius of the point when hovered.


									labels: [<?php echo implode( ', ', $labels );   ?>],
									data: [<?php echo implode( ', ', $datapoints );   ?>],
									trend: [<?php echo implode( ', ', $trendpoints ); ?>],
								},
							]
						};

						var overviewTooltips = function ( tooltip ) {
							// Tooltip Element
							var tooltipEl = jQuery( '#monsterinsights-realtime-chartjs-tooltip' );
							if ( ! tooltipEl[0] ) {
								jQuery( 'body' ).append( '<div id="monsterinsights-realtime-chartjs-tooltip" style="padding:10px;"></div>' );
								tooltipEl = jQuery( '#monsterinsights-realtime-chartjs-tooltip' );
							}
							// Hide if no tooltip
							if ( ! tooltip.opacity ) {
								tooltipEl.css( {
									opacity: 0
								} );
								jQuery( '.chartjs-wrap canvas' ).each( function ( index, el ) {
									jQuery( el ).css( 'cursor', 'default' );
								} );
								return;
							}
							jQuery( this._chart.canvas ).css( 'cursor', 'pointer' );

							// Set caret Position
							tooltipEl.removeClass( 'above below no-transform' );
							if ( tooltip.yAlign ) {
								tooltipEl.addClass( tooltip.yAlign );
							} else {
								tooltipEl.addClass( 'no-transform' );
							}

							var label = tooltip.title[0];
							var value = tooltip.title[1];
							var change = tooltip.title[2];
							var trend = '';
							if ( change == 0 ) {
								trend += '0%';
							} else if ( change > 0 ) {
								trend += '<img src="<?php echo esc_url( $up ); ?>"';
								trend += 'srcset="<?php echo esc_url( $up2x ); ?> 2x"';
								trend += 'alt="<?php echo esc_attr( $uplabel ); ?>" style="margin-right:6px"/>' + change + '%';
							} else {
								trend += '<img src="<?php echo esc_url( $down ); ?>"';
								trend += 'srcset="<?php echo esc_url( $down2x ); ?> 2x"';
								trend += 'alt="<?php echo esc_attr( $downlabel ); ?>" style="margin-right:6px"/>' + Math.abs( change ) + '%';
							}

							var html = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container">';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '</div>';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-descriptor">' + <?php echo wp_kses_post( $descriptor ); ?> + '</div>';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-trend">' + trend + '</div>';
							html += '</div>';

							tooltipEl.html( html );

							// Find Y Location on page
							var top = 0;

							if ( tooltip.yAlign ) {
								var ch = 0;
								if ( tooltip.caretHeight ) {
									ch = tooltip.caretHeight;
								}
								if ( tooltip.yAlign == 'above' ) {
									top = tooltip.y - ch - tooltip.caretPadding;
								} else {
									top = tooltip.y + ch + tooltip.caretPadding;
								}
							}
							// Display, position, and set styles for font
							tooltipEl.css( {
								opacity: 1,
								width: tooltip.width ? (
									tooltip.width + 'px'
								) : 'auto',
								left: tooltip.x - 50 + 'px',
								top: top + 68 + 'px',
								fontFamily: tooltip._fontFamily,
								fontSize: tooltip.fontSize,
								fontStyle: tooltip._fontStyle,
								padding: tooltip.yPadding + 'px ' + tooltip.xPadding + 'px',
								'z-index': 99999,
							} );
						};

						var MonsterInsightsOverview<?php echo time();?> = new Chart( ctx, {
							type: 'LineWithLine',
							data: data,
							plugins: [
								{
									afterRender: function () {
										//var parent = jQuery( '.monsterinsights-overview-report-overview-graph-panel .monsterinsights-tabbed-nav-tab-title[data-tab=\'pageviews-tab\']' ).hasClass( 'active' );
										//if ( ! parent && '<?php echo $class; ?>' == 'pageviews' ) {
										//	jQuery( '.monsterinsights-tabbed-nav-panel.pageviews-tab' ).hide();
										//}
									},
								}
							],
							options: {
								legend: {
									display: false,
								},
								hover: {
									intersect: true,
								},
								tooltips: {
									enabled: false,
									yAlign: 'top',
									xAlign: 'top',
									intersect: false,
									custom: overviewTooltips,
									callbacks: {
										title: function ( tooltipItem, data ) {
											tooltipItem = tooltipItem[0];
											var label = data.datasets[0].labels[tooltipItem.index];
											var value = data.datasets[0].data[tooltipItem.index];
											var change = data.datasets[0].trend[tooltipItem.index];
											return [label, value, change];
										},
										label: function ( tooltipItem, data ) {
											return '';
										}
									}
								},
								scales: {
									xAxes: [
										{
											spanGaps: true,
											position: 'bottom',
											gridLines: {
												show: true,
												color: '#f2f6fa',
											},
											ticks: {
												fontColor: '#7f8591',
												autoSkip: false,
											},
											afterTickToLabelConversion: function(data){
							                    var xLabels = data.ticks;

							                    xLabels.forEach(function (labels, i) {
							                        if (i % 5 !== 4 && i !== 29){
							                            xLabels[i] = '';
							                        }
							                    });
							                } 
										}
									],
									yAxes: [
										{
											gridLines: {
												show: true,
												color: '#d4e2ef',
											},
											ticks: {
												fontColor: '#7f8591',
												callback: function ( value ) {
													if ( value % 1 === 0 ) {
														return value;
													}
												}
											}
										}
									]
								},
								animation: false,
								legend: {
									display: false,
								},
								responsive: true,
								maintainAspectRatio: false,
								borderWidth: 1,
							}
						} );
					}
				} );
			</script>
		</div>
		<?php
		$html = ob_get_clean();

		return $html;
	}

	public function get_page_list( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-panel monsterinsights-white-bg-panel panel nopadding list-no-icons monsterinsights-clear" style="margin-bottom: 20px;">
			<div class="monsterinsights-reports-panel-title">
				<?php echo esc_html__( 'Top Pages', 'ga-premium' ); ?>
			</div>
			<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Top Pages', 'ga-premium' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the top pages users are currently viewing on your site.', 'ga-premium' ) ); ?>"></div>
			<div class="monsterinsights-reports-list">
				<table id="monsterinsights-report-realtime-page-list" class="table monsterinsights-reports-data-table">
					<thead class="monsterinsights-reports-data-table-thead">
					<tr>
						<th><?php echo esc_html__( 'Page', 'ga-premium' ); ?></th>
						<th><?php echo esc_html__( 'Pageview Count', 'ga-premium' ); ?></th>
						<th><?php echo esc_html__( 'Percent of Total', 'ga-premium' ); ?></th>
					</tr>
					</thead>
					<tbody class="monsterinsights-reports-data-table-tbody monsterinsights-reports-pages-list">
					<?php $i = 1;
					if ( ! empty( $data['toppages'] ) && is_array( $data['toppages'] ) ) {
						foreach ( $data['toppages'] as $iqueries => $queriesdata ) {
							$hide     = $i > 10 ? ' style="display: none;" ' : '';
							$opening  = ! empty( $queriesdata['url'] ) ? '<a href="' . esc_attr( untrailingslashit( site_url() ) . $queriesdata['url'] ) . '" target="_blank">' : '';
							$closing  = '</a>' ;
						 	?>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row" <?php echo $hide; ?>>
								<td><?php echo '<span class="monsterinsights-reports-list-count">' . $i . '.</span>&nbsp;<span class="monsterinsights-reports-list-text">' . $opening . esc_html( $queriesdata['title'] ) . $closing . '</span>'; ?></td>
								<td><?php echo number_format_i18n( $queriesdata['count'] ); ?></td>
								<td><?php echo number_format( $queriesdata['percent'], 2 ) . '%'; ?></td>
							</tr>
							<?php
							$i ++;
						}
					} else {
						?>
						<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row">
							<td colspan="3"><?php echo esc_html__( 'No pageviews currently.', 'ga-premium' ); ?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<?php
			$referral_url = 'https://analytics.google.com/analytics/web/#/realtime/rt-content/' . MonsterInsights()->auth->get_referral_url();
			?>
			<div class="monsterinsights-reports-panel-footer monsterinsights-reports-panel-footer-large">
				<?php echo esc_html__( 'Show', 'ga-premium' ); ?>&nbsp;
				<div class="monsterinsights-reports-show-selector-group btn-group" role="group" aria-label="<?php echo esc_html__( 'How many to show', 'ga-premium' ); ?>">
					<button type="button" data-tid="monsterinsights-report-realtime-page-list" class="monsterinsights-reports-show-selector-button ten btn btn-default active" disabled="disabled">
						10
					</button>
					<button type="button" data-tid="monsterinsights-report-realtime-page-list" class="monsterinsights-reports-show-selector-button twentyfive btn btn-default">
						25
					</button>
					<button type="button" data-tid="monsterinsights-report-realtime-page-list" class="monsterinsights-reports-show-selector-button fifty btn btn-default">
						50
					</button>
				</div>
				<a href="<?php echo $referral_url; ?>" target="_blank" title="<?php echo esc_html__( 'View All Real-Time Pageviews', 'ga-premium' ); ?>" class="monsterinsights-reports-panel-footer-button"><?php echo esc_html__( 'View All Real-Time Pageviews', 'ga-premium' ); ?></a>
			</div>
		</div>
		<?php
	}

	public function get_traffic_list( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-panel monsterinsights-white-bg-panel panel nopadding list-no-icons monsterinsights-clear">
			<div class="monsterinsights-reports-panel-title">
				<?php echo esc_html__( 'Top Referral Traffic Sources', 'ga-premium' ); ?>
			</div>
			<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Top Referral Traffic Sources', 'ga-premium' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the  top referral traffic sources for your site currently.', 'ga-premium' ) ); ?>"></div>
			<div class="monsterinsights-reports-list">
				<table id="monsterinsights-report-realtime-referral-list" class="table monsterinsights-reports-data-table">
					<thead class="monsterinsights-reports-data-table-thead">
					<tr>
						<th><?php echo esc_html__( 'Source', 'ga-premium' ); ?></th>
						<th><?php echo esc_html__( 'Path', 'ga-premium' ); ?></th>
						<th><?php echo esc_html__( 'Campaign', 'ga-premium' ); ?></th>
						<th><?php echo esc_html__( 'Count', 'ga-premium' ); ?></th>
						<th><?php echo esc_html__( 'Percent', 'ga-premium' ); ?></th>
					</tr>
					</thead>
					<tbody class="monsterinsights-reports-data-table-tbody monsterinsights-reports-pages-list">
					<?php $i = 1;
					if ( ! empty( $data['referrals'] ) && is_array( $data['referrals'] ) ) {
						foreach ( $data['referrals'] as $iqueries => $queriesdata ) {
							$hide     = $i > 10 ? ' style="display: none;" ' : '';
						 	?>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row" <?php echo $hide; ?>>
								<td><?php echo '<span class="monsterinsights-reports-list-count">' . $i . '.</span>&nbsp;<span class="monsterinsights-reports-list-text">' . esc_html( $queriesdata['source'] ) . '</span>'; ?></td>
								<td><?php echo esc_html( $queriesdata['path'] ); ?></td>
								<td><?php echo esc_html( $queriesdata['campaign'] ); ?></td>
								<td><?php echo number_format_i18n( $queriesdata['count'] ); ?></td>
								<td><?php echo number_format( $queriesdata['percent'], 2 ) . '%'; ?></td>
							</tr>
							<?php
							$i ++;
						}
					} else {
						?>
						<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row">
							<td colspan="5"><?php echo esc_html__( 'No referral traffic currently.', 'ga-premium' ); ?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<?php
			$referral_url = 'https://analytics.google.com/analytics/web/#/realtime/rt-traffic/' . MonsterInsights()->auth->get_referral_url();
			?>
			<div class="monsterinsights-reports-panel-footer monsterinsights-reports-panel-footer-large">
				<?php echo esc_html__( 'Show', 'ga-premium' ); ?>&nbsp;
				<div class="monsterinsights-reports-show-selector-group btn-group" role="group" aria-label="<?php echo esc_html__( 'How many to show', 'ga-premium' ); ?>">
					<button type="button" data-tid="monsterinsights-report-realtime-referral-list" class="monsterinsights-reports-show-selector-button ten btn btn-default active" disabled="disabled">
						10
					</button>
					<button type="button" data-tid="monsterinsights-report-realtime-referral-list" class="monsterinsights-reports-show-selector-button twentyfive btn btn-default">
						25
					</button>
					<button type="button" data-tid="monsterinsights-report-realtime-referral-list" class="monsterinsights-reports-show-selector-button fifty btn btn-default">
						50
					</button>
				</div>
				<a href="<?php echo $referral_url; ?>" target="_blank" title="<?php echo esc_html__( 'View All Real-Time Traffic Sources', 'ga-premium' ); ?>" class="monsterinsights-reports-panel-footer-button"><?php echo esc_html__( 'View All Real-Time Traffic Sources', 'ga-premium' ); ?></a>
			</div>
		</div>
		<?php
	}

	public function get_geo_row( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-container row monsterinsights-clear" style="margin-right: -15px; margin-left: -15px;">
			<div class="monsterinsights-reports-2-column-item col-md-6">
				<?php $this->get_country_table( $data ); ?>
			</div>
			<div class="monsterinsights-reports-2-column-item col-md-6">
				<?php $this->get_city_table( $data ); ?>
			</div>
		</div>
		<?php
	}

	public function get_country_table( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-panel monsterinsights-white-bg-panel panel nopadding list-no-icons">
			<div class="monsterinsights-reports-panel-title">
					<?php echo esc_html__( 'Top Countries', 'ga-premium' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Top Countries', 'ga-premium' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the top countries visitors on your site right now are browsing from.', 'ga-premium' ) ); ?>"></div>
				<div class="monsterinsights-reports-list">
					<table id="monsterinsights-report-realtime-country-list" class="table monsterinsights-reports-data-table">
						<thead class="monsterinsights-reports-data-table-thead">
						<tr>
							<th><?php echo esc_html__( 'Country', 'ga-premium' ); ?></th>
							<th><?php echo esc_html__( 'Count', 'ga-premium' ); ?></th>
							<th><?php echo esc_html__( 'Percent', 'ga-premium' ); ?></th>
						</tr>
						</thead>
						<tbody class="monsterinsights-reports-pages-list">
						<?php 
						$i = 1;
						if ( ! empty( $data['countries'] ) && is_array( $data['countries'] ) ) {
							$countries = array_flip(monsterinsights_get_country_list( false ));
							foreach ( $data['countries'] as $iqueries => $queriesdata ) {
								$hide     = $i > 10 ? ' style="display: none;" ' : '';
							 	?>
								<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row" <?php echo $hide; ?>>
									<?php
									if ( ! empty( $countries[ $queriesdata['country'] ] ) ) {
										$queriesdata['iso']  = $countries[ $queriesdata['country'] ];
										$queriesdata['name'] = monsterinsights_get_country_list( true )[$queriesdata['iso']];
									} else {
										$queriesdata['iso']  = '';
										$queriesdata['name'] = $queriesdata['country'];
									}
									echo '<td><span class="monsterinsights-reports-list-count">' . $i . '.</span><span class="monsterinsights-reports-country-flag monsterinsights-flag-icon monsterinsights-flag-icon-' . strtolower( $queriesdata['iso'] ) . ' "></span><span class="monsterinsights-reports-list-text">' . $queriesdata['name'] . '</span></td>';
									?>
									<td><?php echo number_format_i18n( $queriesdata['count'] ); ?></td>
									<td><?php echo number_format( $queriesdata['percent'], 2 ) . '%'; ?></td>
									<?php
									$i++;
									?>
								</tr><?php
							}
							if ( $i < 11 ) {
								for ( $i; $i < 11; $i++ ) {
									?>
									<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row">
										<td colspan="4">&nbsp;</td>
									</tr>
									<?php
								}
							}
						} else {
							?>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row">
								<td colspan="3"><?php echo esc_html__( 'No traffic currently.', 'ga-premium' ); ?></td>
							</tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="3">&nbsp;</td></tr>
							<?php
						}
						?>
						</tbody>
					</table>
				</div>
				<?php
				$referral_url = 'https://analytics.google.com/analytics/web/#/realtime/rt-location/' . MonsterInsights()->auth->get_referral_url();
				?>
				<div class="monsterinsights-reports-panel-footer monsterinsights-reports-panel-footer-large">
<!-- 					<?php //echo esc_html__( 'Show', 'ga-premium' ); ?>&nbsp;
					<div class="monsterinsights-reports-show-selector-group btn-group" role="group" aria-label="<?php //echo esc_html__( 'How many to show', 'ga-premium' ); ?>">
						<button type="button" data-tid="monsterinsights-report-realtime-country-list" class="monsterinsights-reports-show-selector-button ten btn btn-default active" disabled="disabled">
							10
						</button>
						<button type="button" data-tid="monsterinsights-report-realtime-country-list" class="monsterinsights-reports-show-selector-button twentyfive btn btn-default">
							25
						</button>
						<button type="button" data-tid="monsterinsights-report-realtime-country-list" class="monsterinsights-reports-show-selector-button fifty btn btn-default">
							50
						</button>
					</div> -->
					<a href="<?php echo $referral_url; ?>" target="_blank" title="<?php echo esc_html__( 'View All Real-Time Traffic by Country', 'ga-premium' ); ?>" class="monsterinsights-reports-panel-footer-button"><?php echo esc_html__( 'View All Real-Time Traffic by Country', 'ga-premium' ); ?></a>
				</div>
		</div>
		<?php
	}

	public function get_city_table( $data ) {
		?>
		<div class="monsterinsights-reports-2-column-panel monsterinsights-white-bg-panel panel nopadding list-no-icons">
			<div class="monsterinsights-reports-panel-title">
					<?php echo esc_html__( 'Top Cities', 'ga-premium' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Top Cities', 'ga-premium' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the top cities visitors on your site right now are browsing from.', 'ga-premium' ) ); ?>"></div>
				<div class="monsterinsights-reports-list">
					<table id="monsterinsights-report-realtime-cities-list" class="table monsterinsights-reports-data-table">
						<thead class="monsterinsights-reports-data-table-thead">
						<tr>
							<th><?php echo esc_html__( 'City', 'ga-premium' ); ?></th>
							<th><?php echo esc_html__( 'State/Region', 'ga-premium' ); ?></th>
							<th><?php echo esc_html__( 'Country', 'ga-premium' ); ?></th>
							<th><?php echo esc_html__( 'Count', 'ga-premium' ); ?></th>
						</tr>
						</thead>
						<tbody class="monsterinsights-reports-pages-list">
						<?php $i = 1;
						if ( ! empty( $data['cities'] ) && is_array( $data['cities'] ) ) {
							$countries = array_flip(monsterinsights_get_country_list( false ));
							foreach ( $data['cities'] as $iqueries => $queriesdata ) {
								$hide     = $i > 10 ? ' style="display: none;" ' : '';
							 	?>
								<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row" <?php echo $hide; ?>>
									<td><span class="monsterinsights-reports-list-count"><?php echo $i;?>.</span><?php echo esc_html( $queriesdata['city'] ); ?></td>
									<td><?php echo esc_html( $queriesdata['region'] ); ?></td>
									<?php
									if ( ! empty( $countries[ $queriesdata['country'] ] ) ) {
										$queriesdata['iso']  = $countries[ $queriesdata['country'] ];
										$queriesdata['name'] = monsterinsights_get_country_list( true )[$queriesdata['iso']];
									} else {
										$queriesdata['iso']  = '';
										$queriesdata['name'] = $queriesdata['country'];
									}
									echo '<td><span class="monsterinsights-reports-country-flag monsterinsights-flag-icon monsterinsights-flag-icon-' . strtolower( $queriesdata['iso'] ) . ' "></span><span class="monsterinsights-reports-list-text">' . $queriesdata['name'] . '</span></td>';
									?>
									<td><?php echo number_format_i18n( $queriesdata['count'] ); ?></td>
									<?php
									$i++;
									?>
								</tr><?php
							}
							if ( $i < 11 ) {
								for ( $i; $i < 11; $i++ ) {
									?>
									<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row">
										<td colspan="4">&nbsp;</td>
									</tr>
									<?php
								}
							}
						} else {
							?>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row">
								<td colspan="4"><?php echo esc_html__( 'No traffic currently.', 'ga-premium' ); ?></td>
							</tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<tr class="monsterinsights-reports-data-table-tr monsterinsights-listing-table-row"><td colspan="5">&nbsp;</td></tr>
							<?php
						}
						?>
						</tbody>
					</table>
				</div>
				<?php
				$referral_url = 'https://analytics.google.com/analytics/web/#/realtime/rt-location/' . MonsterInsights()->auth->get_referral_url();
				?>
				<div class="monsterinsights-reports-panel-footer monsterinsights-reports-panel-footer-large">
				<!-- 	<?php //echo esc_html__( 'Show', 'ga-premium' ); ?>&nbsp;
					<div class="monsterinsights-reports-show-selector-group btn-group" role="group" aria-label="<?php //echo esc_html__( 'How many to show', 'ga-premium' ); ?>">
						<button type="button" data-tid="monsterinsights-report-realtime-cities-list" class="monsterinsights-reports-show-selector-button ten btn btn-default active" disabled="disabled">
							10
						</button>
						<button type="button" data-tid="monsterinsights-report-realtime-cities-list" class="monsterinsights-reports-show-selector-button twentyfive btn btn-default">
							25
						</button>
						<button type="button" data-tid="monsterinsights-report-realtime-cities-list" class="monsterinsights-reports-show-selector-button fifty btn btn-default">
							50
						</button>
					</div> -->
					<a href="<?php echo $referral_url; ?>" target="_blank" title="<?php echo esc_html__( 'View All Real-Time Traffic by City', 'ga-premium' ); ?>" class="monsterinsights-reports-panel-footer-button"><?php echo esc_html__( 'View All Real-Time Traffic by City', 'ga-premium' ); ?></a>
				</div>
			</div>
		<?php
	}
}