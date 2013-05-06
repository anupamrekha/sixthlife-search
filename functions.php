<?php
/**
 * Convert XML to an Array
 *
 * @param string  $XML
 * @return array
 */
function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    // jedziemy po tablicy
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
    return $xml_array;
}

function spost_exists($title){
	$query_pdt = "select ID from wp_posts where post_title = '{$title}'";
	$result_pdt = mysql_query($query_pdt) or die(mysql_error());
	if(mysql_num_rows($result_pdt)<=0){
		return false;
	}
	else{
		return true;
	}
}

function unique_filename($filestr){
	$i=1;
	while(spost_exists($filestr)==true){
		$filestr = $filestr.'_'.$i;
		$i++;
	}
	return $filestr;
}

function clean_fname($new_filename){
  			    $new_filename = trim($new_filename);
				$new_filename = str_replace('\'', '',$new_filename );
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
  		  		$new_filename = str_replace('#', '',$new_filename );
  		  		$new_filename = str_replace('(', '',$new_filename );  
  		  		$new_filename = str_replace(')', '',$new_filename );
  		  		$new_filename = str_replace('”', '',$new_filename );
				$new_filename = str_replace('"', '',$new_filename );
				return $new_filename;
}

//function to rename a wp attachment

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

       function getRawurls(){

        	$query = "select * from wp_themepageurls";

        	$result = mysql_query($query) or die(mysql_error());

        	$data = array();

        	while($row = mysql_fetch_assoc($result)){

        	$data[] = $row;

        			

        	} 

        	return $data;

        } 
        
        
function getNewRSS($url){
 	 $query_check = "select id from wp_envatorssurls where rss_url = '{$url}'";
 	 $result_check = mysql_query($query_check) or die(mysql_error());
 	 
 	 if(mysql_num_rows($result_check)==0){
	 $output = file_get_contents($url); 
	 
 if(!empty($output)){
	$output = mysql_escape_string($output);
 	$query = "insert into wp_envatorssurls (rss_url, content) values('{$url}', '{$output}')";
 	$result = mysql_query($query) or die(mysql_error());
 	return $result;
 	}
 	else {return 'empty';}
 }
 	else {return 'exists';}
}


function updateRSSUrls($idarray){
 	 $query = "select * from wp_envatorssurls where id IN('".join(",", $idarray)."')";
 	 $result = mysql_query($query) or die(mysql_error());
 	 $error = '';
 	 while($row = mysql_fetch_assoc($result)){
	 $output = file_get_contents($row['rss_url']);
	if(!empty($output)){
		$output = mysql_escape_string($output);
	 	$query = "update wp_envatorssurls set content = '{$output}' where id = {$row['id']}";
	 	$result = mysql_query($query) or die(mysql_error());
	 	$error .= $row['rss_url']. ' updated successfuly.<br />';
	 	}
	 	else {$error .= $row['rss_url']. ' is empty or could not be fetched.<br />';}
	 }
	 return $error;
 }	 


function productsRSS($idarray){
	$error = '';
 	 $query = "select * from wp_envatorssurls where id IN('".join(",", $idarray)."')";
 	 $result = mysql_query($query) or die(mysql_error());
	  
	while($row = mysql_fetch_assoc($result)){

		 $category = explode('/', $row['rss_url']);
		 $category = $category[count($category)-1];
		 $category = str_replace('-slash', ', ', $category);
		 $category = str_replace('-', ' ', $category);
		 $category = str_replace('.atom', '',$category );
		 $category = mysql_escape_string(ucwords($category));

		if($category!=''){  $category = mysql_escape_string(ucwords($category));}
		else{  $category = ucwords($category);}
					 
		 $products = stripslashes($row['content']);
		$products = XMLtoArray($products);
		$pdtarray = $products['FEED'];
		array_shift($pdtarray);
		array_shift($pdtarray);
		array_shift($pdtarray);
		array_shift($pdtarray);
		array_shift($pdtarray);
		array_shift($pdtarray);
		array_shift($pdtarray);
		$pdtarray = $pdtarray['ENTRY'];
		$i = 0;
		foreach($pdtarray as $item){
		//	print_r($item); 
			  $idstring = $item['ID'];
			  $id = explode('/',$idstring );
			  $id = $id[1];
			  
			  $query_chk = "select * from wp_envato where item_id = '{$id}'";
			  $result_chk = mysql_query($query_chk) or die(mysql_error());
			  
			  if(mysql_num_rows($result_chk)==0){
			  
			  $type = explode(':', $idstring);
			  $type = $type['1'];
			  $type = explode('.', $type);
			  $type = $type['0'];
			  
			  $published = substr($item['PUBLISHED'], 0, 10);
			  
			  $itemurl = $item['LINK']['HREF'];
			  if($item['TITLE']!=''){$itemtitle = mysql_escape_string($item['TITLE']);}
				else{$itemtitle = $item['TITLE'];}
			  
			  $content = $item['CONTENT']['content'];
			  $content = mysql_escape_string($content);
			  if($item['AUTHOR'][$i]['NAME']!=''){ $author = mysql_escape_string($item['AUTHOR'][$i]['NAME']);}
			else{ $author = $item['AUTHOR'][$i]['NAME'];}			 
			  
			$ch1 = curl_init();  
			curl_setopt($ch1, CURLOPT_URL, 'http://marketplace.envato.com/api/edge/item-prices:'.$id.'.json');  
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 50);  
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);  
			$ch_data1 = curl_exec($ch1);  
			curl_close($ch1); 
			$json_data1 = json_decode($ch_data1, true);
			$price = $json_data1['item-prices']['0']['price'];
			
			$ch2 = curl_init();  
			curl_setopt($ch2, CURLOPT_URL, 'http://marketplace.envato.com/api/edge/item:'.$id.'.json');  
			curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 50);  
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);  
			$ch_data2 = curl_exec($ch2);  
			curl_close($ch2); 
			$json_data2 = json_decode($ch_data2, true); 
			//print_r($json_data2);
			$preview = $json_data2 ['item']['live_preview_url'];
			$demourl = 'full_screen_preview/'.$id;
			$demourl = str_replace($id, $demourl,$itemurl );
			
			$afflink = $itemurl.'?ref=sixthlife';
			$affdemo = $demourl.'?ref=sixthlife';
			
			$query_addproduct = "insert into wp_envato(item_id, item_title, item_content, item_type,  	item_category, item_author, item_url, item_preview, item_demo, item_price, item_afflink,item_affdemo) values({$id},'{$itemtitle}','{$content}','{$type}' ,'{$category}','{$author}' ,'{$itemurl}','{$preview}','{$demourl}','{$price}','{$afflink}','{$affdemo}')";
			
			$result_addproduct = mysql_query($query_addproduct) or die(mysql_error());
			$error .= $id.'-'.$itemtitle.' inserted successfully.<br />';
 			}
 			else{
 						$error .= $id.'-'.$itemtitle.' already exists.<br />';
 			}
		$i++;
		}
	}	  	
	return $error;
}

function updateAttach($idarray){
	$query_pdt = "select id, item_id,item_title,item_preview from wp_envato where  id IN('".join(",", $idarray)."') and attachment_id = 0";
	$result_pdt = mysql_query($query_pdt) or die(mysql_error());
	
	while($row = mysql_fetch_assoc($result_pdt)){
		$new_filename = clean_fname($row['item_title']);
		
		$new_filename = unique_filename($new_filename);
		
		  $tmp = download_url( $row['item_preview'] );
	 
	     $file_array = array(
			        'name' => basename($row['item_preview']),
			        'tmp_name' => $tmp
		    		);
	
		//	print_r($file_array);
   		// Check for download errors
	    if ( is_wp_error( $tmp ) ) {
     		@unlink( $file_array[ 'tmp_name' ] );
       		//return $tmp;
     		continue;
	    	}
			
   		$id = media_handle_sideload( $file_array, 0,$new_filename );
			    // Check for handle sideload errors.
	    if ( is_wp_error( $id ) ) {
     		@unlink( $file_array['tmp_name'] );
       		//return $id;
       		continue;
			    }
		
		rename_attacment($id, urlencode($new_filename));	
				

		$query_up = "update wp_envato set attachment_id = '{$id}' where id = {$row['id']}";
		$result_up = mysql_query($query_up) or die(mysql_error());
	}
}

?>