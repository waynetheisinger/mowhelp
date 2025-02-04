<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package iknowledgebase
 */

$post_icon = apply_filters( 'iknowledgebase_post_icon', 'icon-book' );
?>
<div class="faq-list">
    <div class="panel-block is-borderless question close-modle">
        <span class="panel-icon">
            <span class="<?php echo esc_attr( $post_icon ); ?>"></span>
        </span>
        <?php do_action( 'iknowledgebase_post_time');?>
        <h4><?php the_title(); ?></h4>
        <span class="icon-chevron-up  ml-auto"></span>
    </div>
    <div class="answer is-hidden" style="padding:.5em 1.75em;">
        <?php apply_filters( 'the_content', the_content()); ?>
    </div>
</div>

