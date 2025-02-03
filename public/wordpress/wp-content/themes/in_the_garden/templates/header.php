<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="divider">
    <div class='social-bar'>
      <div class="container">
        <div class="icons">
          <a href='https://www.facebook.com/Mowdirect/' target='_blank'><img src='<?php echo get_stylesheet_directory_uri();?>/assets/img/icon-facebook.png' alt='Facebook'></a>
          <a href='https://twitter.com/mowdirect' target='_blank'><img src='<?php echo get_stylesheet_directory_uri();?>/assets/img/icon-twitter.png' alt='Twitter'></a>
          <a href='https://plus.google.com/+MowdirectCoUk' target='_blank'><img src='<?php echo get_stylesheet_directory_uri();?>/assets/img/icon-gplus.png' alt='Google+'></a>
        </div>
      </div>
    </div>
    <div class="container content">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>"><img src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png' alt='MowTalk - In the Garden'></a>
      </div>

      <nav class="collapse navbar-collapse" role="navigation">
        <?php get_template_part("templates/searchform");?>
        <?php
          if (has_nav_menu('primary_navigation')) :
            wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new Roots_Nav_Walker(), 'menu_class' => 'nav navbar-nav'));
          endif;
        ?>
      </nav>
    </div>
  </div>


    <div class="monthly-offer-banner">
      <div class="container">
       <img src='<?php if ( wp_is_mobile() ) { echo get_stylesheet_directory_uri() ."/assets/img/md-slick-monthly-ms-winner-mobile.png";} else { echo get_stylesheet_directory_uri() . "/assets/img/md-slick-monthly-ms-winner-desktop.png"; } ?>' class="monthly-offer-banner-img" alt='monthly offer banner image'>
      </div>
    </div>

</header>
