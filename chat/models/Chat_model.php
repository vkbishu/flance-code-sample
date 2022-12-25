<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model{
    
    private $conversation_table = 'chats';
    private $conversation_user_table = 'chat_member';
    private $conversation_message = 'messages';
    private $message_types = ['attachment','text'];

	public function __construct(){
        return parent::__construct();
	}
    
    
    /**
     *     Return conversation token by project id and message users
     * 
     *      @param $project_id {String} - Required
     *      @param $msg_users {Array} - Required
     * 
     *      @return $conversation_token {String}
     */

    public function get_conversation_token($msg_users, $project_id){
        sort($msg_users);
        $conversation_id = null;
        $conversation_room = $this->db->dbprefix($this->conversation_user_table);
        $converstion = $this->db->select("c.chat_token,r.chat_id,(select group_concat(rr.user_id) from $conversation_room rr where r.chat_id=rr.chat_id order by rr.user_id asc) as room_members")
                        ->from($this->conversation_user_table.' as r')
                        ->join($this->conversation_table.' as c', 'c.chat_id=r.chat_id')
                        ->where('c.project_id', $project_id)
                        ->having('room_members', implode(',',$msg_users))
                        ->group_by('r.chat_id')
                        ->get()
                        ->row();
        if( $converstion){
            $conversation_id = $converstion->chat_token;
        }else{
            $conversation_id = $this->__create_conversation($msg_users, $project_id);
        }
      
        return $conversation_id;
    }

    /**
     *  Generate conversation token
     * 
     */
    
    private function __create_conversation($msg_users, $project_id){
        $token = md5('project-'.$project_id.'-'.implode('|', $msg_users).'-time-'.time());
        $this->db->insert($this->conversation_table, ['chat_token' => $token, 'project_id' => $project_id, 'created_on' => date('Y-m-d H:i:s'), 'total_msg' => 0]);
        $chat_id = $this->db->insert_id();

        foreach($msg_users as $k => $member_id){
            $this->db->insert($this->conversation_user_table, ['user_id' => $member_id, 'last_seen_msg' => 0, 'chat_id' =>  $chat_id]);
        }
      
        return $token;
    }

    /**
     *     Return conversation list with conversation_id, and last_message
     * 
     *      @param $user_id {String} - Required
     * 
     *      @return  {Array} - Conversation List (conversation_id, last_message)
    */

    public function chat_list($user_id,$filter=[]){
        $this->db->select('c.chat_id,c.project_id,c.chat_token,c.last_msg')
            ->from($this->conversation_table . ' c')
            ->join($this->conversation_user_table.' u', 'u.chat_id=c.chat_id', 'INNER')
            ->where('u.user_id', $user_id)
            ->group_by('c.chat_id');

        if(array_key_exists('with_message', $filter)){
            $this->db->having('c.last_msg >', 0);
        }
        $result = $this->db->order_by('c.last_msg', 'DESC')->order_by('c.created_on', 'DESC')->get()->result(); 
        if($result){
            foreach($result as $k => $v){
                $result[$k]->last_message = $this->get_message_by_id($v->last_msg);
                $result[$k]->chat_user = $this->other_chat_user($v->chat_token, $user_id);
                $result[$k]->new_msg = $this->conversation_room_unread_messages(['chat_token' => $v->chat_token, 'user_id' => $user_id], '', '', FALSE);
            }
        }
        return $result;
    }

    /**
     *  Get message by id
     * 
     */

    public function get_message_by_id($message_id){
        $result = [];
        if(is_array($message_id)){
            $result = $this->db->where_in('message_id', $message_id)->get($this->conversation_message)->result();
            if($result){
                foreach($result as $k => $v){
                    $result[$k]->display_time = date('h:i A', strtotime($v->date));
                }
            }
        }else{
            $result = $this->db->where('message_id', $message_id)->get($this->conversation_message)->row();
            if($result){
                $result->display_time = date('h:i A', strtotime($result->date));
            }
        }
        return $result;
    }

    /**
     *  Other Chat User
     * 
     */

    public function other_chat_user($conversation_token, $user_id){
        $member = $this->db->select("c.chat_id,c.chat_token,r.user_id,r.last_seen_msg,CONCAT(u.fname,' ',u.lname) as name,u.fname,u.lname,u.last_seen", FALSE)
                        ->from($this->conversation_user_table .' r')
                        ->join($this->conversation_table . ' as c', 'c.chat_id=r.chat_id', 'INNER')
                        ->join('user u', 'u.user_id=r.user_id', 'INNER')
                        ->where('c.chat_token', $conversation_token)
                        ->where('r.user_id <>', $user_id)
                        ->get()
                        ->row();
        if($member){
            $member->logo = get_user_logo($member->user_id);
            $member->online = is_online_user($member->last_seen);
        }

        return $member;
    }

    /**
     *   Return conversation members
     *  
     *   @param $conversation_id {String}
     * 
     *  @return {Array} - all conversation members
     */

    public function conversation_room_members($conversation_token){
        $members = $this->db->select('m.*')
                    ->from($this->conversation_user_table. ' as m')
                    ->join($this->conversation_table.' as c', 'c.chat_id=m.chat_id', 'INNER')
                    ->where('c.chat_token', $conversation_token)
                    ->get()
                    ->result();

        return $members;
    }

    /**
     *  Conversation message list
     *  @param $srch {Array} - filter options (conversation_token and user_id must)
     *  @param $limit  {Number}
     *  @param $offset  {Number}
     *  @param $for_list {Boolean}    
     * 
     *  @return {Array<Message_Str>}
     * 
    */

    public function conversation_room_messages($srch=[], $limit=0, $offset=30, $for_list=TRUE){
        $conversation_token = !empty($srch['chat_token']) ? $srch['chat_token'] : null;
        $user_id = !empty($srch['user_id']) ? $srch['user_id'] : null;
        if(!$conversation_token || !$user_id){
            throw new Exception('Invalid paramter in FILE: '.__FILE__.' Line no:'.__LINE__);
        }
        $this->db->select('m.message_id')
                ->from($this->conversation_message . ' m')
                ->join($this->conversation_user_table . ' u', 'u.chat_id=m.chat_id', 'INNER')
                ->join($this->conversation_table . ' c', 'c.chat_id=m.chat_id', 'INNER')
                ->where('c.chat_token', $conversation_token)
                ->where('u.user_id', $user_id);

        if(array_key_exists('unread', $srch)){
            $this->db->where('m.message_id > u.last_seen_msg');
        }    
        if($for_list){
            $result = [];
            $all_result = $this->db->limit($offset, $limit)->order_by('m.message_id', 'desc')->get()->result_array();
            $message_ids = get_k_value_from_array($all_result, 'message_id');
            if($message_ids){
                $result = $this->get_message_by_id($message_ids);
            }
            /*   if($all_result){
                $all_result =  array_reverse($all_result);
                $result = $all_result;
              foreach($all_result as $k => $message){
                    $result[$k] = new Message_Str($message);
                } 
            }*/
        }else{
            $result = $this->db->get()->num_rows();
        }

        return $result;


    }

    /**
     * 
     *  Conversation room attachments 
     * 
     *  @param $srch {Array} - filter options (conversation_id must)
     *  @param $limit  {Number}
     *  @param $offset  {Number}
     *  @param $for_list {Boolean}    
     * 
    */

    public function conversation_room_attachments($srch=[], $limit=0, $offset=30, $for_list=TRUE){
        $conversation_token = !empty($srch['chat_token']) ? $srch['chat_token'] : null;
        $this->db->select('m.message_id')
                ->from($this->conversation_message . ' m')
                ->join($this->conversation_table . ' c', 'c.chat_id=m.chat_id', 'INNER')
                ->where('c.chat_token', $conversation_token)
                ->where('m.type', 'attachment');

        if($for_list){
            $result = [];
            $all_result = $this->db->limit($offset, $limit)->order_by('m.message_id', 'desc')->get()->result_array();
            $message_ids = get_k_value_from_array($all_result, 'message_id');
            $result = $this->get_message_by_id($message_ids);
            $result =  array_reverse($all_result);
            
             /* if($all_result){
               foreach($all_result as $k => $message){
                    $result[$k] = new Message_Str($message);
                }
            } */
        }else{
            $result = $this->db->get()->num_rows();
        }

        return $result;
    }

    /**
     * 
     *  Conversation room unread messages 
     * 
     *  @param $conversation_id {Number}
     *  @return  {Array} All Unread Message    
     * 
    */

    public function conversation_room_unread_messages($srch=[], $limit=0, $offset=100, $for_list=TRUE){
        $conversation_id = !empty($srch['chat_token']) ? $srch['chat_token'] : null;
        $member_id = !empty($srch['user_id']) ? $srch['user_id'] : null;
        if(!$conversation_id || !$member_id){
            throw new Exception('Invalid paramter in FILE: '.__FILE__.' Line no:'.__LINE__);
        }

        $srch['unread'] = true;
        return $this->conversation_room_messages($srch, $limit, $offset, $for_list);
    }


    public function send_message(){
        $chat_token = post('chat_token');
        if(!$chat_token){
            throw new Exception('Invalid chat');
        }
        
        // insert message
        $chat_id = getField('chat_id', $this->conversation_table, 'chat_token', $chat_token);
        /* $sender_id = post('sender_id'); */
		$sender_id = login_user_id();
        $dbdata = [
            'chat_id' => $chat_id,
            'sender_id' => $sender_id,
            'message' => filter_data(post('message')),
            'type' => 'text',
            'date' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert($this->conversation_message, $dbdata);
        $msg_id = $this->db->insert_id();

        // update conversation last message and total message
        $this->db
                ->set('total_msg', 'total_msg + 1', FALSE)
                ->set('last_msg', $msg_id)
                ->where('chat_id', $chat_id)
                ->update($this->conversation_table);

        // update member conversation last message
        $this->db
            ->set('last_seen_msg', $msg_id)
            ->where('chat_id', $chat_id)
            ->where('user_id', $sender_id)
            ->update($this->conversation_user_table);

        $members = $this->conversation_room_members($chat_token);
        if($members){
            foreach($members as $member){
                if($member->user_id != $sender_id){
                    $this->update_user_file($member->user_id);
                } 
            }
        }

        $msg = $this->get_message_by_id($msg_id);
        return $msg;
    }

     /**
     *  Mark message as read
     * 
     *  @param {String} conversation id
     *  @param {Number} user id
     * 
     *  @return void
     */

    public function mark_as_read($chat_token, $user_id){
        $last_message = getField('last_msg', 'chats', 'chat_token', $chat_token);
        $chat_id = getField('chat_id', 'chats', 'chat_token', $chat_token);
        $this->db->where(['chat_id' => $chat_id, 'user_id' => $user_id])->update($this->conversation_user_table, ['last_seen_msg' => $last_message]);
    }

    public function update_user_file($user_id){
        $data = null;
        $file = 'user_message/user_'.$user_id.'.update';
        $data_arr['message'] = 0;

        if(file_exists( $file)){
            $data = file_get_contents($file);
            if(strlen($file) > 0){
                $data_arr = json_decode($data, true);
                $data_arr['message'] += 1;
            }
        }else{
            $data_arr['message']= 1;
        }
       
        if( $data_arr){
            file_put_contents($file, json_encode($data_arr));
        }
       
    }

    public function reset_msg_file($user_id){
        $file = 'user_message/user_'.$user_id.'.update';
        $data_arr['message'] = 0;
        file_put_contents($file, json_encode($data_arr));

    }

    public function get_unread_msg($user_id){
        $data = null;
        $file = 'user_message/user_'.$user_id.'.update';
        if(file_exists( $file)){
            $data = file_get_contents($file);
        }
        $result = 0;
        if($data && strlen($data) > 0){
            $data = json_decode($data);
            if($data->message > 0){
               $result =  $data->message;
            }
        }
        return $result;
    }

	
}


