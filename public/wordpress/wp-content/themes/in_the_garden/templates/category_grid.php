<?php if(!is_home()){ ?>
  <div class="col-sm-24">
    <?php if(!is_single()){ ?>
    <h1 class='category-header'><?php echo roots_title();?></h1>
    <?php } ?>
    <div class='category-grid-toggle'>
      <a href='#' class='btn btn-large btn-default'>SHOW CATEGORIES</a>
    </div>
  </div>
<?php } ?>

<section class="category-grid">
    <?php
      $categories = mowtalk()->blogCategories();
      foreach($categories as $cat){?>
        <a href='<?php echo $cat["url"] ?>' class='col-xs-12 col-sm-6 item'>
          <div class="content">
            <img src='<?php echo $cat["image"]?>' alt='<?php echo $cat["name"]?>'>
            <h3><?php echo $cat["name"] ?></h3>
          </div>
        </a>
    <?php };?>
</section>
