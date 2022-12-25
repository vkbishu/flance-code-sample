<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
        $this->load->model('chat_model');
        parent::__construct();
        check_user_log();
	}

	public function index(){
		$profile_id = login_user_id();
		$this->data['profile'] = get_row([
		'select' => 'user_id,fname as name,gender',
		'from' => 'user',
		'where' => ['user_id' => $profile_id]
		]);
		$this->data['profile']['user_logo'] = get_user_logo($profile_id);
        $lay = '';
		$this->layout->extend('chat-extended',$this->data);
    }

    public function browse($chat_token){
        $this->data['chat_token'] = $chat_token;
        $this->index();
    }

    public function quick_chat(){
        $user_id = get('user_id');
        $project_id = get('project_id');
        $this->data['chat_token'] = $this->chat_model->get_conversation_token([$user_id, login_user_id()], $project_id);

        $profile_id = login_user_id();
		$this->data['profile'] = get_row([
            'select' => 'user_id,name,gender',
            'from' => 'users',
            'where' => ['user_id' => $profile_id]
		]);
		$this->data['profile']['user_logo'] = get_user_logo($profile_id);
        $this->load->view('quick-chat', $this->data);
    }

    /* public function test(){
        $project_id = '1570882951';
        $chat_token = $this->chat_model->get_conversation_token([8, login_user_id()], $project_id);
        $chat_token = $this->chat_model->get_conversation_token([7, login_user_id()], $project_id);
        $chat_token = $this->chat_model->get_conversation_token([16, login_user_id()], $project_id);
    } */


    public function get_header_message(){
        $profile_id = login_user_id();
        $chat_list = $this->chat_model->chat_list($profile_id, ['with_message' => true]);
        $this->data['chat_list'] = $chat_list;
        //get_print($this->data['chat_list']);
        $this->load->view('chat-list-ajax', $this->data);
    }

    public function get_chat(){
		$profile_id = login_user_id();
        $chat_list = $this->chat_model->chat_list($profile_id);
      
        $result = [];
        foreach($chat_list as $k => $v){
            $result[$k] = $v->chat_user;
            $result[$k]->user_logo = $v->chat_user->logo;
            $result[$k]->message =  !empty($v->last_message) ? $v->last_message->message : '<i>Chat start with '.$v->chat_user->name.'</i>';
            $result[$k]->project_name =  getField('title', 'projects', 'project_id', $v->project_id);
            $result[$k]->project_id =  $v->project_id;
            $result[$k]->sender_id =  !empty($v->last_message) ? $v->last_message->sender_id : 0;
            $result[$k]->new_msg =  $v->new_msg;
        }
        $this->api_format->set_data('list', $result);
        $this->api_format->out();
    }

    public function chat_messages(){
        $srch = get();
        $srch['user_id'] = login_user_id();
        $srch['chat_token'] = get('chat_token');
        $page = (int) (get('page') > 0) ? get('page') : 0;
		$per_page = 40;
        $limit = $page*$per_page;
        /* $limit = 0; */
		$next_page = ($page+1);

        $list = $this->chat_model->conversation_room_messages($srch, $limit, $per_page);
        $total_record = $this->chat_model->conversation_room_messages($srch, $limit, $per_page, FALSE);
        $total_page = ceil($total_record/$per_page);
        
        $this->api_format->set_data('list', $list);
        $this->api_format->set_data('list_total', $total_record);
        $this->api_format->set_data('current_page', $page);
        $this->api_format->set_data('next_page', $next_page);

        $this->api_format->out();
    }

    public function send_message(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'message', 'required|trim');
        $this->form_validation->set_rules('chat_token', 'chat_token', 'required');
        //$this->form_validation->set_rules('sender_id', 'sender_id', 'required');

        if($this->form_validation->run() === false){
            $this->api_format->set_error(validation_errors_array());
            $this->api_format->out();
        }

        $message = $this->chat_model->send_message();

        $this->api_format->set_data('last_message', $message);
        $this->api_format->out();
    }

     /**
     *  mark conversation as read
     * 
     */
    public function notify(){
        $chat_token = post('chat_token');
        $chat_as =login_user_id();
        $this->chat_model->mark_as_read($chat_token,  $chat_as);
        $this->chat_model->reset_msg_file($chat_as);
        echo 1;
        // notify other user in some way
    }


    /**
     *  Unread message
     * 
     */

    public function unread_message(){
        $srch = [];
        $srch['user_id'] = login_user_id();
        $srch['chat_token'] = get('chat_token');
        $list = $this->chat_model->conversation_room_unread_messages($srch);
        $this->api_format->set_data('unread', $list);
        $this->api_format->out();
    }

    public function check_new(){
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $new_msg_count = $this->chat_model->get_unread_msg(login_user_id());
        echo "data:  $new_msg_count".PHP_EOL;
        echo PHP_EOL;
        ob_flush();
        flush();
    }

	
}


