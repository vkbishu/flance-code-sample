<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <?php /*?><div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Chat</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6"> </div>
        <!-- /.col --> 
      </div>
      <!-- /.row --> 
    </div>
    <!-- /.container-fluid --> 
  </div><?php */?>
  <!-- /.content-header --> 
  
  <!-- Main content -->
  <section class="content pt-3">
    <div class="container-fluid"> 
      
      <!-- working area -->
      <div class="card cardutline direct-chat direct-chat-pink" id="chat-app">
      <div class="row m-0">
        <div class="col-sm-3 p-0">
          <div class="messages-inbox border-right">
            <div class="messages-headline">
              <h4>Profiles</h4>
              <div class="input-with-icon">
                <input type="text" class="form-control" placeholder="Search">
                <i class="icon-material-outline-search"></i> </div>
            </div>
            <ul>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user5-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>David Peterson</h5>
                    <span>4 hours ago</span> </div>
                </div>
                </a> </li>
              <li class="active-message"> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user7-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sindy Forest</h5>
                    <span>Yesterday</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user3-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sebastiano Piccio</h5>
                    <span>2 days ago</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user4-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Marcin Kowalski</h5>
                    <span>2 days ago</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user5-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>David Peterson</h5>
                    <span>4 hours ago</span> </div>
                </div>
                </a> </li>
              <li class="active-message"> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user7-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sindy Forest</h5>
                    <span>Yesterday</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user3-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sebastiano Piccio</h5>
                    <span>2 days ago</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user4-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Marcin Kowalski</h5>
                    <span>2 days ago</span> </div>
                </div>
                </a> </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-6 p-0">            
            <div class="card-header">
              <h4>Chat as</h4>
              <div class="message-card-header">              
              <div class="user-details">
                <div class="user-avatar status-online">
                	<img src="<?php echo MOD_DIST;?>images/user7-128x128.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="user-name">                    
                    <b>Sarah Bullock</b>
                </div>
              </div>
              <div>With</div>
              <div class="user-details">
                <div class="user-avatar status-online">
                	<img src="<?php echo MOD_DIST;?>images/user6-128x128.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="user-name">                    
                    <b>Alexander Pierce</b>
                </div>
              </div>
              </div>              
            </div>
            <!-- /.card-header -->
            <div class="card-body"> 
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages"> 
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg">
                  <div class="direct-chat-infos clearfix"> <span class="direct-chat-name float-left">Alexander Pierce</span> <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span> </div>
                  <!-- /.direct-chat-infos --> 
                  <img class="direct-chat-img" src="<?php echo MOD_DIST;?>img/user1-128x128.jpg" alt="Message User Image"> 
                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text"> Is this template really for free? That's unbelievable! </div>
                  <!-- /.direct-chat-text --> 
                </div>
                <!-- /.direct-chat-msg --> 
                
                <!-- Message to the right -->
                <div class="direct-chat-msg right">
                  <div class="direct-chat-infos clearfix"> <span class="direct-chat-name float-right">Sarah Bullock</span> <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span> </div>
                  <!-- /.direct-chat-infos --> 
                  <img class="direct-chat-img" src="<?php echo MOD_DIST;?>img/user3-128x128.jpg" alt="Message User Image"> 
                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text"> You better believe it! </div>
                  <!-- /.direct-chat-text --> 
                </div>
                <!-- /.direct-chat-msg --> 
              </div>
              <!--/.direct-chat-messages--> 
              
              <!-- Contacts are loaded here -->
              <div class="direct-chat-contacts">
                <ul class="contacts-list">
                  <li> <a href="#"> <img class="contacts-list-img" src="<?php echo MOD_DIST;?>img/user1-128x128.jpg">
                    <div class="contacts-list-info"> <span class="contacts-list-name"> Count Dracula <small class="contacts-list-date float-right">2/28/2015</small> </span> <span class="contacts-list-msg">How have you been? I was...</span> </div>
                    <!-- /.contacts-list-info --> 
                    </a> </li>
                  <!-- End Contact Item -->
                </ul>
                <!-- /.contatcts-list --> 
              </div>
              <!-- /.direct-chat-pane --> 
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <form action="#" method="post">
                <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                  <span class="input-group-append">
                  <button type="submit" class="btn btn-site">Send</button>
                  </span> </div>
              </form>
            </div>
            <!-- /.card-footer--> 
          
        </div>
        <div class="col-sm-3 p-0">
          <div class="messages-inbox border-left">
            <div class="messages-headline">
              <h4>Chats</h4>
              <div class="input-with-icon">
                <input type="text" class="form-control" placeholder="Search">
                <i class="icon-material-outline-search"></i> </div>
            </div>
            <ul>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user1-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>David Peterson</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li class="active-message"> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user2-160x160.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sindy Forest</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user6-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sebastiano Piccio</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user8-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Marcin Kowalski</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user1-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>David Peterson</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li class="active-message"> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user2-160x160.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sindy Forest</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-offline"></i><img src="<?php echo MOD_DIST;?>images/user6-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Sebastiano Piccio</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
              <li> <a href="#">
                <div class="message-avatar"><i class="status-icon status-online"></i><img src="<?php echo MOD_DIST;?>images/user8-128x128.jpg" alt=""></div>
                <div class="message-by">
                  <div class="message-by-headline">
                    <h5>Marcin Kowalski</h5>
                    <span>Earning $10</span> </div>
                </div>
                </a> </li>
            </ul>
          </div>
        </div>
      </div></div>
    </div>
    <!-- /.container-fluid --> 
  </section>
  <!-- /.content --> 
</div>
<!-- /.content-wrapper -->



<script>
(function(){

'use strict';

var app = new Vue({
    el: '#chat-app',

    data: {

        // profile list
        profiles: [],

        // chat of a particular profile
        chats: [],

        //active chat
        active_chat: {
            chat_as: {},
            chat_with:{},

            // chat message
            messages: []
        }
    },

    methods: {

    },

    computed: {

    },

    
    

});
    
})();
</script>