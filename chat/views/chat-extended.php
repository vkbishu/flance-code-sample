<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vue-infinite-loading@^2/dist/vue-infinite-loading.js"></script>
 
<!-- working area -->
<div class="card" id="chat-app">
<!-- working area -->
<div class="messages-container margin-top-0">
<div class="messages-container-inner" :style="{'height' : chatHeight+'px'}">
    <div class="messages-inbox">
    <div class="messages-headline">
        <!--<h4>Chats</h4>-->
        <div class="input-with-icon">
        <input type="text" class="form-control" placeholder="Search" @input="filterChat">
        <i class="icon-material-outline-search"></i> </div>
        </div>
    <ul v-if="chatList.length > 0">
        <li v-for="(chat, index) in chatList" :key="index"> 
            <a href="#" v-on:click.prevent="setChat(index)" :class="{'active': active_chat.chat_token == chat.chat_token}">
                <div class="message-avatar">
                    <i class="status-icon" :class="{'status-online' : chat.online, 'status-offline' : !chat.online}"></i>
                    <img :src="chat.user_logo" :alt="chat.name">
                </div>
                <div class="message-by">
                <div class="message-by-headline">
                    <h5>{{chat.name}} <span class="float-right badge badge-danger" v-if="chat.new_msg > 0">{{chat.new_msg}}</span></h5>
                    <p>{{chat.project_name}}</p>
                    <p v-if="chat.new_msg > 0"><b> {{chat.fname}}: <abbr v-html="chat.message"></abbr></b></p>
                    <p v-else> {{ chat | sender_name }} <abbr v-html="chat.message"></abbr></p>                            
                </div>
                </div>
            </a> 
        </li>
    </ul>
    <div v-else>
        <h4>No results</h4>
    </div>
    </div>


<!-- no chat selected template
<div class="col-sm-6 p-0 d-flex" v-if="active_chat.chat_token === null">   
    <h3 class="m-auto">No Chat Selected</h3>
</div>
    -->
<div class="message-content" v-if="active_chat.chat_token === null" style="min-height:530px;">
        <h3 class="m-auto">No Chat Selected</h3>
</div>
<!-- active chat tempate -->

<div class="message-content" v-else>
    <div class="messages-headline">
        <!--<h4>Chat as</h4>-->
        <div class="message-card-header">              
        <div class="user-details">
        <div class="user-avatar" :class="{'status-online' : active_chat.chat_with.online}">
            <img :src="active_chat.chat_with.user_logo" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="user-name">                    
            <b>{{active_chat.chat_with.name}}</b>
            <p>{{active_chat.project_name}}</p>
        </div>
        </div>
        </div>              
    </div>
    <!-- /.card-header -->
    <div class="message-content-inner" ref="chat_message_container">
        <!-- Conversations are loaded here -->
        <div class="direct-chat-messages"> 

        <infinite-loading @infinite="loadMessage" ref="messageInfiniteLoading" :identifier="active_chat.messageInfiniteId" direction="top">
        <div slot="no-more"></div>
        <div slot="no-results"></div>                 
        </infinite-loading>

        <!-- Message. Default to the left -->
        <div class="message-bubble" v-for="(message,index) in active_chat.messages" :key="index" :class="{'me': message.sender_id==active_chat.chat_as.user_id}">
            <div class="message-bubble-inner">
            <div class="direct-chat-infos clearfix"> 
            <span class="direct-chat-name float-left" hidden>Alexander Pierce</span> 
            <div class="direct-chat-timestamp message-time-sign"><span>{{message.display_time}}</span></div> 
            </div>
            <!-- /.direct-chat-infos --> 
            <img class="direct-chat-img" src="<?php echo MOD_DIST;?>img/user1-128x128.jpg" alt="Message User Image" hidden> 
            <!-- /.direct-9chat-img -->
            <div class="message-text"> {{message.message}} </div>
            <!-- /.direct-chat-text --> 
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /.direct-chat-msg --> 
    </div>
    <!-- /.card-body -->                       
    </div>
    <div class="message-reply">
        <div class="input-group">
            <input type="text" placeholder="Type Message ..." class="form-control" v-model="active_chat.message" :disabled="active_chat.sending" v-on:keyup.enter="sendMessage" ref="message_input">
            <span class="input-group-append">
                <button type="submit" class="btn btn-site" v-on:click.prevent="sendMessage" :disabled="active_chat.sending">
                    <span v-if="active_chat.sending">Sending...</span>
                    <span v-else><i class="icon-feather-send"></i></span>
                </button>
            </span> 
        </div>
    </div>
    <!-- /.card-footer-->
</div>

</div>
</div>
</div>
   


<script>
(function(){

'use strict';

var active_chat_token  = '<?php echo !empty($chat_token) ? $chat_token : 'null'?>';
var profiles = [
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user5-128x128.jpg',
        name: 'David Peterson',
        message: 'Yesterday',
    },
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user7-128x128.jpg',
        name: 'Sindy Forest',
        message: '4 hours ago',
    },
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user3-128x128.jpg',
        name: 'Sebastiano Piccio',
        message: '2 days ago',
    },
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user4-128x128.jpg',
        name: 'Marcin Kowalski',
        message: '2 days ago',
    }
];

var chats = [
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user1-128x128.jpg',
        name: 'David Peterson',
        message: 'Earning $10',
        online: true,
    },
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user2-160x160.jpg',
        name: 'Sindy Forest',
        message: 'Earning $10',
        online: true,
    },
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user6-128x128.jpg',
        name: 'Sebastiano Piccio',
        message: 'Earning $0.05',
        online: false,
    },
    {
        user_logo: 'http://localhost/eroflirts/mod/assets/app_assets/dist/images/user8-128x128.jpg',
        name: 'Marcin Kowalski',
        message: 'Earning $0.10',
        online: false,
    }
];

var messages =  [
    {
        sender_id: 1,
        receiver_id: 2,
        message: 'Testing message for tesitng',
        display_time: '2 hours ago'
    },
    {
        sender_id: 1,
        receiver_id: 2,
        message: 'Hello there hope you are doing well',
        display_time: '1 hours ago'
    },
    {
        sender_id: 2,
        receiver_id: 1,
        message: 'Hey there how are you?',
        display_time: '5 mins ago'
    },
]

var profileXhr;

var app = new Vue({
    el: '#chat-app',

    data: {

        // container height
        chatHeight: (window.innerHeight - 120),

        // profile list
        profiles: [],

        //profile list infinite id
        profileInfiniteId: Date.now(),

        // chat list of a particular profile
        chats: [],

        //active chat
        active_chat: {
            chat_token: null,

            // chat as object
            chat_as: {},

            // chat with object with chat id
            chat_with:{},

            // chat message
            messages: [],

            // input message
            message: '',

            // message state
            sending: false,

            // chat message infinite loading
            messageInfiniteId: 'chat-'+Date.now(),

            // project name
            project_name: '',

        },

        // url path
        url: {
            chat_list: '<?php echo base_url('chat/get_chat'); ?>',
            messages: '<?php echo base_url('chat/chat_messages'); ?>',
            notify: '<?php echo base_url('chat/notify')?>',
            send_message: '<?php echo base_url('chat/send_message')?>',

        },

        // filters
        filters: {
            profile: {
                page: 0
            },
            chat: {
                term:'',
            },
            message: {
                page: 0,
            }
        }
    },

    methods: {

        loadChat: function(){
            var _self = this;
            $.getJSON(this.url.chat_list, function(res){
                _self.chats = [];
                if(res.status == 1){
                    _self.chats.push(...res.data.list);
                }

                _self.$nextTick(function(){
                    _self.chatLoaded();
                });
            });

        },

        chatLoaded: function(){
            var _self = this;
            if(active_chat_token !== null){
                _self.chatList.map(function(item, index){
                    if(item.chat_token == active_chat_token){
                        active_chat_token = null;
                        _self.setChat(index);
                        
                    }
                });
            }
        },


        loadMessage: function($state){ 
            var _self = this;
            var filters = JSON.parse(JSON.stringify(_self.filters.message));
            filters.chat_as = _self.active_chat.chat_as.user_id;
            filters.chat_token = _self.active_chat.chat_token;

            $.getJSON(this.url.messages, filters, function(res){

                if(res.status == 1){
                    if(res.data.list.length > 0){
                        _self.active_chat.messages.unshift(...res.data.list);
                        _self.active_chat.message_total = res.data.list_total;
                        _self.filters.message.page = res.data.next_page;
                        $state.loaded();
                        
                        if(filters.page == 0){
                            // acknowledgement to the server
                            _self.notify();
                        }
                        
                    }else{
                        $state.complete();
                    }	
                }
                
            });
        },

        notify(){
            var _self = this;
            $.post(_self.url.notify, {chat_token: _self.active_chat.chat_token, chat_as: _self.active_chat.chat_as.user_id}, function(res){
                // successfully notify the server about the message delivery
                _self.active_chat.chat_with.chat.new_msg = 0;
            });
        },

        setChat: function(index){
           
            var chat = this.chatList[index];
            this.active_chat.chat_with = {
                user_logo: chat.user_logo,
                name: chat.name,
                online: chat.online,
                user_id: chat.user_id,
                chat: chat
            };

            this.active_chat.project_name = chat.project_name;

            this.active_chat.chat_token = chat.chat_token; // reset chat token

            this.active_chat.messages = []; // reset message
            this.filters.message.page = 0; // reset page
            this.active_chat.messageInfiniteId = 'chat-'+Date.now(); // update infiniteloading id to load message
            
            history.pushState('', '', '<?php echo base_url('chat/browse')?>/'+chat.chat_token);

            this.$nextTick(function(){
                this.$refs.message_input.focus();
            });
            
        },

        resetActiveChat: function(){
            this.active_chat.chat_with = {};
            this.active_chat.chat_token = null;
            this.active_chat.messages = []; // reset message
            this.filters.message.page = 0; // reset page
        },

        filterProfile: function(e){

            var _self = this;
            _self.filters.profile.page = 0;
            _self.filters.profile.term = e.target.value;
            _self.profiles = [];
            if(profileXhr){
                profileXhr.abort();
            }
            _self.profileInfiniteId += 1;
            
        },

        filterChat: function(e){
            //console.log('Chat filter: ' + e.target.value);
            this.filters.chat.term = e.target.value.trim();
        },

        sendMessage: function(){
            var _self = this;
            if(_self.active_chat.message.trim().length == 0){
                return false;
            }

            var postdata = {
                message: _self.active_chat.message,
                chat_token: _self.active_chat.chat_token,
                time: Date.now(),
                sender_id: _self.active_chat.chat_as.user_id
            };

            _self.active_chat.sending = true;

        /*  _self.active_chat.sending = true;
             console.log(postdata);
            setTimeout(function(){
                _self.active_chat.message = '';
                _self.active_chat.sending = false; 
                _self.active_chat.messages.push(postdata);
               
                _self.$nextTick(function(){
                    _self.$refs.message_input.focus();
                    _self.scrollToBottom();
                });

            }, 500);
           
            return; */

            $.ajax({
                url: _self.url.send_message,
                type: 'POST',
                data: postdata,
                dataType: 'json',
                success: function(res){
                    if(res.status == 1){
                        _self.active_chat.message = '';
                        _self.active_chat.sending = false; 
                        _self.active_chat.messages.push(res.data.last_message);
                    
                        _self.$nextTick(function(){
                            _self.$refs.message_input.focus();
                            _self.scrollToBottom();
                        });

                        _self.loadChat();
                    }
                }
            });
        },

        unreadChatMsg: function(){
            var _self = this;
            var filters = {
                chat_as: _self.active_chat.chat_as.user_id,
                chat_token: _self.active_chat.chat_token  
            };
            $.getJSON('<?php echo base_url('chat/unread_message');?>', filters, function(res){

                if(res.status == 1){
                    if(res.data.unread.length > 0){
                        _self.active_chat.messages.push(...res.data.unread);
                       
                    }
                    _self.$nextTick(function(){
                        _self.notify();
                        _self.scrollToBottom();
                        _self.loadChat();
                    });	
                }

            });
        },

        scrollToBottom: function(){
            var container = this.$refs.chat_message_container;
            container.scrollTop = container.scrollHeight;
        },
    },

    computed: {
        chatList: function(){
            var _self = this;
            return _self.chats.filter(function(item){
                if(item.name.toLowerCase().indexOf(_self.filters.chat.term.toLowerCase()) !== -1){
                    return true;
                }else{
                    return false;
                }
            });
        }
    },

    mounted: function(){
		//this.loadChat();
       var selected_profile = <?php echo !empty($profile) ? json_encode($profile) : 'null'; ?>;
       var _self = this;
       if(selected_profile !== null){

            _self.active_chat.chat_as = {
                user_logo: selected_profile.user_logo,
                name: selected_profile.name,
                online: true,
                user_id: selected_profile.user_id,
            };

            _self.active_chat.chat_id = null;
            _self.active_chat.chat_with = {};

            _self.loadChat();
            //_self.resetActiveChat();

           

        }
    },

    filters: {
        sender_name: function(chat){
            return chat.sender_id == chat.user_id ? chat.fname+': ' : 'You: ';
        }
    }

});

<?php /*
var source = new EventSource("<?php echo base_url('chat/check_new')?>");
source.onmessage = function(e) {
  if(e.data > 0){
    app.profileInfiniteId += 1;
    if(app.active_chat.chat_token !== null){
        app.unreadChatMsg();
    }else{
        if(app.active_chat.chat_as.user_id > 0){
            app.loadChat();
        }
    }
    
  }
};*/?>

// invoked by  checkUpdate() in footer: on new message
function after_new_message(){
    app.profileInfiniteId += 1;
    if(app.active_chat.chat_token !== null){
        app.unreadChatMsg();
    }else{
        if(app.active_chat.chat_as.user_id > 0){
            app.loadChat();
        }
    }
}

window.after_new_message = after_new_message;

})();


</script>