<?php



/*

Plugin Name: Sixthlife Search

Plugin URI: http://sixthlife.net

Description: Sixthlife Search to Database

Author: Anupam Rekha

Version: 1.0

Author URI: http://sixthlife.net

*/
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'functions.php');

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'simple_html_dom.php');



$url = '';

$from = 1;

$to = 1;



$themegallery_owner = '';



/************  First Page ********************/

$themegallery_start = '';

$themegallery_end = '';



$theme_start = '';

$theme_end = '';



$themeurl_start = '';

$themeurl_end = '';



$title_start = '';

$title_end = '';



$price_start = '';

$price_end = '';



$cont_start = '';

$cont_end = '';



$category_start = '';

$category_end = '';



$preview_start = '';

$preview_end = '';



/************  Inner Page ********************/



$itheme_start = '';

$itheme_end = '';



$title_start = '';

$title_end = '';



$price_start = '';

$price_end = '';



$cont_start = '';

$cont_end = '';



$category_start = '';

$category_end = '';



$preview_start = '';

$preview_end = '';



/************  End Page ********************/

$affurl_common = '';

$affurl_postfix = '';



$affdemourl_common = '';

$affdemourl_postfix ='';





/**********Plugin Settings ******************/





/*******Check if page content is inserted in raw data *************/

/*Submit theme gallery url from which themses will be extractred to be saved in db */

function submitURL(){

		$error = '';

if(isset($_POST['submit_url'])){

	$start_url =  $_POST['starter_url'];

	$to = $_POST['start_to'];

	$from = $_POST['start_from'];

	$themeby = $_POST['theme_by'];





if($start_url == '')	{$error .= 'URL Cannot be blank.';}

else if(is_numeric($from)==false || is_numeric($to)==false){$error .= 'From and To should be numeric.';}

else if($themeby ==''){$error .= 'Please Enter Theme Author or Seller.';}

else{

for($i=$from; $i<=$to;){

	

	if(strpos($start_url, '{counter}') !== false){

		$url = str_replace('{counter}', $i, $start_url);

	}

	else{

		$url = $start_url;

	}

	

	$query = "select * from wp_themepageurls where url = '{$url}'";

	$result = mysql_query($query) or die(mysql_error());

	

	if(mysql_num_rows($result)<=0){

		$rawdata = file_get_contents($url);

		if($rawdata != ''){

		$rawdatai = mysql_escape_string($rawdata);

		$query_ins = "insert into wp_themepageurls (id, site, url, rawdata) values('','{$themeby}','{$url}','{$rawdatai}' ) ";

		$result_ins = mysql_query($query_ins) or die(mysql_error());

		if($result_ins){$error .= 'URL: '.$url.' added successfully. <br />';}

		}

		else{

		 $error .= 	'URL :'.$url. ' could not fetch content.</br />';

		}

	}

	else{

		$error .= 'URL :'.$url. ' already exists.</br />';

	}	

	$i++;

}// end for



} //end if

} // end post

return $error;

}


/*submit details of each theme to be added to themespdts */
function submitDetails(){

		$error = '';

			$html = new simple_html_dom();

	if(isset($_POST['submit_thse'])){

	

	$starturl_id = $_POST['select_urls'];

	$starturl_id =  mysql_escape_string($starturl_id);

	

	$eachtheme_iden = $_POST['eachtheme_element'];

	$eachthemeurl_iden = $_POST['eachtheme_url'];

	

	$eachthemedemo_iden = $_POST['eachtheme_demo'];

	$eachthemetitle_iden = $_POST['eachtheme_title'];

	$eachthemeprice_iden = $_POST['eachtheme_price'];

	$eachthemepreview_iden = $_POST['eachtheme_preview'];

	$eachthemedescription_iden = $_POST['eachtheme_desc'];

	$eachthemecategory_iden = $_POST['eachtheme_cat'];

	

	$innerthemedemo_iden = $_POST['innertheme_demo'];

	$innerthemetitle_iden = $_POST['innertheme_title'];

	$innerthemeprice_iden = $_POST['innertheme_price'];

	$innerthemepreview_iden = $_POST['innertheme_preview'];

	$innerthemedescription_iden = $_POST['innertheme_desc'];

	$innerthemecategory_iden = $_POST['innertheme_cat'];

		

	$themepagebaseurl = $_POST['base_urltoattach'];

	$themepageaffstring = $_POST['base_affstring'];	

	$themepagedefcat = $_POST['default_cat'];	

	

	if($starturl_id == '')	{$error .= 'Start URL Cannot be blank.';}

	else if($eachtheme_iden == '')	{$error .= 'Each theme identifier cannot be blank.';}

	

	else if($eachthemeurl_iden == '')	{$error .= 'Each theme URL identifier cannot be blank.';}

	else if($eachthemedemo_iden == '' && $innerthemedemo_iden == '')	{$error .= 'Each theme Demo and Inner theme identifier both cannot be blank.';}		

	else if($eachthemetitle_iden == '' && $eachthemetitle_iden == '')	{$error .= 'Each and Inner theme Title identifier both cannot be blank.';}	

	else if($eachthemepreview_iden == '' && $innerthemepreview_iden == '')	{$error .= 'Each theme and Inner theme Preview identifier both cannot be blank.';}	

//	else if($eachthemedescription_iden == '' && $innerthemedescription_iden == '')	{$error .= 'Each theme and Inner theme Description identifier both cannot be blank.';}	

//	else if($eachthemecategory_iden == '' && $innerthemecategory_iden == '')	{$error .= 'Each theme and Inner theme Category identifier both cannot be blank.';}

	

	else{

		

	$query_gr = "select * from wp_themepageurls where id = {$starturl_id}";

	$result_gr = mysql_query($query_gr) or die(mysql_error());

	$rawdata = mysql_result($result_gr, 0, 'rawdata');

	$rawdata = stripslashes($rawdata);

	$themeby = mysql_result($result_gr, 0, 'site');

	$start_url =  mysql_result($result_gr, 0, 'url');

	

	$urlpc = explode('1', $start_url);

	

	

$query_up = "update wp_themepageurls set baseurl = '{$themepagebaseurl}', affstring = '{$themepageaffstring}', defaultcat = '{$themepagedefcat}', ethemeelem = '{$eachtheme_iden}',ethemeurl = '{$eachthemeurl_iden}',ethemedemo = '{$eachthemedemo_iden}',ethemetitle = '{$eachthemetitle_iden}',ethemeprice = '{$eachthemeprice_iden}',ethemepreview = '{$eachthemepreview_iden}',ethemedesc = '{$eachthemedescription_iden}',ethemecat = '{$eachthemecategory_iden}',ithemedemo = '{$innerthemedemo_iden}',ithemetitle = '{$innerthemetitle_iden}',ithemeprice = '{$innerthemeprice_iden}',ithemepreview = '{$innerthemepreview_iden}',ithemedesc = '{$innerthemedescription_iden}',ithemecat = '{$innerthemecategory_iden}' where url like '%{$urlpc[0]}%'";

mysql_query($query_up) or die(mysql_error());

 

	$html->load($rawdata); 

	foreach($html->find($eachtheme_iden) as $article) {

		

    $themeurl = $article->find($eachthemeurl_iden, 0)->href;



 

   // echo $themeurl.'anu<br />';

 	$query_themeexists = "select id from wp_themepdts where url = '{$themeurl}'";

 	$result_themeexists = mysql_query($query_themeexists) or die(mysql_error());

	

 	if(mysql_num_rows($result_themeexists)==0){

    if($eachthemedemo_iden!=''){$themedemo = $article->find($eachthemedemo_iden, 0)->href;}

    if($eachthemetitle_iden!=''){$themetitle = $article->find($eachthemetitle_iden, 0)->plaintext;}

    if($eachthemeprice_iden!=''){$themeprice = $article->find($eachthemeprice_iden, 0)->plaintext;}

    if($eachthemepreview_iden!=''){ $themepreview = $article->find($eachthemepreview_iden, 0)->src;}

    if($eachthemedescription_iden!=''){ $themedescription = $article->find($eachthemedescription_iden, 0)->plaintext;}   

    if($eachthemecategory_iden!=''){  $themecategory = $article->find($eachthemecategory_iden, 0)->plaintext;}	

	

		if(isset($themepagebaseurl) && $themepagebaseurl!='' && strpos($themeurl,'http')===false && strpos($themeurl,'www.')===false){

			$themeurl =$themepagebaseurl.$themeurl;

		}

		else if(strpos($themeurl,'http')===false && strpos($themeurl,'www.')===false){

		$parse = parse_url($start_url);

    	$themeurl = 'http://'.$parse['host'].$themeurl;

    }	

		$innhtml = file_get_html($themeurl);

		$themerawdata =$innhtml ;



	    if( $innerthemedemo_iden!='' ){  $themedemo = $innhtml->find($innerthemedemo_iden, 0)->href;}

	    if( $innerthemetitle_iden!=''){$themetitle = $innhtml->find($innerthemetitle_iden, 0)->plaintext;}

	    if( $innerthemeprice_iden!=''){$themeprice = $innhtml->find($innerthemeprice_iden, 0)->plaintext;}

	    if( $innerthemepreview_iden!=''){ $themepreview = $innhtml->find($innerthemepreview_iden, 0)->src;}

	    if(  $innerthemedescription_iden!=''){ $themedescription = $innhtml->find($innerthemedescription_iden, 0)->plaintext;}   

	    if( $innerthemecategory_iden!=''){ $themecategory = $innhtml->find($innerthemecategory_iden, 0)->plaintext;}



			$themerawdata =mysql_escape_string($innhtml );

		if($themedemo!=''&& isset($themepagebaseurl) && $themepagebaseurl!='' && strpos($themedemo,'http')===false && strpos($themedemo,'www.')===false){

			$themedemo =$themepagebaseurl.$themedemo;

		}

		else if(	$themedemo!=''&& strpos($themedemo,'http')===false && strpos($themedemo,'www.')===false){

		$parse = parse_url($start_url);

    	$themedemo = 'http://'.$parse['host'].$themedemo;

    }			

		$new_filename = clean_fname($themetitle);	

		$new_filename = unique_filename($new_filename);

		$previewimg = trim($themepreview);

		

		if(isset($themepagebaseurl) && $themepagebaseurl!='' && strpos($previewimg,'http')===false && strpos($previewimg,'www.')===false){

			$previewimg =$themepagebaseurl.$previewimg;

		}

		else if(strpos($previewimg,'http')===false && strpos($previewimg,'www.')===false){

		$parse = parse_url($start_url);

    	$previewimg = 'http://'.$parse['host'].$previewimg;

    }

		  $tmp = download_url($previewimg );

	 

	     $file_array = array(

			        'name' => basename($previewimg),

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

		$themedemo = mysql_escape_string($themedemo);

		$themetitle = mysql_escape_string($themetitle);

		$themeprice = mysql_escape_string($themeprice);

		$themepreview = mysql_escape_string($themepreview);

		$themedescription = mysql_escape_string($themedescription);

		$themecategory = mysql_escape_string($themecategory);

		

		if(isset($themepagedefcat) && $themepagedefcat!=""){$themecategory = $themepagedefcat;} 

		if( isset($themepageaffstring) && $themepageaffstring !=""){

		$afflink = $themeurl.$themepageaffstring;

		if($themedemo!=''){

		$affdemolink = $themedemo.$themepageaffstring; }	

		}	

		else{

					$afflink = '';

				$affdemolink = ''; 

		}

//{$themerawdata}

	$query_ins1 = "insert into wp_themepdts values('','{$themeby}', '{$themetitle}','{$themeurl}','{$themepreview}','{$id}', '{$themerawdata}','{$themedescription}','{$themeprice}','$themecategory','$afflink','$affdemolink')";	

	$result_ins1 = mysql_query($query_ins1) or die(mysql_error()); 

	if($result_ins1){$error .= $themeurl.' added successfully.<br />';}

		}

		else{

			$error .=  $themeurl.' already exists.<br />';

		}	    	

		}

		

	}

	

}

if($error!=''){

		 		$error .= "<h5>Starter URL: $start_url</h5>";	

}

	 	



	return $error;

}



function thse_scripts_general() {

    wp_enqueue_script('sixthlife-search',plugins_url('/js/general.js', __FILE__), array('jquery'));

}

add_action('admin_init', 'thse_scripts_general'); 



// create custom plugin settings menu

add_action('admin_menu', 'thse_create_menu');



function thse_create_menu() {

	

	//create new top-level menu

	add_menu_page('Theme Search', 'Theme Search', 'administrator', __FILE__, 'thse_settings_page',plugins_url('sixthlife-search/images/icon.png'));



	//call register settings function

	add_action( 'admin_init', 'register_thsesettings' );

}





function thse_settings_page() {

?>

<div class="wrap">

<div id="icon-link-manager" class="icon32" ></div>

<h2>Theme Search</h2>

<h3>Submit URL</h3>

<?php $error =  submitURL(); ?>

<?php if($error!=''){echo'<div id="message" class="updated" >' .$error.'</div>';}

?>

<form method="post" action="" >



<table class="form-table">

<tbody>     

        <tr valign="top">

		

		<th scope="row"><label for="">Starter URL</label></th> <td>

        <input type="text" class="regular-text" name="starter_url" value="<?php if(isset($_POST['starter_url'])){  echo $_POST['starter_url'];} ?>" /> 

		<label for="">Page Counter From</label>



		 <input type="text"  name="start_from" class="small-text" value="<?php if(isset($_POST['start_from'])){  echo $_POST['start_from'];} ?>" />

		 		<label for="">Page Counter To</label>

		 		 <input type="text" class="small-text" name="start_to" value="<?php if(isset($_POST['start_to'])){  echo $_POST['start_to'];} ?>" />

        <p class="description">The Webpage From which to get the Themes. Ex. http://studiopress.com/gallery/page/{counter} where counter is 1 to 1 for single page. </p>

       </td>

         </tr>

		 <tr valign="top">

        <th  scope="row"><label for="">

  Theme Author/ Seller</label></th><td>

        <input type="text" class="regular-text" name="theme_by" value="<?php if(isset($_POST['theme_by'])){  echo $_POST['theme_by'];} ?>" />

        <p class="description">The themes author or seller Ex. cssigniter, mojothemes etc. Use consistent names without spaces. </p>

               </td></tr>

      		 <tr valign="top">

        <th  scope="row">

  

               </td></tr>     

</tbody>

</table>

<?php submit_button('Submit', 'primary', 'submit_url', '', '' ); ?>

</form>

</div>

<?php } ?><?php



add_action('admin_menu', 'register_thse_addthemes_submenu_page');



function register_thse_addthemes_submenu_page() {





	add_submenu_page( 'sixthlife-search/sixthlifesearch.php', 'Add Themes', 'Add Themes', 'manage_options', 'thse_addthemes_submenu_page','thse_addthemes_submenu_page' ); 

}



function thse_addthemes_submenu_page() {

	

	$rawdata = getRawurls(); 

	

	//print_r($rawdata);



	?>

	<div class="wrap">

<div id="icon-link-manager" class="icon32" ></div>

<h2>Theme Search</h2>

<h3>Add Themes</h3>



<?php $error =  submitDetails(); ?>

<?php if($error!=''){echo'<div id="message" class="updated" >' .$error.'</div>';}

?>

<form method="post" action="" >

<h4>From the Main Page</h4>

<table class="form-table">

<tbody>     

        <tr valign="top">

		

		<th scope="row"><label for="">Select URLs</label></th> <td>

        <select name="select_urls" id="select_urls" multiple="true" size="6" onchange="javascript:loadSelectors();">

        <?php foreach($rawdata as $data){?>

        <option value="<?php echo $data['id']; ?>"><?php echo $data['url']; ?></option>

        <?php } ?>

		</select>

        <p class="description">Select Pages from which to fetch themes. </p>

       </td>

         </tr>

         

  		 <tr valign="top">

        <th  scope="row"><label for="">Base URL</label> </th><td>

        

		<input type="text" class="regular-text" name="base_urltoattach" id="base_urltoattach"  value="<?php if(isset($_POST['base_urltoattach'])){  echo $_POST['base_urltoattach'];}  ?>"  />

	

	       <p class="description"> If the Images and demo urls are not absolute base url for ex. http://www.obx-themes.com/. Leave blank if URLS are absolute.</p>

   

               </td></tr>       

         

	 <tr valign="top">

        <th  scope="row"><label for="">Affiliate URL String</label> </th><td>

        

		<input type="text" class="regular-text" name="base_affstring" id="base_affstring"  value="<?php if(isset($_POST['base_affstring'])){  echo $_POST['base_affstring'];}  ?>"  />

	

	       <p class="description"> If there is common string to be attached to affiliate url like ?r=sixthlife</p>

   

               </td></tr>  

               

	 <tr valign="top">

        <th  scope="row"><label for="">Default Category</label> </th><td>

        

		<input type="text" class="regular-text" name="default_cat" id="default_cat"  value="<?php if(isset($_POST['default_cat'])){  echo $_POST['default_cat'];}  ?>"  />

	

	       <p class="description"> If Category String is not there and you want to enter a default category for the themes like: Wordpress</p>

   

               </td></tr>  

			           

         

		 <tr valign="top">

        <th  scope="row"><label for="">Each Theme Element</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_element" id="eachtheme_element" onblur="javascript:checkTheme(this);" value="<?php if(isset($_POST['eachtheme_element'])){  echo $_POST['eachtheme_element'];} ?>"  />

	

	       <p class="description" id="chk_theme"></p>

        <p class="description">Ex: div.theme-item  </p>

               </td></tr>

      		 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme URL</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_url" id="eachtheme_url" value="<?php if(isset($_POST['eachtheme_url'])){  echo $_POST['eachtheme_url'];} ?>" onblur="javascript:checkThemeurl(this);" /> 

		

		<p class="description" id="chk_themeurl"></p>

	

	      

        <p class="description">Each Theme URL.  </p>

               </td></tr>  

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme Demo</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_demo" id="eachtheme_demo" onblur="javascript:checkThemedemo(this);" value="<?php if(isset($_POST['eachtheme_demo'])){  echo $_POST['eachtheme_demo'];} ?>" />

	

	        <p class="description" id="chk_themedemo"></p>   

        <p class="description">Each Theme Demo.  </p>

               </td></tr>      

	 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme Title</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_title" id="eachtheme_title" value="<?php if(isset($_POST['eachtheme_title'])){  echo $_POST['eachtheme_title'];} ?>" onblur="javascript:checkThemetitle(this);" />

	

	    <p class="description" id="chk_themetitle"></p>  

        <p class="description">Each Theme Title.  </p>

               </td></tr> 

  

  	 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme Price</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_price" id="eachtheme_price" onblur="javascript:checkThemeprice(this);" value="<?php if(isset($_POST['eachtheme_price'])){  echo $_POST['eachtheme_price'];} ?>" />

	

	        <p class="description" id="chk_themeprice"></p>   

        <p class="description">Each Theme Price.  </p>

               </td></tr> 

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme Preview</label> </th><td>

        



		<input type="text" class="regular-text" name="eachtheme_preview" id="eachtheme_preview" onblur="javascript:checkThemepreview(this);" value="<?php if(isset($_POST['eachtheme_preview'])){  echo $_POST['eachtheme_preview'];} ?>" />

	

	     <p class="description" id="chk_themepreview"></p>    

        <p class="description">Each Theme Preview Image.  </p>

               </td></tr> 

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme Description</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_desc" id="eachtheme_desc" onblur="javascript:checkThemedescription(this);" value="<?php if(isset($_POST['eachtheme_desc'])){  echo $_POST['eachtheme_desc'];} ?>" />

	

	    <p class="description" id="chk_themedescription"></p> 

        <p class="description">Each Theme Description .  </p>

               </td></tr> 

  	 <tr valign="top">

        <th  scope="row">

  <label for="">Each Theme Category</label> </th><td>

        

		<input type="text" class="regular-text" name="eachtheme_cat"  id="eachtheme_cat" onblur="javascript:checkThemecategory(this);"  value="<?php if(isset($_POST['eachtheme_cat'])){  echo $_POST['eachtheme_cat'];} ?>" />

	

	    <p class="description" id="chk_themecategory"></p> 	      

        <p class="description">Each Theme Category.  </p>

               </td></tr> 

   

   

               

</tbody>

</table>





<h4>From the Inner Page</h4>

<table class="form-table">

<tbody>     

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Inner Theme Demo</label> </th><td>

        

		<input type="text" class="regular-text" name="innertheme_demo" id="innertheme_demo"  onblur="javascript:checkThemeinnerdemo(this);" value="<?php if(isset($_POST['themeinner_demo'])){  echo $_POST['themeinner_demo'];} ?>" />

	

 		<p class="description" id="chkinner_demo"></p> 	   

        <p class="description">Inner Theme Demo.  </p>

               </td></tr> 

  

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Inner Theme Title</label> </th><td>

        

		<input type="text" class="regular-text" name="innertheme_title" id="innertheme_title"  onblur="javascript:checkThemeinnertitle(this);" value="<?php if(isset($_POST['innertheme_title'])){  echo $_POST['innertheme_title'];} ?>" />

	

	<p class="description" id="chkinner_title"></p> 	

	      

        <p class="description">Inner Theme Title.  </p>

               </td></tr> 

  

  	 <tr valign="top">

        <th  scope="row">

  <label for="">Inner Theme Price</label> </th><td>

        

		<input type="text" class="regular-text" name="innertheme_price"  id="innertheme_price"  onblur="javascript:checkThemeinnerprice(this);"  value="<?php if(isset($_POST['innertheme_price'])){  echo $_POST['innertheme_price'];} ?>" />

	

	    	<p class="description" id="chkinner_price"></p>   

        <p class="description">Inner Theme Price.  </p>

               </td></tr> 

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Inner Theme Preview</label> </th><td>

        

		<input type="text" class="regular-text" name="innertheme_preview" id="innertheme_preview"  onblur="javascript:checkThemeinnerpreview(this);"  value="<?php if(isset($_POST['innertheme_preview'])){  echo $_POST['innertheme_preview'];} ?>" />

		

		<p class="description" id="chkinner_preview"></p>   

	      

        <p class="description">Inner Theme Preview Image.  </p>

               </td></tr> 

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Inner Theme Description</label> </th><td>

        

		<input type="text" class="regular-text" name="innertheme_desc" id="innertheme_desc" onblur="javascript:checkThemeinnerdescription(this);" value="<?php if(isset($_POST['innertheme_desc'])){  echo $_POST['innertheme_desc'];} ?>" />

	

	      	<p class="description" id="chkinner_description"></p>  

        <p class="description">Each Theme Description .  

        </tr>

        

    	 <tr valign="top">

        <th  scope="row">

  <label for="">Inner Theme Category</label> </th><td>

        

		<input type="text" class="regular-text" name="innertheme_cat" id="innertheme_cat" onblur="javascript:checkThemeinnercategory(this);" value="<?php if(isset($_POST['innertheme_cat'])){  echo $_POST['innertheme_cat'];} ?>" />

	

	      	<p class="description" id="chkinner_category"></p>  

        <p class="description">Each Theme Category .  

        </tr></table><?php submit_button('Submit', 'primary', 'submit_thse', '', '' ); ?>

</form>

        <?php } ?><?php

  add_action('admin_menu', 'register_thse_addenvatorssurls_submenu_page');



function register_thse_addenvatorssurls_submenu_page() {

	add_submenu_page( 'sixthlife-search/sixthlifesearch.php', 'Envato RSS URLS', 'Envato RSS URLS', 'manage_options', 'thse_addenvatorssurls_submenu_page','thse_addenvatorssurls_submenu_page' ); 

}
      
function thse_addenvatorssurls_submenu_page() {
	$error = '';
		 if(isset($_POST['rssurls']) && !empty($_POST['rssurls'])){ 
			$postedrssurls = explode('|',$_POST['rssurls']); 
			foreach($postedrssurls as $rssurl){

				$insertrss = getNewRSS(trim($rssurl));
				echo $insertrss;
		if($insertrss === true){
			$error .=	$rssurl. ' added successfully.<br />';
			}
		else if($insertrss === 'exists'){
			$error .= 	$rssurl . ' already exists<br />';
			}
			else if($insertrss === 'empty'){
			$error .=	$rssurl. ' rss content could not be fetched.<br />';	
			}
		
		}
		
		}
		
		 ?>


	<div class="wrap">

<div id="icon-link-manager" class="icon32" ></div>

<h2>Envato RSS URLS</h2>

<h3>Envato RSS URLS</h3>

<?php if($error!=''){echo'<div id="message" class="updated" >' .$error.'</div>';} ?>
<form method="post" action="" >
<table class="form-table">

<tbody>     

   	 <tr valign="top">

        <th  scope="row">

  <label for="">Envato RSS URLs</label> </th><td>

        

		<textarea  name="rssurls"  cols="100" rows="5" id="rssurls"  >
		<?php 
		 if(isset($_POST['rssurls']) && !empty($_POST['rssurls'])){ 
			

				echo $_POST['rssurls'];

		}
		?>
</textarea>

	

 		<p class="description" id="chkrssurls"></p> 	   

        <p class="description">List Envato RSS URLS one on each line.  </p>

               </td></tr> 
               
               </tbody>
               </table>
               <?php submit_button('Add RSS Urls', 'primary', 'submit_addrssurl', '', '' ); ?>
</form>
<?php }    ?>




<?php

  add_action('admin_menu', 'register_thse_addenvatorssproducts_submenu_page');



function register_thse_addenvatorssproducts_submenu_page() {

	add_submenu_page( 'sixthlife-search/sixthlifesearch.php', 'Envato RSS Products', 'Envato RSS Products', 'manage_options', 'thse_addenvatorssproducts_submenu_page','thse_addenvatorssproducts_submenu_page' ); 

}
      
function thse_addenvatorssproducts_submenu_page() {
	$error = '';
	
	if(isset($_POST['submit_addrssproducts']) && isset($_POST['fetchfresh'])){

		$error .= updateRSSUrls($_POST['select_envrssurls']);
	}
	

	if(isset($_POST['submit_addrssproducts'])){

		$error .= productsRSS($_POST['select_envrssurls']);
	}
		
		 ?>


	<div class="wrap">

<div id="icon-link-manager" class="icon32" ></div>

<h2>Envato RSS Products</h2>

<h3>Envato RSS Products</h3>

<?php if($error!=''){echo'<div id="message" class="updated" >' .$error.'</div>';} ?>
<form method="post" action="" >
<table class="form-table">

<tbody>     

         <tr valign="top">

		

		<th scope="row"><label for="">Select URLs</label></th> <td>

        <select name="select_envrssurls[]" id="select_envrssurls" multiple="true" size="6" >

        <?php 
		$query_rssurls = "select id, rss_url from wp_envatorssurls";
		$result_rssurls = mysql_query($query_rssurls) or die(mysql_error());
		
		while($row = mysql_fetch_assoc($result_rssurls)){?> 

        <option value="<?php echo $row['id']; ?>"><?php echo $row['rss_url']; ?></option>

        <?php } ?>

		</select>

        <p class="description">Select RSS Pages from which to fetch Products. </p>

       </td>

         </tr>

         
<tr>
<th scope="row">
<td>
<label for="fetchfresh">
<input id="fetchfresh" type="checkbox" name="fetchfresh" value="1" />
Fetch Fresh Content from respective RSS Urls.
</label>
</td>
</tr>
           </tbody>
               </table>
			             
               <?php submit_button('Add RSS Products', 'primary', 'submit_addrssproducts', '', '' ); ?>
                        
</form>
<?php }    ?>