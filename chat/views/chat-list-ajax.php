<?php if(count($chat_list) > 0){foreach($chat_list as $k => $v){ 
$profile_pic = $v->chat_user->logo;    
if($v->chat_user->online){
	$online_status_cls = 'status-online';
}else{
	$online_status_cls = 'status-offline';
}
$last_message = '';
if($v->new_msg > 0){
    $last_message .= '<b>';
}
$last_message .= $v->last_message->message;
if($v->new_msg > 0){
    $last_message .= '</b>';
}
?>
<li class="notifications-not-read">
 <a href="<?php echo base_url('chat/browse/'.$v->chat_token);?>"> <span class="notification-avatar <?php echo $online_status_cls; ?>"><img src="<?php echo $profile_pic; ?>" alt=""></span>
  <div class="notification-text"> <strong><?php echo $v->chat_user->name; ?></strong>
	<p class="notification-msg-text"><?php echo $last_message; ?></p>
	<span class="color"><?php echo $v->last_message->display_time;?></span> 
	</div>
  </a> 
</li>
<?php } } ?>