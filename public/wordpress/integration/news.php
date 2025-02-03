<?php 
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);

//bring in the public namespace
require_once('../wordpress/wp-load.php');
//bring in the private namespace
require_once(ABSPATH . '/wp-admin/includes/admin.php');
?>
<div id="news">
          <h6>See all: 
          <?php 
		  $newsCategoryID = $_GET["catid"]; 
		  //now translate into wordpress categories
		  switch ($newsCategoryID) {
				case 2:
					echo "<a href=\"/blog/category/lawn-mowers-2/\">Lawnmower News:</a>";
					$wpCategoryID = 281;
					break;
				case 3:
					echo "<a href=\"/blog/category/lawn-garden-tractors/\">Lawn Tractor News:</a>";
					$wpCategoryID = 307;
					break;
				case 4:
					echo "<a href=\"/blog/category/leaf-blowers-vacuums/\">Vacuum &amp; Blower News:</a>";
					$wpCategoryID = 178;
					break;
				case 5:
					echo "<a href=\"/blog/category/brushcutters-and-trimmers/\">Brushcutter &amp; Trimmer News:</a>";
					$wpCategoryID = 573;
					break;
				case 6:
					echo "<a href=\"/blog/category/hedgetrimmers-and-hedgecutters/\">Hedgetrimmer &amp; Hedgecutter News:</a>";
					$wpCategoryID = 557;
					break;
				case 7:
					echo "<a href=\"/blog/category/chainsaws/\">Chainsaw News:</a>";
					$wpCategoryID = 41;
					break;
				case 8:
					echo "<a href=\"/blog/category/cultivators-and-tillers/\">Cultivator &amp; Tiller News:</a>";
					$wpCategoryID = 586;
					break;
				case 9:
					echo "<a href=\"/blog/category/chippers-shredders/\">Chipper &amp; Shredder News:</a>";
					$wpCategoryID = 490;
					break;
				case 10:
					echo "<a href=\"/blog/category/other-garden-machinery/\">Other Garden Machinery News:</a>";
					$wpCategoryID = 87;
					break;
				default:
       				echo "<a href=\"/blog/\">Gardening News:</a>";
      				
			}
		  ?>   
          </h6>
          <div id="newsContent">
          <p class="noneLinkLink" style="margin:0;"><a href="/blog/" class="noneLinkLink">Blog Homepage.</a></p>
          <?php
		  	if($newsCategoryID>0)
		  	{
			  	$recent_posts = get_posts("numberposts=5&category=$wpCategoryID");
			}else{
				$recent_posts = get_posts("numberposts=5");  
		  	}
      foreach($recent_posts as $post){
	  setup_postdata($post);
	  	   $printTitle = $post->post_title;
		   $printTitle = $title = str_replace("Â.","-",$printTitle);
		   $printTitle = $title = str_replace("Â£","£",$printTitle);
		   $printTitle = $title = str_replace('â..',"-",$printTitle);
      echo '<p>'.date("d-m-Y",strtotime($post->post_date)).': <a href="' . get_permalink() . '" title="Look '.$post->post_title.'" >' .$printTitle.'</a> </p> ';
      } 
?>
            <p class="noneLinkLink"><a href="blog/feed/" class="noneLinkLink"><img src="/acatalog/rssFeedTitle.gif" alt="" /></a></p>
            <div id="newsPaperContainer">
              <div id="newspaper"> <img src="/acatalog/newspaper.png" alt="RSS Symbol and a Newspaper picture"/> </div>
            </div>
          </div>
        </div>
