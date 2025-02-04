<?php
/**
 * The template for displaying Category pages.
 *
 * @package iknowledgebase
 */

get_header();
$iknowledgebase_settings = get_option( 'iknowledgebase_settings', false );

    $term = get_queried_object();
  
    $cat_image_url = get_field('banner_image', "category_" . $term->category_parent);
		$image_block = '';
		if(!empty($cat_image_url)){
			$image_block .= '<img class="image" src="' . $cat_image_url . '" loading="lazy" />';
		}

?>
<?php if ( empty( $iknowledgebase_settings['archive_sidebar'] ) ) : ?>
    <section class="category">
        <div class="container">
            <div class="banner">
                <?php echo $image_block; ?>
                <div class="banner-search">
                    <h1><?php echo $term->name; ?></h1>
                    <?php get_search_form(); ?>
                </div>
            </div>
            <div class="level">
                <div class="level-left"><?php iknowledgebase_breadcrumbs(); ?></div>
                <div class="level-right"><?php //get_search_form(); ?></div>
            </div>
             <h1 class="has-text-centered title"><?php the_title(); ?></h1>
            <div class="columns is-multiline is-centered pt-5 is-flex-direction-row<?php //iknowledgebase_sidebar_location(); ?>">
                <div class="column is-full-touch is-two-thirds-desktop">
                    <div class="box is-mobile">
						<?php iknowledgebase_posts_sorter(); ?>
                    </div>
					<?php if ( have_posts() ) : ?>
                        <div class="panel has-background-white">
							<?php
							iknowledgebase_get_sticky_posts_in_category();
							// Load posts loop.
							while ( have_posts() ) {
								the_post();
								get_template_part( 'template-parts/content', 'list' );
							}
							?>
                        </div>
						<?php iknowledgebase_the_posts_pagination(); ?><?php endif; ?>
                </div>
                <div class="column is-full-touch"><?php get_sidebar(); ?></div>
            </div>

        </div>

    </section>
<?php else: ?>

    <section >
        <div class="container">
            <div class="level">
                <div class="level-left"><?php iknowledgebase_breadcrumbs(); ?></div>
                <div class="level-right"><?php //get_search_form(); ?></div>
            </div>
             <h1 class="has-text-centered title"><?php the_title(); ?></h1>
            <div class="is-max-w-2xl mx-auto pt-5">
                <div class="box is-mobile">
					<?php iknowledgebase_posts_sorter(); ?>
                </div>
				<?php if ( have_posts() ) : ?>
                    <div class="panel has-background-white">
                        <h2 class="panel-heading"><?php the_archive_title(); ?></h2>
						<?php
						iknowledgebase_get_sticky_posts_in_category();
						// Load posts loop.
						while ( have_posts() ) {
							the_post();
							if( is_sticky() ){
							    continue;
							}
							get_template_part( 'template-parts/content', 'list' );
						}
						?>
                    </div>
					<?php iknowledgebase_the_posts_pagination(); ?><?php endif; ?>
            </div>
        </div>

        </div>

    </section>

<?php endif; ?>


<?php get_footer(); ?>
