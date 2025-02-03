<?php
if(function_exists("register_field_group"))
{
  register_field_group(array (
    'id' => 'acf_post-fields',
    'title' => 'Post Fields',
    'fields' => array (
      array (
        'key' => 'field_54b923400fe7c',
        'label' => 'Post Priority',
        'name' => 'post_priority',
        'type' => 'select',
        'instructions' => 'Choose the priority of this post. Home Page will make this post appear as the main feature on the homepage (providing no newer posts have the same status). Sidebar will make this post appear in the sidebar throughout the site (again, only if no newer Sidebar posts exist). Standard will allow the post to appear in the normal Wordpress order of date descending, sticky posts first.',
        'choices' => array (
          'standard' => 'Standard',
          'sidebar' => 'Sidebar',
          'homepage' => 'Home Page',
        ),
        'default_value' => 'standard',
        'allow_null' => 0,
        'multiple' => 0,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'post',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}
?>