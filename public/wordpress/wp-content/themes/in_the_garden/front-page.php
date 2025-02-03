<div class="row">
  <div class="col-sm-24 intro">
    <?php echo mowtalk()->pageContent("home"); wp_reset_postdata();?>

  </div>
</div>
<div class="row">
  <?php
  get_template_part("templates/category_grid");
  ?>
</div>
<div class="row">
 <?php get_template_part("templates/content", "home");?>
</div>