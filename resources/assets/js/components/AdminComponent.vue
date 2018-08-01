<template>
  <v-app id="app">
    <v-navigation-drawer v-model="drawer" clipped fixed app >
      <v-list dense>
        <router-link :to="{name: 'home'}">
          <v-list-tile @click="drawer = !drawer">
            <v-list-tile-action> <v-icon>home</v-icon> </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>HOME</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </router-link>

        <router-link :to="{name: 'admin_user'}">
          <v-list-tile @click="drawer = !drawer">
            <v-list-tile-action> <v-icon>supervisor_account</v-icon> </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>社員管理</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </router-link>

        <router-link :to="{name: 'admin_slip'}">
          <v-list-tile @click="drawer = !drawer">
            <v-list-tile-action> <v-icon>supervisor_account</v-icon> </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>給与管理</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </router-link>
      </v-list>
    </v-navigation-drawer>

    <v-toolbar color="indigo" dark fixed app clipped-left>
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

    <v-fade-transition mode="out-in">
      <router-view @axios-logout="axiosLogout"></router-view>
    </v-fade-transition>

    <v-footer color="indigo" dark app fixed>
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
