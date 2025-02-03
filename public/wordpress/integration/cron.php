<?php
/*
 * hit file with each of the categories and save them back to the cache
 * set up a cron to do the actual producing of cache
 * magento module then includes the cached content
 * http://php.net/manual/en/function.file-put-contents.php
 */
require_once("Encoding.php");

 
$basePath =  dirname(dirname(__FILE__));
  
//needed variables
$baseCachePath = dirname(__FILE__) . "/cache/";

  function buildString($newsCategoryID) {
  $wpCategoryID = "";	  
  $string="
<map id='m_blogGuide' name='m_blogGuide'>
<area alt='Top 10 Guides' title='Top 10 Guides to Lawnmowers, Tractor Mowers & Other Garden Equipment' href='/blog/category/top-tens/' coords='0,43,195,43,195,125,0,125,0,43' shape='poly'>
<area alt='Read Our Blog' title='Read our Gardening Blog' href='/blog' coords='0,0,195,44' shape='rect'>
</map>
  <div id=\"news\">
          <h6>"; 
           
		    
		  //now translate into wordpress categories
		  switch ($newsCategoryID) {
				case 2:
					$string .= "<a href=\"/blog/category/lawn-mowers-2/\">Lawnmower News</a>";
					$wpCategoryID = 281;
					break;
				case 3:
					$string .= "<a href=\"/blog/category/lawn-garden-tractors/\">Lawn Tractor News</a>";
					$wpCategoryID = 307;
					break;
				case 4:
					$string .= "<a href=\"/blog/category/leaf-blowers-vacuums/\">Vacuum &amp; Blower News</a>";
					$wpCategoryID = 178;
					break;
				case 5:
					$string .= "<a href=\"/blog/category/brushcutters-and-trimmers/\">Brushcutter &amp; Trimmer News</a>";
					$wpCategoryID = 573;
					break;
				case 6:
					$string .= "<a href=\"/blog/category/hedgetrimmers-and-hedgecutters/\">Hedgetrimmer &amp; Hedgecutter News</a>";
					$wpCategoryID = 557;
					break;
				case 7:
					$string .= "<a href=\"/blog/category/chainsaws/\">Chainsaw News</a>";
					$wpCategoryID = 41;
					break;
				case 8:
					$string .= "<a href=\"/blog/category/cultivators-and-tillers/\">Cultivator &amp; Tiller News</a>";
					$wpCategoryID = 586;
					break;
				case 9:
					$string .= "<a href=\"/blog/category/chippers-shredders/\">Chipper &amp; Shredder News</a>";
					$wpCategoryID = 490;
					break;
				case 10:
					$string .= "<a href=\"/blog/category/other-garden-machinery/\">Other Garden Machinery News</a>";
					$wpCategoryID = 87;
					break;
				case 11:
					$string .= "<a href=\"/blog/category/mountfield/\">Discover Mountfield</a>";
					$wpCategoryID = 100;
					break;
				default:
       				$string .= "<a href=\"/blog/\">Gardening News</a>";
      				
			}
		    
  //loop through the top level categories
  $string.="</h6>
          <div id=\"newsContent\">";

   if( ($newsCategoryID > 0) && !empty($wpCategoryID) )
    {
	   $postUrl        = 'https://www.mowdirect.co.uk/blog/wp-json/wp/v2/posts/?per_page=5&categories='.$wpCategoryID; 
	   $curlConnection = curl_init();
	   curl_setopt($curlConnection, CURLOPT_URL, $postUrl);
	   curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);
	   $recentPosts = curl_exec($curlConnection);
	   curl_close($curlConnection);
	   if(!empty($recentPosts)){
		    $jsonRecentPosts = json_decode($recentPosts);
			if(!empty($jsonRecentPosts)){
				foreach($jsonRecentPosts as $recentItem) {
					$title  = $recentItem->title->rendered;
					$link   = $recentItem->link;
					$lastModifiedDate = date("d-m-Y",strtotime($recentItem->modified));
					$string.= '<p>'.$lastModifiedDate.': <a href="' . $link . '" title="Look '.$title.'" >' .$title.'</a> </p>';
				}
				return $string;
			}
	   }  
    } 
  }
   
  //build the lists
  for ($i=1; $i<=11; $i++)
  {
    $magfile = "bloglist_".$i.".html";
    $putstring = buildString($i);
    file_put_contents ($baseCachePath.$magfile, $putstring);
  }
?>
