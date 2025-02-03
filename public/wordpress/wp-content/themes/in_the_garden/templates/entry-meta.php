<time class="updated" datetime="<?php echo get_the_time('c'); ?>"><?php echo get_the_date(); ?></time>
<p class="byline author vcard"><?php echo __('By', 'roots'); ?> <a href="<?php echo get_author_posts_url($post->post_author); ?>" rel="author" class="fn"><?php echo the_author_meta('display_name', $post->post_author); ?></a></p>
