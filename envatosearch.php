<?php



/*

Plugin Name: Sixthlife Search

Plugin URI: http://sixthlife.net/product/sixthlife-search/

Description: Envato Search to Posts product names, urls, images, descriptions.

Author: Anupam Rekha

Version: 1.0

Author URI: http://sixthlife.net

*/ 

/*     

$attacment_idarray = array();

require_once( ABSPATH . 'wp-admin/includes/image.php' );

require_once( ABSPATH . 'wp-admin/includes/file.php' );

require_once(ABSPATH . 'wp-admin/includes/media.php');



$pagetitle = "50+ Premium Social Network Wordpress Themes"; 

 $pagecontent = "";

 

 $post_if = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title like '$pagetitle'");

if($post_if < 1){

	

$ch = curl_init();  

curl_setopt($ch, CURLOPT_URL, 'http://marketplace.envato.com/api/edge/search:themeforest,wordpress,social|and|network.json');  

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  

$ch_data = curl_exec($ch);  

curl_close($ch);  

if(!empty($ch_data))  

{  

    $json_data = json_decode($ch_data, true);  

    //print_r($json_data);  

    $data_count = count($json_data['search']) -1;  

  

    for($i = 0; $i <= $data_count; $i++)  

    {   

    	$title = $json_data['search'][$i]['description'];

    	$themeurl = $json_data['search'][$i]['url']."?ref=sixthlife";

      //  echo '<li><a href="',$json_data['search'][$i]['url'],'">',$json_data['search'][$i]['description'],'</a></li>'; 	

	  $serial = $i+1;

      $pagecontent .= "<h2>{$serial}. {$json_data['search'][$i]['description']}</h2>";

		$ch1 = curl_init();  

		curl_setopt($ch1, CURLOPT_URL, 'http://marketplace.envato.com/api/edge/item:'.$json_data['search'][$i]['id'].'.json');  

		curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 5);  

		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);  

		$ch_data1 = curl_exec($ch1);  

		curl_close($ch1); 

		if(!empty($ch_data1))  

		{ 	//	print_r($json_data1);

		

  			  $json_data1 = json_decode($ch_data1, true); 

  			  $url = $json_data1 ['item']['live_preview_url'];

  			    $new_filename = str_replace('\'', '',$json_data['search'][$i]['description'] );

  			    $new_filename = trim($new_filename);

  			    $new_filename = str_replace(',', '',$new_filename );

  			    $new_filename = str_replace(':', '',$new_filename );

  			    $new_filename = str_replace(';', '',$new_filename );

  			    $new_filename = str_replace('\\', '',$new_filename );

  			    $new_filename = str_replace('&', '',$new_filename );

  			    $new_filename = str_replace('.', '',$new_filename );

  			    $new_filename = str_replace('/', '',$new_filename );

  			    $new_filename = str_replace(' ', '-',$new_filename );

  			    $new_filename = str_replace('--', '-',$new_filename );

  			    $new_filename = str_replace('--', '-',$new_filename );

  			 	$new_filename = str_replace('\"', '',$new_filename );

  			 	$new_filename = str_replace('|', '',$new_filename );

  			 	$new_filename = str_replace('+', '',$new_filename );

  			 	

			  $tmp = download_url( $url );

			    $file_array = array(

			        'name' => basename($url),

			        'tmp_name' => $tmp

			    );

			

			    // Check for download errors

			    if ( is_wp_error( $tmp ) ) {

			        @unlink( $file_array[ 'tmp_name' ] );

			        return $tmp;

			    }

			

			    $id = media_handle_sideload( $file_array, 0,$new_filename );

			    // Check for handle sideload errors.

			    if ( is_wp_error( $id ) ) {

			        @unlink( $file_array['tmp_name'] );

			        return $id;

			    }

			rename_attacment($id, urlencode($new_filename));

			    $attachment_url = wp_get_attachment_url( $id );

		

  

  			  $live_preview_url = str_replace($json_data['search'][$i]['id'], 'full_screen_preview/'.$json_data['search'][$i]['id'], $json_data['search'][$i]['url']);

				

				$content=file_get_contents($json_data['search'][$i]['url']);

				$cont_desc = explode('item-description', $content)	; 

				$cont_desc = explode('more-work',$cont_desc[1]);

				$cont_desc = strip_tags($cont_desc[0],'<p>');

				$cont_desc = first_n_words($cont_desc, 200);	

		  		$cont_desc = $cont_desc.'</p>';

  			  

  			  $links = "<a class=\"mini-butt red-buy\" title=\"{$json_data['search'][$i]['description']} View Demo\" href=\"{$live_preview_url}?ref=sixthlife\" rel=\"nofollow\">View Demo</a>      <a class=\"mini-butt red-buy\" title=\"{$json_data['search'][$i]['description']} More Info\" href=\"{$json_data['search'][$i]['url']}?ref=sixthlife\" rel=\"nofollow\">More Info</a>";

  			  

  			  $pagecontent .= "<a title=\"{$json_data['search'][$i]['description']}\" href=\"{$json_data['search'][$i]['url']}?ref=sixthlife\"><img class=\"alignleft\" title=\"{$json_data['search'][$i]['description']}\" alt=\"\" src=\"{$attachment_url}\" width=\"630\" /></a>";

  			  

  			  $pagecontent .= str_replace('itemprop="description">','', $cont_desc); 

  			  

  			  $pagecontent .= $links;

		//	 echo $imageurl = '<img src = "'.$json_data1 ['item']['live_preview_url'].'" />';



		}

    }  

 }

 

else  

{  

    echo 'Sorry, but there was a problem connecting to the API.';  

} 

//}

 //$pagecontent="a";



if($pagecontent!="" && $pagetitle!=""){

	// Create post object

  $my_post = array(

  

     'post_title' => $pagetitle,

     'post_content' => $pagecontent,

     'post_status' => 'draft',

     'post_author' => 1,

     'post_category' => array(3,355)

  );

$post_if = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title like '$pagetitle'");

if($post_if < 1){

   $post_id =  wp_insert_post( $my_post );

    

    foreach($attacment_idarray as $attacment_id){



}

}

else{



}

}

}

*/

function rename_attacment($post_ID,$newfilename){



    $post = get_post($post_ID);

    $file = get_attached_file($post_ID);

    $path = pathinfo($file);

        //dirname   = File Path

        //basename  = Filename.Extension

        //extension = Extension

        //filename  = Filename



    //$newfilename = "NEW FILE NAME HERE";

    $newfile = $path['dirname']."/".$newfilename.".".$path['extension'];



    rename($file, $newfile);    

    update_attached_file( $post_ID, $newfile );



} 



// just the excerpt

function first_n_words($text, $number_of_words) {

   // Where excerpts are concerned, HTML tends to behave

   // like the proverbial ogre in the china shop, so best to strip that

   $text = strip_tags($text);



   // \w[\w'-]* allows for any word character (a-zA-Z0-9_) and also contractions

   // and hyphenated words like 'range-finder' or "it's"

   // the /s flags means that . matches \n, so this can match multiple lines

 $text = preg_replace("/^\W*((\w[\w'-]*\b\W*){1,$number_of_words}).*/ms", '\\1', $text);



   // strip out newline characters from our excerpt

   return str_replace("\n", "", $text);

}



?>
