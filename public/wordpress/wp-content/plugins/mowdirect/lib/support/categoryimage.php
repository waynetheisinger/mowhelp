<?php

namespace MowDirect\Support;

class CategoryImage {
  public function __construct(){
    add_action('admin_init', array($this, 'init'));
  }

  public function init(){

    $cats = ["The Lawn", "Cutting Stuff", "Growing Stuff", "Dick", "Hints & Tips", "Holly", "Silly Stuff", "When To", "Power Garden", "Our News", "Drew", "What's New"];

    /*foreach($cats as $cat){
      \wp_create_category( $cat);
    }*/
    
    add_action('category_add_form_fields',  array($this, 'add_image_field'));
    add_action('category_edit_form_fields', array($this, 'edit_image_field'));
    add_action('edit_term',   array($this, 'save_category_image' ));
    add_action('create_term', array($this, 'save_category_image'));
    add_action('delete_term', array($this, 'delete_category_image'));
    #add_filter('manage_edit-category_columns', '')
  }

  public function add_image_field(){
    wp_enqueue_media();
    $src = MOWDIRECT_PLUGIN_URL."/assets/js/categoryImages.js";
    wp_enqueue_script( "category_images", $src, "jquery", null, true );
    echo $this->image_field_markup();
  }

  public function image_field_markup(){
    $placeholder = MOWDIRECT_PLUGIN_URL."/assets/img/categoryPlaceholder.png";
    $supportingScript = $this->categoryImageSupportScript();
    $html = <<<EOT
    $supportingScript
    <div class='form-field'>
      <label for='category_image'>Category Image:</label>
      <img width="240" src='$placeholder' id='category_image_preview'>
      <input type='hidden' name='category_image' id='category_image' value=''>
      <br>
      <button class='upload_image_button button'>Upload/Select Image</button>
    </div>
EOT;
    return $html;
  }

  public function categoryImageSupportScript(){
    $plugin_url = MOWDIRECT_PLUGIN_URL;
    $script = <<<EOT
    <script type='text/javascript'>
      var mowdirect = {pluginUrl: "$plugin_url"};
    </script>
EOT;
    return $script;
  }

  public function edit_image_field($tax){
    wp_enqueue_media();
    $src = MOWDIRECT_PLUGIN_URL."/assets/js/categoryImages.js";
    wp_enqueue_script( "category_images", $src, "jquery", null, true );
    $term_id = $tax->term_id;
    $image_url = self::image_for_term($term_id, "category_image");
    $option_key = 'mowdirect_category_image'. $term_id;
    $image_text = get_option('mowdirect_category_image'.$term_id);
    $supportingScript = $this->categoryImageSupportScript();
    $html = <<<EOT
    <tr class='form-field'>
    $supportingScript
      <th scope='row' valign='top'><label for='category_image'>Category Image</label></th>
      <td><img width="240" id="category_image_preview" src="$image_url"/><br/><input type="hidden" name="category_image" id="category_image" value="$image_text" /><br />
    <button class="upload_image_button button">Upload/Select</button>
    <button class="remove_image_button button">Remove</button>
    </tr>
EOT;
    echo $html;
  }

  public function save_category_image($term_id){
    #var_dump($_POST);
    if(isset($_POST['category_image'])){
      if(empty($_POST['category_image'])){
        delete_option( 'mowdirect_category_image'.$term_id );
      } else {
        update_option('mowdirect_category_image'.$term_id, $_POST['category_image']);
      }
    }
  }

  public function delete_category_image($term_id){
    delete_option( 'mowdirect_category_image'.$term_id );
  }

  public static function image_for_term($term_id, $size='full'){
    $attachment_id = get_option('mowdirect_category_image'.$term_id);
    if(!$attachment_id){
      return MOWDIRECT_PLUGIN_URL."/assets/img/categoryPlaceholder.png";
    }
    $image = wp_get_attachment_image_src($attachment_id, $size);
    return $image[0];
  }

}