<template>
  <v-app id="app">
    <v-navigation-drawer v-model="drawer" clipped fixed app >
      <v-list dense>
        <rlink linkname='home'></rlink> 
        <rlink linkname='admin_user'></rlink> 
        <rlink linkname='admin_csvslip'></rlink> 
        <rlink linkname='admin_actlog'></rlink> 
      </v-list>
    </v-navigation-drawer>

    <v-toolbar color="primary" dark fixed app clipped-left>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>{{title}}</v-toolbar-title>
      <v-spacer></v-spacer>
      {{ name }}
      <v-btn icon @click="axiosLogout()">
        <v-tooltip left>
          <v-icon slot="activator" color="white" dark >exit_to_app</v-icon>
          <span>ログアウト</span>
        </v-tooltip>
      </v-btn>
    </v-toolbar>

    <v-content>
      <v-container fluid fill-height
        <v-layout justify-center fluid column>
          <v-fade-transition mode="out-in">
            <router-view @axios-logout="axiosLogout"></router-view>
          </v-fade-transition>
        </v-layout>
      </v-container>
    </v-content>

    <v-footer color="primary" dark app fixed>
      <span class="white--text ml-3" v-html="footer"></span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      drawer: false,
      footer: 'footer',
      title: 'title',
    }),

    props: {
      id: String,
      name: String,
      role: String,
      logout: String,
    },

    mounted() {
      console.log('AdminComponent mounted.')
//      console.log(process.env)
      if (process.env.MIX_FOOTER_COPY) { this.footer = process.env.MIX_FOOTER_COPY }
      if (process.env.MIX_APP_NAME) { this.title = process.env.MIX_APP_NAME }
    },

    methods: {
      axiosLogout: function() {
        axios.post(this.logout)
        .then( function (response) {
          console.log(response)
        }.bind(this))

        .catch(function (error) {
          console.log(error)
          if (error.response) {
            if (error.response.status) {
              if (error.response.status == 401 || error.response.status == 419) {
                var parser = new URL(this.logout)
                location.href=parser.origin
              }
            }
          }
        }.bind(this))
      },
    },
  }
</script>
