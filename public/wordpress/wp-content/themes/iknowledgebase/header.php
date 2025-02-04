<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
        <link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'iknowledgebase' ); ?></a>
	<?php $iknowledgebase_menu_classes = apply_filters( 'iknowledgebase_menu_nav_classes', '' ); ?>
	<?php do_action( 'iknowledgebase_header_bar' ); ?>
    <nav class="navbar <?php echo esc_attr( $iknowledgebase_menu_classes ); ?>" role="navigation"
         aria-label="<?php esc_attr_e( 'Main Navigation', 'iknowledgebase' ); ?>">
        <div class="container">
     
    
    		<div class="header-content">
    			<?php iknowledgebase_brand(); ?>
    			<div class="navigation">
    				<a class="shop" href="https://www.mowdirect.co.uk/" target="_blank">shop</a>
    				<a class="blog" href="/blog/" target="_blank">blog</a>
    			</div>
    			<div id="header-contact">
					<p class="tel"><a class="rulertel" href="tel:020 3026 8712"> 020 3026 8712 </a></p>
					<div id="opening_time_id" class="opening_time"><small>Opening times</small> 
					<div class="opening-popup">
						<ul>
							<li>Call Weekdays 9am - 7pm <em>(Closed Between 1pm &amp; 2pm)</em></li>
							<li>Saturday Phone Lines 10am - 4pm</li>
						</ul>
						<span class="close">X</span></div>
					</div>
				</div>
			</div>
        </div>
    </nav>
</header>
<main class="main is-relative is-flex-shrink-0" id="content">
	<?php $iknowledgebase_settings = get_option( 'iknowledgebase_settings', false ); ?>
	<?php if ( !empty( $iknowledgebase_settings['body_svg'] ) ) : ?>
        <svg class="intersect" viewBox="0 0 1441 279" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="intersect" transform="translate(0.178955, 0.820312)" fill-rule="nonzero">
                <path d="M0,177.850479 L0,0 L1440.00104,0 L1440.00104,177.850479 C1268.57105,239.085479 1021.55925,277.43899 731.888245,277.43899 C442.215245,277.43899 171.433045,239.085479 0,177.850479 Z"
                      id="Path"></path>
            </g>
        </svg>
	<?php endif; ?>

