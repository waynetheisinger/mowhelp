<div class="reverse">
  <div class="col-sm-9">
    <div class="sidebar">
      <section class="pod latest">
        <h3 class='boxed'>Recent Articles</h3>
        <ul class='post-list'>
        <?php 
          $recent = mowtalk()->query()->limit(5)->create(); 
          if($recent->have_posts()){
            while($recent->have_posts()){
              $recent->the_post();
              ?>
                <li><a href='<?php the_permalink(); ?>'><?php the_title();?></a></li>
              <?php
            }
          }
          wp_reset_postdata();
          ?>
        </ul>
      </section>
      <section class='newsletter'>
        <?php get_template_part("templates/newsletter_form");?>
      </section>
      <section class='second-article pod'>
       <?php 
          global $post;
          $top_posts = mowtalk()->top_posts();  
          $post = $top_posts[1];
          setup_postdata( $post );
          $link = get_permalink();
          include(locate_template( "templates/content-article-sidebar.php" ));
          wp_reset_postdata();
        ?>
      </section>
    </div>
  </div>
  <div class="col-sm-15">
    <section class="lead-article pod">
      <?php 
        while(have_posts()){
          the_post();
          get_template_part("templates/content", "article");
        }
      ?>
    </section>
  </div>
</div>
