<template>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h2>Users</h2></div>

                    <div class="panel-body">
                        <div v-for="user in users">{{ user.name }}</div >
                    </div>
                </div>
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
            window.Echo.join('App.Event')
                .here((users) => {
                    this.users = users;
                })
                .joining((user) => {
                    this.users.push(user);
                })
                .leaving((user) => {
                    console.log(user);
                    var i = this.users.indexOf(user);
                    if(i != -1) {
                        this.users.splice(i, 1);
                    }
                });
        }
    },






    data() { return {
        
        users : []
        
    }}
  };
</script>