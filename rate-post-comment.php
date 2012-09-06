<?php
/**
 * @package Rate Post Comment
 * @version 1.0
 * Plugin Name: Rate Post Comment
 * Plugin URI: http://wordpress.org/extend/plugins/rate-post-comment/
 * Description: Rate posts and comments.
 * Author:Ola Apata
 * Author URI: http://fb-520.com
 * Version: 1.0
 * License: GPL2 Licence
 * License URI: license.txt
*/

define( 'SB_ABSPATH', dirname( __FILE__ ) );
global $wpdb,$user_identity, $user_ID, $update_message, $max_comment;
$plugin_prefix = "rr";
$signup_builder_table = $wpdb->prefix . "rate-post-comment";
$feb_signup = dirname(__FILE__);
define('RR_PLUGIN_URL', plugin_dir_url( __FILE__ ));
$plugin_name = "Rate Post Comment";


register_activation_hook(__FILE__, "install_rate_and_report");
add_action("init","rr_wp_enqueue_style");
add_action('wp_enqueue_scripts', 'rr_wp_enqueue_scripts');
if($_REQUEST["rr_action"] == "update"){add_action("init", "rr_rate_options");}
if($_REQUEST["rr_action"] == "rr_reset_comments"){add_action("init", "rr_reset_comments");}
if($_REQUEST["rr_action"] == "rr_reset_posts"){add_action("init", "rr_reset_posts");}
add_action("admin_menu", "rate_and_report_menu");
add_action("wp_head","rr_wp_head_action");
add_filter("comment_reply_link","rr_comment_reply_link");
add_filter( "the_content", "rr_the_content" );



function rate_and_report_menu() {
//add_menu_page('Rate and Report', 'Rate and Report', 'add_users', 'rate-and-report/rate-and-report.php', 'rate_and_report',   plugins_url('rate-and-report/images/star.png'));


add_menu_page('Rate Post Comment', 
              'Rate Post Comment',
              8, 
              'rate-post-comment/rate-post-comment.php', 
              'rate_and_report', 
              plugins_url('rate-post-comment/images/star.png'));
	
	  }

function rr_wp_enqueue_style(){
     wp_enqueue_style( 'comment-watch', plugins_url( 'rate-post-comment/style/rate-post-comment.css' ) );	
}

function rr_wp_enqueue_scripts() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
 

function rr_update_alert($message, $type){
	
	return 
	
	"<div class='msg_updated'>".$message."</div>";
	
}

function rr_the_content($content){	
  if (is_single()) {  
	global $user_ID, $id;	
	$post_ID = $id;
	$meta_key = "rr_post_rate_". $post_ID;
	$get_post_count =  get_post_meta($post_ID, $meta_key, true);
	if($get_post_count == "" or $get_post_count == NULL){
	$love = "0";
	$hate = "0";
	
	}else{
		
	$get_post_values = explode(',', $get_post_count);
	
	$love = $get_post_values[0];
	$hate = $get_post_values[1];
	if($hate == NULL){$hate = "0";}
	if($love == NULL){$love = "0";}
	
		
	}
	if(get_option('rr_posts') == 'up' or get_option('rr_posts') == 'updown'){ 
	
	$link .= " &nbsp;
	          <img onClick=rr_wp_head_post('love','".$user_ID ."','". $post_ID."')   
			  src=".plugins_url('rate-post-comment/images/love.png')." style=cursor:pointer />
			  &nbsp;<span id=love_post_".$post_ID." class='rr_thumb_up'>".$love."</span>&nbsp;";
   } 
	if(get_option('rr_posts') == 'updown'){ 
	$link .= "&nbsp;
	          <img onClick=rr_wp_head_post('hate','".$user_ID ."','". $post_ID."') 
			  src=".plugins_url('rate-post-comment/images/hate.png')."  style=cursor:pointer />
			  &nbsp;<span id=hate_post_".$post_ID." class='rr_thumb_down'>".$hate."</span>";
	}
   }
   
     return $content . $link;
 }
 

function rr_comment_reply_link($link){
	global $user_ID;
	$comment_ID = get_comment_ID();
	$meta_key = "rr_comment_rate_".$comment_ID;
	$get_comments_count =  get_comment_meta($comment_ID, $meta_key, true);
	if($get_comments_count == "" or $get_comments_count == NULL){
	$love = "0";
	$hate = "0";
	
	}else{
		
	$get_comment_values = explode(',', $get_comments_count);
	
	$love = $get_comment_values[0];
	$hate = $get_comment_values[1];
	if($hate == NULL){$hate = "0";}
	if($love == NULL){$love = "0";}	
		
	}
	
	
	if(get_option('rr_comments') == 'up' or get_option('rr_comments') == 'updown'){ 
	
	$link .= " &nbsp;
	          <img onClick=rr_wp_head_comment('love','".$user_ID ."','". $comment_ID."')   
			  src=".plugins_url('rate-post-comment/images/love.png')." style=cursor:pointer />
			  &nbsp;<span id=love_comment_".$comment_ID." class='rr_thumb_up'>".$love."</span>&nbsp;";
	}
	if(get_option('rr_comments') == 'updown'){ 
	$link .= "&nbsp;
	          <img onClick=rr_wp_head_comment('hate','".$user_ID ."','". $comment_ID."') 
			  src=".plugins_url('rate-post-comment/images/hate.png')."  style=cursor:pointer />
			  &nbsp;<span id=hate_comment_".$comment_ID." class='rr_thumb_down'>".$hate."</span>";
	}
	
	return $link;
}

function rr_comment_text($content){
	$comment_ID = get_comment_ID();
	$new_link ="<div style='text-align:left;font-size:11px;margin-top:5px'><a href=''>Report Comment</a></div>";
	return $content . $new_link .  get_comment_ID();
}


function rr_rate_options(){
	global $update_message;
	$rr_comments = $_REQUEST['rr_comments'];
	$rr_posts = $_REQUEST['rr_posts'];
	 
    $option_name = 'rr_comments';
    $newvalue =  $rr_comments;
	
    $option_name_1 = 'rr_posts';
    $newvalue_1 =  $rr_posts;

     if (get_option($option_name) != $newvalue) {
        update_option($option_name, $newvalue);
      }else {
       $deprecated = ' ';
       $autoload = 'yes';
       add_option($option_name, $newvalue, $deprecated, $autoload );
     }
	 
	 if (get_option($option_name_1) != $newvalue_1) {
        update_option($option_name_1, $newvalue_1);
      }else {
       $deprecated = ' ';
       $autoload = 'yes';
	   add_option( $option_name_1, $newvalue_1, $deprecated, $autoload );
     }	 	 
	 $update_message = 'Settings updated successfully';
}

function rr_wp_head_action($type, $user_ID, $comment_ID){
	
	?><script>
	function rr_wp_head_comment(type, user_ID, comment_ID){    
		comment_rate(type, user_ID, comment_ID);
	}
	
	function rr_wp_head_post(type, user_ID, post_ID){	    
		post_rate(type, user_ID, post_ID);
	}
	
   function rr_wp_head_report_display(user_ID, comment_ID){
		$("#report_" + comment_ID + "_container").css({display:'block'});
		$("#comment_report_" + comment_ID).css({display:'none'});
	}
	
	
	function rr_wp_head_report(user_ID, comment_ID){
		post_report(user_ID, comment_ID);
	}
	
	
function comment_rate(type, user_ID, comment_ID) {
  var randnum = Math.floor(Math.random()*900000000) + 10000;
  //alert(type + ' ' + user_ID + ' ' + comment_ID);
  jQuery.ajax({
         type: "GET",
         url: "<?php echo plugins_url('rate-post-comment/rate-post-comment-ajax.php')?>?user_ID=" + user_ID + "&type=" + type + "&comment_ID=" + comment_ID + "&rand=" + randnum + "&watch=rate_comment",
         success: function(msg){    
         if(msg == '') {
         alert('Error! rating not recorded');
         }
         else {          
		 $('#'+type+'_comment_'+ comment_ID).html(msg);            
         }
          return false;     
         }
      });
      return true;
	  
}


function post_rate(type, user_ID, post_ID) {
  var randnum = Math.floor(Math.random()*900000000) + 10000;
  jQuery.ajax({
         type: "GET",
         url: "<?php echo plugins_url('rate-post-comment/rate-post-comment-ajax.php')?>?user_ID=" + user_ID + "&type=" + type + "&post_ID=" + post_ID + "&rand=" + randnum + "&watch=rate_post",
         success: function(msg){    
         if(msg == '') {
         alert('Error! rating not recorded');
         }
         else {          
		 $('#'+type+'_post_'+ post_ID).html(msg);            
         }
          return false;     
         }
      });
      return true;
	  
}
	
	
	</script>
    <?
}
function rr_remove_admin_bar(){
   /* Disable WordPress Admin Bar for all users but admins. */
//if (!current_user_can('administrator')):
  show_admin_bar(false);
//endif;
}

function install_rate_and_report()
	{
		//SET OPTIONS
		$option_name_1 =  'rr_posts';
		$newvalue_1 ='updown';
		$option_name =  'rr_comments';
		$newvalue ='updown';
		
		if(!get_option($option_name_1)){
		add_option( $option_name_1, $newvalue_1, $deprecated, $autoload );
		}
		if(!get_option($option_name)){
		add_option( $option_name, $newvalue, $deprecated, $autoload );
		}
	}
	
function rr_reset_comments(){	
	global $update_message, $wpdb;
	$query = "DELETE  FROM ".$wpdb->prefix."commentmeta  WHERE meta_key LIKE 'rr_comment_rate%';";
	 if($wpdb->query($query)){
			   $update_message = 'Comments rating reset successfully';
	       }
	
}

function rr_reset_posts(){
	global $update_message, $wpdb;
	$query = "DELETE  FROM ".$wpdb->prefix."postmeta  WHERE meta_key LIKE 'rr_post_rate%';";
	 if($wpdb->query($query)){
			   $update_message = 'Posts rating reset successfully';
	       }
	
}

function rate_and_report(){
	global $wpdb, $signup_builder_table, $user_ID,$update_message;	
?>  
<div class='wrap' id="main_body">

<?php if($update_message <> ""){?>
<div class="updated">&nbsp;&nbsp&nbsp<?php _e($update_message);?> </div>
<?php } ?>

<div id="icon-options-general" class="icon32"><br /></div>

	<h2><?php echo 'Rate Post Comment' ?></h2>



<h3>Setting</h3>
<div class="tablenav"  style="width:95%">
<form method=post action="" name="form_1" id="form_1" style="padding:0;margin:0">
<input type=hidden name=rr_action id=rr_action value=update />
	<table class="widefat post fixed" cellspacing="0">
            	<thead>
                    <tr rowspan=2>
                       <th width=""><label>Post Rate setting</label></th>
                       <th width=""><label>Comment Rate setting</label></th>                     
                    </tr>
                </thead>
                    <tr rowspan=2>
         <td>
<select name="rr_posts"  id="rr_posts">
<option value="up" <?php if(get_option('rr_posts') == 'up'){  echo 'selected=selected';} ?>>Show thumbs up only</option>
<option value="updown" <?php if(get_option('rr_posts') == 'updown'){ echo  'selected=selected';} ?>>Show thumbs up and thumbs down</option>
<option value="" <?php if(get_option('rr_posts') == ''){ echo 'selected=selected';} ?>>Hide thumbs up and thumbs down</option>
</select>
&nbsp;&nbsp;&nbsp;
<?php if(get_option('rr_posts') == 'up' or get_option('rr_posts') == 'updown'){  ?>
<img src="<?php echo plugins_url('rate-post-comment/images/love.png') ?>" /> 
&nbsp;<span class='rr_thumb_up'><?php echo rand(55,900) ?></span>&nbsp;&nbsp;
<?php }  if(get_option('rr_posts') == 'updown'){  ?>
<img src="<?php echo plugins_url('rate-and-report/images/hate.png') ?>" />
&nbsp;<span class='rr_thumb_down'><?php echo rand(10,90) ?></span>
<?php }  ?>
 </td>
 <td>
<select name="rr_comments"  id="rr_comments">
<option value="up" <?php if(get_option('rr_comments') == 'up'){ echo  'selected=selected';} ?>>Show thumbs up only</option>
<option value="updown" <?php if(get_option('rr_comments') == 'updown'){  echo 'selected=selected';} ?>>Show thumbs up and thumbs down</option>
<option value="" <?php if(get_option('rr_comments') == ''){ echo  'selected=selected';} ?>>Hide thumbs up and thumbs down</option>
</select>
&nbsp;&nbsp;&nbsp;
<?php if(get_option('rr_comments') == 'up' or get_option('rr_comments') == 'updown'){  ?>
<img src="<?php echo plugins_url('rate-post-comment/images/love.png') ?>" /> 
&nbsp;<span class='rr_thumb_up'><?php echo rand(55,900) ?></span>&nbsp;&nbsp;
<?php }  if(get_option('rr_comments') == 'updown'){  ?>
<img src="<?php echo plugins_url('rate-post-comment/images/hate.png') ?>" />
&nbsp;<span class='rr_thumb_down'><?php echo rand(10,90) ?></span>
<?php }  ?>
</td>

</tr>
<tr>


<td colspan=2>
<div style="float:right;margin-top:10px;margin-bottom:10px">
<input id="add" name="add" type="submit" value="Save Changes" class='button-primary' />
</div>
</td>
                   
             </tr>
	
        	</table>

</form>
<div style="float:left;margin-top:20px">
<form method=post action="" name="form_2" id="form_2" style="padding:0;margin:0;float:left">
<input type=hidden name=rr_action id=rr_action value="rr_reset_posts" /><input id="reset_post" name="reset_post" type="submit" value="Reset all post ratings" class='button-secondary' /></form>


<form method=post action="" name="form_3" id="form_3" style="padding:0;margin:0;float:left">
<input type=hidden name=rr_action id=rr_action value="rr_reset_comments" /><input id="reset_comment" name="reset_comment" type="submit" value="Reset all comment ratings" class='button-secondary' /></form>
</div>


</div>

</div>

  
    <?php
	

}