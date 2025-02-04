<?php
/**
 * The template for displaying Archive pages.
 *
 * @package iknowledgebase
 */

get_header();
?>

    <section class="section">
        <div class="container">
            <div class="level">
                <div class="level-left"><?php iknowledgebase_breadcrumbs(); ?></div>
                <div class="level-right"><?php get_search_form(); ?></div>
            </div>
            <div class="mx-auto pt-5">
                <div class="columns is-multiline mb-6">
                    <?php
                        $current_category = get_queried_object();
                        $args = array('child_of' => $current_category->term_id);
                        $categories = get_categories( $args );

                    foreach ($categories as $sub_cat) {
                        echo '<div class="column is-3-widescreen is-6-desktop is-12-touch sub-cat-grid">';

                        echo "<a class=\"box panel-block is-radiusless\" href='" . get_category_link( $sub_cat->term_id ) . "' style=\" border-radius: 10px !important;\"> <span class=\"panel-icon icon-folder\"></span>" . $sub_cat->name . "<span class=\"right-arrow\"></span></a>";
                        echo '</div>';

                    }

                    ?>

                </div>
            </div>

        </div>

    </section>

<?php get_footer(); ?>