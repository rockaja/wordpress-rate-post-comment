
<?php
//echo getcwd();
define('WP_USE_THEMES', false);

require_once(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-blog-header.php');
global $wpdb, $user_ID;


if (is_user_logged_in()) {
	
	if($_REQUEST['watch'] == "rate_comment"){
	
	$comment_ID = $_REQUEST['comment_ID'];
	$user_ID = $_REQUEST['user_ID'];
	$type = $_REQUEST['type'];
//if (!$user_ID) {

		if($comment_ID != "" and $user_ID != "" and $type != ""){
			$meta_key = "rr_comment_rate_" . $comment_ID;			
			$prev_value =  get_comment_meta($comment_ID, $meta_key, true);
			
			$get_comment_values = explode(',', $prev_value);
			if($get_comment_values[0] == ""){$get_comment_values[0] = 0;}
			if($get_comment_values[1] == ""){$get_comment_values[1] = 0;}
			
			//check if userid exist in array
			//remove first 2 this is for love and hate
			$temp_get_comment_values = $get_comment_values;
			unset($temp_get_comment_values[0]);
			unset($temp_get_comment_values[1]);
				
	          if($type == "love"){
				  
				if (in_array($user_ID . "_" . $type, $temp_get_comment_values) or
				    in_array($user_ID . "_hate", $temp_get_comment_values)) 
					{ 				
				       echo 'Already Rated';
				    
					 }else{
			            $love = $get_comment_values[0];
			            $love++;
			            $get_comment_values[0] = $love;
						//if($get_comment_values[1]){
						//$get_comment_values[1] = $love;
						//}
				        //unset($get_comment_values[$user_ID . _hate]);
			            $get_comment_values[] = $user_ID. "_" . $type;
					    $meta_value = implode( ",", $get_comment_values);
			            
						
						if(update_comment_meta( $comment_ID, $meta_key, $meta_value, $prev_value ))
						{
							//echo $meta_value;
							echo $love;
						}
			        }
			  }
			
			  if($type == "hate"){
	           
			     if (in_array($user_ID . "_" . $type, $temp_get_comment_values) or
				    in_array($user_ID . "_love", $temp_get_comment_values)) 
					{ 				
				       echo 'Already Rated';
				    
					 }else{
			           $hate = $get_comment_values[1];
			           $hate++;
			           $get_comment_values[1] = $hate;
			           //unset($get_comment_values[$user_ID. "_love"]);
			           $get_comment_values[] = $user_ID. "_" . $type;
					   $meta_value = implode( ",", $get_comment_values);		
			 
			           if(update_comment_meta( $comment_ID, $meta_key, $meta_value, $prev_value ))
						{
							//echo $meta_value;
							echo $hate;
						}
			         }
			    }
			
              unset($get_comment_value);
		
		
		}else{
			
			echo 'Error!';
			
		}
			
}





if($_REQUEST['watch'] == "rate_post"){
	
	$post_ID = $_REQUEST['post_ID'];
	$user_ID = $_REQUEST['user_ID'];
	$type = $_REQUEST['type'];
//if (!$user_ID) {

		if($post_ID != "" and $user_ID != "" and $type != ""){
			$meta_key = "rr_post_rate_" . $post_ID;			
			$prev_value =  get_post_meta($post_ID, $meta_key, true);
			
			$get_post_values = explode(',', $prev_value);
			if($get_post_values[0] == ""){$get_post_values[0] = 0;}
			if($get_post_values[1] == ""){$get_post_values[1] = 0;}
			
			//check if userid exist in array
			//remove first 2 this is for love and hate
			$temp_get_post_values = $get_post_values;
			unset($temp_get_post_values[0]);
			unset($temp_get_post_values[1]);
				
	          if($type == "love"){
				  
				if (in_array($user_ID . "_" . $type, $temp_get_post_values) or
				    in_array($user_ID . "_hate", $temp_get_post_values)) 
					{ 				
				       echo 'Already Rated';
				    
					 }else{
			            $love = $get_post_values[0];
			            $love++;
			            $get_post_values[0] = $love;
						//if($get_comment_values[1]){
						//$get_comment_values[1] = $love;
						//}
				        //unset($get_comment_values[$user_ID . _hate]);
			            $get_post_values[] = $user_ID. "_" . $type;
					    $meta_value = implode( ",", $get_post_values);
			            
						
						if(update_post_meta( $post_ID, $meta_key, $meta_value, $prev_value ))
						{
							//echo $meta_value;
							echo $love;
						}
			        }
			  }
			
			  if($type == "hate"){
	           
			     if (in_array($user_ID . "_" . $type, $temp_get_post_values) or
				    in_array($user_ID . "_love", $temp_get_post_values)) 
					{ 				
				       echo 'Already Rated';
				    
					 }else{
			           $hate = $get_post_values[1];
			           $hate++;
			           $get_post_values[1] = $hate;
			           //unset($get_comment_values[$user_ID. "_love"]);
			           $get_post_values[] = $user_ID. "_" . $type;
					   $meta_value = implode( ",", $get_post_values);		
			 
			           if(update_post_meta( $post_ID, $meta_key, $meta_value, $prev_value ))
						{
							//echo $meta_value;
							echo $hate;
						}
			         }
			    }
			
              unset($get_post_value);
		
		
		}else{
			
			echo 'Error!';
			
		}
			
}






	if($_REQUEST['watch'] == "report"){
	
	$comment_ID = $_REQUEST['comment_ID'];
	$user_ID = $_REQUEST['user_ID'];
	$report_text = str_replace("|", " ", $_REQUEST['report_text']);
	
	$meta_key = "rr_comment_report_" . $comment_ID;			
	$prev_value =  get_comment_meta($comment_ID, $meta_key, true);
	$get_comment_values = explode('|', $prev_value);
	
	$meta_key_1 = "rr_comment_report_text_" . $comment_ID;			
	$prev_value_1 =  get_comment_meta($comment_ID, $meta_key_1, true);
	$get_comment_values_1 = explode('|', $prev_value_1);
	
	//$get_comment_value[] = $user_ID .  "_report";
	
	
	 if (in_array($user_ID . "_report", $get_comment_values) ) 
					{ 				
				       echo 'Already reported';
				    
					 }else{

			           $get_comment_values[] = $user_ID. "_report" ;
					   $meta_value = implode( "|", $get_comment_values);
					   
					   $get_comment_values_1[] = $user_ID. "_" . $report_text;
					   $meta_value_1 = implode( "|", $get_comment_values_1);		
			           
			           //if(
					     update_comment_meta( $comment_ID, $meta_key, $meta_value, $prev_value ); 
					     update_comment_meta( $comment_ID, $meta_key_1, $meta_value_1, $prev_value_1);
						 //{
						/*
						$sql = "update wp_comments set 
						             comment_karma = comment_karma +1, 
									 comment_approved = '0' 
						where comment_id=$comment_ID ";
                           
						   $wpdb->query($wpdb->prepare($sql));*/

                            echo "Report Received";
						//}
			         }	
	
	}
	
	
	
	
	



}else {

echo "Not loggedin" . $comment_id;
		
}

?>