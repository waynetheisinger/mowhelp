<article class='hentry <?php echo mowtalk()->article_class(get_the_date( ));?>'>
  <header>
    <h3><?php the_title();?></h3>
  </header>
  <?php get_template_part("templates/entry", "meta");?>
  
  <div class="featured-image">
    <?php the_post_thumbnail("featured_image"); ?>
  </div>
  <div class="entry-content">
    <?php echo apply_filters('get_the_excerpt', get_post($post->ID)->post_content); ?>
  </div>
</article>
