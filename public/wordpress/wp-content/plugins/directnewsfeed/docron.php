<?php 

    //bring in the public namespace
	require_once(dirname(__FILE__) . '/../../../wp-config.php');
	//bring in the private namespace
	require_once(ABSPATH . 'wp-admin/includes/admin.php');

	//bring in the helper file
	require_once(dirname(__FILE__) . "/MagicParser.php");
	
	//get the filename
	$filename = get_option("new_feed_name");
	$format_string = "xml|INFOSTREAMRESULTS/ARTICLE/";
	
	//link to functions
	//http://codex.wordpress.org/Function_Reference/
	//link to php funcions
	//http://uk3.php.net/manual/en/
	function get_post_from_dnID($meta_value){ //open 
		global $wpdb; //globalise the database
		//now query against the directnews_id - returns null if no result
		$postID=$wpdb->get_var($wpdb->prepare("SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = 'dn__articleid' AND `meta_value` = %s",$meta_value));
		return $postID; //if there is a result return the wp_post_id
	} //close get_post_from_dnID
	
	  /**
	   * Writes a post to blog
	   *
	   *  
	   * @param   string    $title            Post title
	   * @param   string    $content          Post content
	   * @param   integer   $timestamp        Post timestamp
	   * @param   array     $category         Array of categories
	   * @param   string    $status           'draft', 'published' or 'private'
	   * @param   integer   $authorid         ID of author.
	   * @param   boolean   $allowpings       Allow pings
	   * @param   boolean   $comment_status   'open', 'closed', 'registered_only'
	   * @param   array     $meta             Meta key / values
	   * @return  integer   Created post id
	   */
	  function insertPost($title, $content, $timestamp = null, $category = null, $status = 'draft', $authorid = null, $allowpings = true, $comment_status = 'open', $meta = array())
	  {
		 
		 $args = array(
			'post_title' 	            => $title,
			'post_content'  	        => $content,
			'post_content_filtered'  	=> $content,
			'post_category'           => $category,
			'post_status' 	          => $status,
			'post_author'             => $authorid,
			'post_date'               => $timestamp,
			'comment_status'          => $comment_status,
			'ping_status'             => $allowpings
		);
		
		 //does the dn__articleid already exist in the database if so get back the post id so that we can update rather than create new
		 
		 $possPostID = get_post_from_dnID($meta["dn__articleid"]);
		 if($possPostID!=NULL)
		 {
			 $args['ID']=$possPostID;
			 
		 }
		 
		 //debugging
		 /*foreach($args as $ky => $vl)
		 {
		 	echo "key:".$ky." = value:".$vl."<br/>";
		 	if($ky=="post_category")
		 	{
		 		foreach($vl as $vlKey => $vlvl){
		 			echo "key:".$vlky." = value:".$vlvl."<br/>";
		 		}
		 	}
		 }*/
		 
		$postid = wp_insert_post($args);
		//echo "Post ID:".$postid."<br/>";
		
		//don't add the direct news id if it already exists
		if(($possPostID==NULL or $possPostID=="")&&$postid)
		 {		//echo "we got here";
		 		foreach($meta as $key => $value) {
				add_post_meta($postid, $key, $value);	
				}
		 }
		 
			
		return $postid;
	  }// close insertPost


	  //create the record handler
	  function myRecordHandler($record)
	  {     global $wpdb;
	  
	  
	  		//variables
			$pictureURL = "";
			$url = "";
			$imagename = "";
			$testImagepath = "";
			$testImageURL = "";
			$file_handle = "";
			$filestream = "";

			$wpauthorID = get_option("which_author");

			//setup variables
			$count=1;
			$wpCats = array();
			
			//hack the time
			$date = $record["DATE"];
			list($day, $month, $year) = explode('/',$date);
			$year=trim($year);
			$month=trim($month);
			$day=trim($day);
			$time=$year."-".$month."-".$day." ".$record["ARTICLE-CREATED"];
			$time=trim($time);
						
			$directNewsID=$record["ARTICLE-ID"];
			$dnArticalTitle=$record["HEADING"];
			$customURL=$record["PICTURE/CUSTOM/URL"];
			$pictureURL=$record["PICTURE/LARGE/URL"];
			$thumbnailURL=$record["PICTURE/SMALL/URL"];	
			$content=$record["CONTENTS"];
			
			
			//create an array for the categories
			//grab the first category id then
			$dnCats=array((string)$record["CATEGORIES/CATEGORY-ID"] => $record["CATEGORIES/CATEGORY"]);
			
			//loop through the remaining categories	
			while ($record["CATEGORIES/CATEGORY@".$count."-ID"]) {
				$dnCats[$record["CATEGORIES/CATEGORY@".$count."-ID"]]=$record["CATEGORIES/CATEGORY@".$count++];
			}
			//loop through and see if we have corresponding wp cats
			foreach($dnCats as $cat_name)
			{
				//echo $cat_name."<br>";
				$cat_name=trim($cat_name);
				//now make sure that the ampersand is html
				$cat_name=htmlspecialchars($cat_name);
				 //does the category exist
				if(get_cat_ID($cat_name)!=0)
				{	//copy to the wordpress array
					$wpCats[]=get_cat_ID($cat_name);
				}
				else{
					//"if ".$cat_name." doesn't exist create it<br>";
					$wpCats[]=wp_create_category($cat_name);
				} 
			}

			//echo "URL after decoding".$url."<br>";
			//echo $directNewsID."<br/>";			
			//test against the existing post
			$testPostID = get_post_from_dnID($directNewsID);
			if($testPostID==NULL)
			{
			//query the meta for direct news id
			//this will tell us wp post id if we're doing an update
			//add the meta array which will allow it to show up in the list of articals
			$meta = array(
			  'dn__articleid' => $directNewsID
			);  
			
			//debugging
			/*foreach($meta as $k => $v)
			{
				echo $k."=".$v."<br/>";
			}*/
			
			//now we should have everyting to either create or overwrite the post
			$postid = insertPost($wpdb->escape($dnArticalTitle), $wpdb->escape($content), $time, $wpCats, 'publish', $wpauthorID, true, 'open', $meta);
			//the only thing remaining is to work out how to produce the tags out of the content of the story
						//download the picture to the images directory
			 $url = trim($customURL);
			//strip the end to find out what the filename is
			$imagename = basename($url);
			$imagename = urldecode($imagename); //Decodes URL-encoded string
			//convert spaces to hyphens
			$imagename = str_replace(" ","-",$imagename);
			//echo $imagename."<br>";
			//what's the path to the upload directory?
			$upload_dir = wp_upload_dir(); 
			$testImagepath=$upload_dir['path']."/".$imagename;
			$testImageURL=$upload_dir['url']."/".$imagename;
				if((!file_exists($testImagepath))&&$url!="")
				{
					
					//open the file handler and stream
					$file_handle = fopen($customURL, "rb" );
					while (!feof($file_handle)) {
						$filestream .= fgets($file_handle);
					}		
					//now upload
					$upload = wp_upload_bits($imagename, null, $filestream);
					if($upload["error"]==false)
					{
					//if we've successfully uploaded then the filename might have changed
					$testImageURL = $upload["url"];
					$testImagepath = $upload["file"];
					}else{
					echo $upload["error"];
					}
				}			
						
				//now add it to the media library
				  $wp_filetype = wp_check_filetype($imagename, null );
				  //post title should be formed from the article title not the image name
				  $stripedTitle = strip_tags($dnArticalTitle);
				 $attachment = array(
					 'post_mime_type' => $wp_filetype['type'],
					 'post_title' => $stripedTitle,
					 'post_content' => '',
					 'post_status' => 'inherit'
				  );
				  $attach_id = wp_insert_attachment( $attachment, $testImagepath, $postid);
				  $attach_data = wp_generate_attachment_metadata( $attach_id, $testImagepath );
				  wp_update_attachment_metadata( $attach_id,  $attach_data );
				  
				  //now make sure it's assigned to the thumbnail this is done as a meta inclusion
				  update_post_meta( $postid, '_thumbnail_id', $attach_id );				  

			//strip out any html in the Artical Title
			//build html for the image and attach it to the front of the post
			$imagehtml = "<img src='".$testImageURL."' title='".$dnArticalTitle."' alt='".$dnArticalTitle."' class='alignleft' />";
			$content=$imagehtml.$content;
			$postid = insertPost($wpdb->escape($dnArticalTitle), $wpdb->escape($content), $time, $wpCats, 'publish', $wpauthorID, true, 'open', $meta);
			
			}else{
				//completely different logic if the post has been created before
				//only build the below functionality if someone complains that a direct news edits are being lost.
				//we've no way of knowing if the image has been created before
				//delete the image that is currently attached
				//create new image
				//now populate needed variables
				//only update if the post hasn't been updated internally by mowdirect - going to need a tag saying an internal edit has taken place
				//now update the existing post
				echo "post exists therefore, didn't do anything<br>";
			}
	  }
	 
	//now parse the xml file and call the record handler
	MagicParser_parse($filename,"myRecordHandler",$format);
	
	//report success
	echo "done the insert for mowdirect";
?>