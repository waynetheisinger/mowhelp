<?php

/**
 * Class to register REST API endpoints for the block.
 */
class JS_Archive_List_Backend {

	public $config = [];

	public function register_routes() {
		$version   = '1';
		$namespace = 'jalw/v' . $version;

		register_rest_route( $namespace, '/years', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_years' ],
			'permission_callback' => '__return_true',
		] );

		register_rest_route( $namespace, '/years/(?P<year>[\d]+)/months', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_months' ],
			'args'                => [
				'year' => [
					'validate_callback' => 'is_numeric',
				]
			],
			'permission_callback' => '__return_true',
		] );
	}

	/**
	 * Creates internal config from received parameters.
	 *
	 * @param WP_REST_Request $request
	 */
	public function build_config( $request ) {
		$include_or_exclude = $request->get_param( 'exclusionType' ) ?? 'include';
		$categories         = $request->get_param( 'cats' ) ?? '';

		if ( $include_or_exclude === 'include' ) {
			$include = explode( ',', $categories );
			$exclude = [];
		} else {
			$include = [];
			$exclude = explode( ',', $categories );
		}

		return [
			'type'         => $request->get_param( 'type' ) ?? 'post',
			'onlycategory' => $request->get_param( 'onlycat' ) === 'true',
			'expand'       => $request->get_param( 'expand' ) === 'true',
			'include'      => $include,
			'exclude'      => $exclude,
		];
	}

	/**
	 * Get years of posts.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response Request with the data.
	 */
	public function get_years( $request ) {
		$config         = $this->build_config( $request );
		$data_source    = new JQ_Archive_List_DataSource( $config );
		$years          = $data_source->get_years();
		$cur_post_year  = null;
		$cur_post_month = null;
		$post_id        = $request->get_param( 'postId' ) ?? null;


		if ( ! empty( $config['postId'] ) ) {
			$post_data = get_post( $post_id );

			if ( $post_data ) {
				$cur_post_year  = intval( substr( $post_data->post_date_gmt, 0, 4 ) );
				$cur_post_month = intval( substr( $post_data->post_date_gmt, 5, 2 ) );
			}
		}

		foreach ( $years as $key => $yearObject ) {
			$years[ $key ]->permalink = get_year_link( $yearObject->year );
			$years[ $key ]->expand    = $data_source->year_should_be_expanded(
				$yearObject->year,
				$cur_post_year,
				$cur_post_month,
				$config['expand']
			);
		}

		return new WP_REST_Response( [
			'years'          => $years,
			'cur_post_year'  => $cur_post_year,
			'cur_post_month' => $cur_post_month,
		], 200 );
	}

	/**
	 * Get year's months with posts
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response Request with the data.
	 */
	public function get_months( $request ) {
		$config         = $this->build_config( $request );
		$data_source    = new JQ_Archive_List_DataSource( $config );
		$years          = $data_source->get_years();
		$cur_post_year  = null;
		$cur_post_month = null;
		$post_id        = $request->get_param( 'postId' ) ?? null;


		if ( ! empty( $config['postId'] ) ) {
			$post_data = get_post( $post_id );

			if ( $post_data ) {
				$cur_post_year  = intval( substr( $post_data->post_date_gmt, 0, 4 ) );
				$cur_post_month = intval( substr( $post_data->post_date_gmt, 5, 2 ) );
			}
		}

		foreach ( $years as $key => $yearObject ) {
			$years[ $key ]->permalink = get_year_link( $yearObject->year );
			$years[ $key ]->expand    = $data_source->year_should_be_expanded(
				$yearObject->year,
				$cur_post_year,
				$cur_post_month,
				$config['expand']
			);
		}

		return new WP_REST_Response( [
			'years'          => $years,
			'cur_post_year'  => $cur_post_year,
			'cur_post_month' => $cur_post_month,
		], 200 );
	}


}

$jalw_backed = new JS_Archive_List_Backend();
add_action( 'rest_api_init', [ $jalw_backed, 'register_routes' ] );
