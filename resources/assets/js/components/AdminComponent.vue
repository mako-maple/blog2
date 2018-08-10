<template>
  <v-app id="app">
    <v-navigation-drawer v-model="drawer" clipped fixed app >
      <v-list dense>
        <rlink linkname='home'></rlink> 
        <rlink linkname='admin_user'></rlink> 
        <rlink linkname='admin_csvslip'></rlink> 
      </v-list>
    </v-navigation-drawer>

    <v-toolbar color="primary" dark fixed app clipped-left>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>Application</v-toolbar-title>
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
      <span class="white--text ml-3">
        &copy; 2018
        <a class="white--text" href="https://qiita.com/nobu-maple">Qiita nobu-maple</a>
      </span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      drawer: false,
    }),

    props: {
      id: String,
      name: String,
      role: String,
      logout: String,
    },

    mounted() {
      console.log('Component mounted.')
    },

    methods: {
      axiosLogout: function() {
        axios.post(this.logout)
        .then( function (response) {
          console.log(response)
        }.bind(this))

        .catch(function (error) {
          if (error.response.status === 401) {
            var parser = new URL(this.logout)
            location.href=parser.origin
          }
          console.log(error.response)
        }.bind(this))
      },
    },
  }
</script>
