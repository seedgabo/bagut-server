<template>
    <div class="col-md-4">
            <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Direct Chat</h3>

              <div class="box-tools pull-right">
                <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="New Messages"></span>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts">
                  <i class="fa fa-comments"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">

                <div class="direct-chat-msg" v-for="msg in messages"> 
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{msg.user.nombre}}</span>
                    <span class="direct-chat-timestamp pull-right">{{msg.timestamp}}</span>
                  </div>
                  <img class="direct-chat-img" v-bind:src="msg.user.imagen" alt="Message User Image"><!-- /.direct-chat-img -->
                  <div class="direct-chat-text">
                    {{msg.message}}
                  </div>
                </div>

              </div>
              <!--/.direct-chat-messages-->
              <!-- /.direct-chat-pane -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <form action="#" method="post">
                <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary btn-flat">Send</button>
                      </span>
                </div>
              </form>
            </div>
            <!-- /.box-footer-->
          </div>
    </div>
</template>




<script>
  export default {

    /**
     * Llamar el socket para cargar los contactos conectados
     * @return null 
     */
    ready() {
        this.listen();
    },

    methods : {

        /**
         * Activa el socket para obtener la lista de contactos conectados
         * @return null
         */
        listen() {
            window.Echo.private('App.User.' + userId)
                .listen('MessageSend', (e) => {
                    console.log(e);
                    var msg = {
                        message: e.message,
                        user: e.userSender,
                        timestamp: "0"
                    }
                    this.messages.push(msg);
                });
        }
    },






    data() { return {
        messages : [],
        users : []
        
    }}
  };
</script>