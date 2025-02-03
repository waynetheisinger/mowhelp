<?php

namespace MowDirect\Support;

class Query {

  public $args = [];
  private $allowed = ["category", "limit", "only_sticky", "ignore_stickiness", "offset"];
  private $query;

  public function __construct(){
    $this->args = new QueryArgs();
  }

  public function __call($method, $params){
    if(array_search($method, $this->allowed) !== FALSE){
      call_user_func_array( array($this->args, $method), $params );
      return $this;
    } else {
      throw new \Exception("Missing method $method");
    }
  }
  
  public function create(){
    if(!$this->query){
      $this->query = new \WP_Query($this->args->get());
    }
    return $this->query;
  }

}

class QueryArgs {
  private $args;

  public function __construct(){
    $this->args = [];
  }

  public function get(){
    return $this->args;
  }

  public function category($cat){
    $this->args["category_name"] = $cat;
    return $this;
  }
  public function limit($limit){
    $this->args["posts_per_page"] = $limit;
    return $this;
  }
  public function only_sticky(){
    $this->args["post__in"] = get_option("sticky_posts");
    return $this;
  }
  public function no_sticky(){
    $this->args["post__not_in"] = get_option("sticky_posts");
    return $this;
  }
  public function ignore_stickiness($bool=true){
    $this->args["ignore_sticky_posts"] = $bool;
    return $this;
  }
  public function offset($num){
    $this->args["offset"] = $num;
    return $this;
  }
  public function post_type($type){
    $this->args["post_type"] = $type;
    return $this;
  }
  // TODO - improve the api to support meta_query style
  public function meta($k, $v){
    $this->args["meta_key"] = $k;
    $this->args["meta_value"] = $v;
    return $this;
  }
  public function orderby($orderby){
    $this->args["orderby"] = $orderby;
    return $this;
  }
  public function order($order){
    $this->args["order"] = $order;
    return $this;
  }
  
}

class API {

  protected function __construct(){
    
  }

  public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();

        }

        return $instance;
    }

  public function query(){
    return new Query;
  }

  public function get_homepage_post(){
    $qargs = new QueryArgs();
    $qargs->limit(1)->ignore_stickiness()->meta("post_priority", "homepage")->orderby("date")->order("DESC");
    $args = $qargs->get();
    $query = new \WP_Query($args);
    wp_reset_postdata();
    if(!empty($query->posts)){
      return $query->posts[0];
    } else {
      $top_posts = $this->top_posts(1);
      return $top_posts[0];
    }
  }

  public function get_sidebar_post(){
    global $post;
    $qargs = new QueryArgs();
    $qargs->limit(1)->ignore_stickiness()->meta("post_priority", "sidebar")->orderby("date")->order("DESC");
    $args = $qargs->get();
    $query = new \WP_Query($args);
    
    if(!empty($query->posts)){
      return $query->posts[0];
    } else {
      $top_posts = $this->top_posts();
      return $top_posts[1];
    }
  }

  public function get_sticky_posts($category=null, $num=5){
    $query = new QueryArgs();
    $args = $query->only_sticky()->limit($num);
    if($category){
      $args->category($category);
    }
    $sticky_posts = get_posts($args->get());
    return $sticky_posts;
  }

  public function top_posts($num=2, $category=null){
    $sticky_posts = $this->get_sticky_posts($category, 5);

    $query2 = new QueryArgs();
    $args2 = $query2->no_sticky()->limit(5);
    if($category){
      $args2->category($category);
    }

    $not_sticky_posts = get_posts($args2->get());

    $all_posts = array_merge($sticky_posts, $not_sticky_posts);
    $top_posts = array_slice($all_posts, 0, $num);

    return $top_posts;
  }

  public function article_class($d){
    $post_date = strtotime($d);
    $old_date = strtotime("January 13th 2015");
    $class = $post_date > $old_date ? "" : "old-article";
    return $class;
  }

  public function blogCategories(){

    $parentId = get_term_by('slug', "mowtalk", "category")->term_id;
    $args = ["hide_empty" => false, "child_of" => $parentId, "orderby" => "id"];
    $cats = get_terms("category", $args);

    $packed = [];
    foreach($cats as $cat){
      $name = $cat->name;
      $image = CategoryImage::image_for_term($cat->term_id, "category_image");
      $url = get_category_link($cat->term_id);
      $catinfo = ["name" => $name, "image" => $image, "url" => $url];
      array_push($packed, $catinfo);
    }
    return $packed;
  }

  public function getCategoryNameBySlug($slug){
    return get_term_by('slug', $slug, "category")->name;
  }

  public function pageContent($name){
    $args = ["post_type" => "page", "pagename" => $name];
    $q = new \WP_Query($args);
    if($q->have_posts()){
      $q->the_post();
      $content = apply_filters("the_content", get_the_content());
    }
    \wp_reset_postdata();
    return $content;
  }

}