<?php

namespace ITJL_BLOG;

class ITJL_BLOG {

    static $theme_templates = [
            //  'page-veranstaltung-filter1.php' => 'FOBI filter 1',
    ];

    public static function start() {
        self::load_classes(self::load_class());
        
        add_action('wp_enqueue_scripts', array(get_called_class(), 'load_scripts'));
    }

    public static function load_class() {
        return [
            'Helpers.php',
            'Theme-Option.php',
            'Featured-Category.php',
            'Short-Codes.php',
            'Post.php'
        ];
    }

    public static function load_scripts() {

        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        self::load_localize_script('jquery', array());
        self::load_localize_script('vendor', array('jquery'));
        self::load_localize_script('itjl', array('jquery'));
        self::load_style('style', array('parent-style'));
    }

    public static function load_localize_script($script_name, $dependancies = array(), $object_name = '', $data = '') {

        if (!file_exists(ITJL_BLOG_ASSETS_SCRIPT_PATH . $script_name . '.js')) {
            return false;
        }

        wp_enqueue_script(
                ITJL_BLOG_CLASSES_NAMESPACE . '-' . $script_name,
                ITJL_BLOG_ASSETS_SCRIPT_URI . $script_name . '.js',
                $dependancies,
                filemtime(ITJL_BLOG_ASSETS_SCRIPT_PATH . $script_name . '.js')
        );

        if (!empty($object_name && $data)) {
            wp_localize_script($script_name, $object_name, $data);
        }
    }

    public static function load_style($style_name, $dependancies = array()) {

        if (!file_exists(ITJL_BLOG_ASSETS_STYLE_PATH . $style_name . '.css')) {
            return false;
        }

        wp_enqueue_style(
                ITJL_BLOG_CLASSES_NAMESPACE . '-' . $style_name,
                ITJL_BLOG_ASSETS_STYLE_URI . $style_name . '.css',
                $dependancies,
                filemtime(ITJL_BLOG_ASSETS_STYLE_PATH . $style_name . '.css')
        );
    }

    public static function load_classes($class_list = []) {

        if (empty($class_list)) {
            return;
        }

        foreach ($class_list as $class_name) {
            $file = ITJL_BLOG_CLASSES_PATH . '/' . $class_name;
            if (!file_exists($file)) {
                return;
            }

            require $file;
        }
    }

    public static function get_template($plugin_template_path, $data = array()) {
        ob_start();
        self::load_template($plugin_template_path, $data);
        return ob_get_clean();
    }

    public static function load_template($plugin_template_path, $data = array()) {
        if (file_exists(ITJL_BLOG_TEMPLATES_PATH . $plugin_template_path)) {
            require ITJL_BLOG_TEMPLATES_PATH . $plugin_template_path;
        }
    }

    public static function get_image_url($image_path) {
        return ITJL_BLOG_ASSETS_IMAGE_URI . $image_path;
    }

    public static function the_image_url($image_path) {
        echo self::get_image($image_path);
    }

    public static function get_api_namespace() {
        return sanitize_title(ITJL_BLOG_CLASSES_NAMESPACE) . '/' . ITJL_BLOG_API_VERSION;
    }

    public static function register_templates() {
        // Add a filter to the attributes metabox to inject template into the cache.
        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {
            // 4.6 and older
            add_filter('page_attributes_dropdown_pages_args', array(get_called_class(), 'register_project_templates'));
        } else {
            // Add a filter to the wp 4.7 version attributes metabox
            add_filter('theme_page_templates', array(get_called_class(), 'add_new_template'));
        }
        // Add a filter to the save post to inject out template into the page cache
        add_filter('wp_insert_post_data', array(get_called_class(), 'register_project_templates'));
        // Add a filter to the template include to determine if the page has our 
        // template assigned and return it's path
        add_filter('template_include', array(get_called_class(), 'view_project_template'));
    }

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public static function add_new_template($posts_templates) {
        $templates = self::$theme_templates;
        if (!empty($templates)) {
            $posts_templates = array_merge($posts_templates, $templates);
        }

        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public static function register_project_templates($atts) {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
        // Retrieve the cache list. 
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }
        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, self::$theme_templates);
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);
        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public static function view_project_template($template) {

        // Get global post
        global $post;
        // Return template if post is empty
        if (!$post) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        $templates = self::$theme_templates;
        if (!isset($templates[get_post_meta(
                                $post->ID, '_wp_page_template', true
                )])) {
            return $template;
        }
        if (file_exists(get_template_directory() . get_post_meta($post->ID, '_wp_page_template', true))) {
            $file = get_template_directory() . get_post_meta($post->ID, '_wp_page_template', true);
        } else {
            $file = ITJL_BLOG_THEME_TEMPLATES_PATH . get_post_meta(
                            $post->ID, '_wp_page_template', true
            );
        }
        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {
            return $file;
        } else {
            echo $file . 'included file not found';
        }
        // Return template
        return $template;
    }

}


ITJL_BLOG::start();
