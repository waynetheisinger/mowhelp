<?php
/**
 * the closing of the main content elements and the footer element
 *
 * @package iknowledgebase
 */
?>

</main>
<footer class="footer mt-6 p-0 pt-6">

   
        <div class="container">
        	 
        	<?php get_template_part( 'template-parts/content', 'newsletter' ); ?>
        	
            <div class="columns">
				<div class="column is-6">
					<div class="home-social">
						<div class="mowdirect-social">
							<h2>Lets stay in touch!</h2>
							<div class="row">
								<div class="home-footer-facebook">
									<a href="https://www.facebook.com/Mowdirect" target="_blank" rel="noopener"> 
									 <img src="https://www.mowdirect.co.uk/media/theme/home-facebook.png" alt="Mowdirect facebook">Facebook 
									</a>
								</div>
								<div class="home-footer-twitter">
									<a href="https://twitter.com/Mowdirect" target="_blank" rel="noopener"> 
								    	<img src="https://www.mowdirect.co.uk/media/theme/home-twitter.png" alt="Mowdirect twitter"> Twitter 
								    </a>
								</div>
								<div class="home-footer-youtube">
									<a href="https://www.youtube.com/channel/UCOzi0_AEGIkxN2pPGbgpYXg" target="_blank" rel="noopener"> 
										<img src="https://www.mowdirect.co.uk/media/theme/home-youTube.png" alt="Mowdirect youtube"> Youtube 
									</a>
								</div>
								<div class="home-footer-instagram">
									<a href="https://www.instagram.com/mowdirect/" target="_blank" rel="noopener"> 
										<img src="https://www.mowdirect.co.uk/media/theme/home-instagram.png" alt="Mowdirect instagram">Instagram 
									</a>
								</div>
								<div class="home-footer-blog">
									<a href="/blog" target="_blank" rel="noopener"> 
										<img src="https://www.mowdirect.co.uk/media/theme/home-read-our-blog.png" alt="Blog">Blog
									</a>
								</div>
								<div class="home-footer-aoworld">
									<a href="http://mowhow.mowdirect.co.uk/"> 
										<img src="https://www.mowdirect.co.uk/media/theme/home-mowhow.png" alt="Help &amp; Advice">Help &amp; Advice
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="trustpilot" style="max-width: 225px;background-color: #fff;border: 2px solid #26ad7b;padding: 10px;border-radius: 					  10px;display: flex;align-items: center;justify-content: center;">
						<a id="profile-link" target="_blank" href="https://uk.trustpilot.com/review/www.mowdirect.co.uk?												utm_medium=trustbox&amp;utm_source=Mini">
						<img src="https://www.mowdirect.co.uk/help-and-advice/wp-content/uploads/2023/10/Screenshot-2023-10-25-105646.png" >
						</a>
					</div>
				</div>
				<div class="column is-6">
					 <h3 class="footer-menu-title">Categories</h3>
					 <div id="main-menu" class="navbar-menu is-active footer-menu">
	                
						<?php
						wp_nav_menu( array(
							'theme_location'  => 'footer-menu',
							'depth'           => '1',
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => '',
							'menu_id'         => '',
							'items_wrap'      => '%3$s',
							'walker'          => new IKnowledgebaseBase_Walker_Nav_Menu(),
							'fallback_cb'     => '',
						) );
						?>

	            	</div>
				</div>
	           
            </div>
            
            <div class=" copyright pl-0">
            	<p>
				<?php
				$iknowledgebase_option = get_option( 'iknowledgebase_settings', '' );
				if ( ! empty( $iknowledgebase_option['footer_text'] ) ) {
					echo esc_attr( $iknowledgebase_option['footer_text'] );
				} else {
					echo '&copy; ' . esc_attr( date_i18n( esc_attr__( 'Y', 'iknowledgebase' ) ) ) . ' ' . esc_attr( get_bloginfo( 'name' ) );
				}
				?></p>
            </div>
        </div>
    

</footer>

<?php wp_footer(); ?>
</body></html>
