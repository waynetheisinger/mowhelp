<script type="text/javascript">
  window.qubit = window.qubit || {};
  window.qubit.blogAuthor = '<?php the_author(); ?>';
</script>
<article class='hentry <?php echo mowtalk()->article_class(get_the_date( ));?>'>
  <header>
    <h1><?php the_title();?></h1>
  </header>
  <?php get_template_part("templates/entry", "meta");?>
  
  <div class="featured-image">
    <?php the_post_thumbnail("featured_image"); ?>
  </div>
  <div class="entry-content"> 
  <?php 
    $content = str_replace('<h1>', '<h3>', $post->post_content); ?>
    <?php echo apply_filters("the_content", $content); ?>
  </div>
</article>
