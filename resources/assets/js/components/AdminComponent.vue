<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-default">
          <div class="card-header">Admin Component</div>
          <div class="card-body">
            I'm an admin component.<br>
            <font size="+5">ID: {{ id }}</font><br>
            <font size="+5">Name: {{ name }}</font><br>
            <font size="+5">Role: {{ role }}</font>
            
            <button v-on:click="axiosLogout">logout</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: () => ({
    }),
    
    props: {
      id: String,
      name: String,
      role: String,
      logout: String,
    },

    mounted() {
      console.log('Component mounted.')
      console.log('name :' + this.name )
      console.log('logout :' + this.logout )
    },
    
    methods: {
      axiosLogout: function() {
        var params = new URLSearchParams()
        axios.post(this.logout, params, {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
        .then( function (response) {
          console.log(response)
        }.bind(this))
        .catch(function (error) {
          if (error.response.status === 401) {
            var parser = new URL(this.logout)
            location.href=parser.origin
          }
          else if (error.response.status === 419) {
            alert('通信エラー : ' + error.response.status === 419)
            var parser = new URL(this.logout)
            location.href=parser.origin
          }
          console.log(error.response)
        }.bind(this))
      },
    },
  }
</script>
