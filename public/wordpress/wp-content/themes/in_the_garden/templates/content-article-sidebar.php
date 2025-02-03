<article class='hentry <?php echo mowtalk()->article_class(get_the_date( ));?>'>
  <header>
    <h3><?php the_title();?></h3>
  </header>
  <?php get_template_part("templates/entry", "meta");?>
  
  <div class="featured-image">
    <?php the_post_thumbnail("featured_image"); ?>
  </div>
  <div class="entry-content"> 
    <?php echo wp_trim_words( get_the_content(), 55, "<a href='" . $link . "'>&hellip;Continued</a>" ) ?>
  </div>
</article>