<template>
  <v-content>
    <v-container fluid fill-height>
      <v-layout justify-center fluid>
        <v-flex xs12 offset-mx5>
          <v-card xs12>

            <v-card-title class="title">
              <v-icon class="ml-2">supervisor_account</v-icon> 社員管理
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>

            <v-data-table
              :headers="headers"
              :items="users"
              :pagination.sync="pagination"
              :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
              :loading="loading"
              :search="search"
              class="elevation-0"
            >

              <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
              <template slot="items" slot-scope="props">
                <tr @click="props.expanded = !props.expanded">
                  <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
                  <td class="text-xs-left">{{ props.item.loginid }}</td>
                  <td class="text-xs-left">{{ props.item.name }}</td>
                  <td class="text-xs-left">{{ props.item.role }} - {{ props.item.role == '10' ? 'ユーザ' : '管理者' }}</td>
                </tr>
              </template>
            </v-data-table>

          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-content>
</template>

<script>
  export default {
    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'name', descending: true, },

      users: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },
        { align: 'left',   sortable: true,  text: '社員ID',   value: 'loginid' },
        { align: 'left',   sortable: true,  text: '氏名',     value: 'name' },
        { align: 'left',   sortable: true,  text: '権限',     value: 'role' },
      ],
    }),

    props: {
    },

    created() {
      console.log('Component created.')
      this.initialize()
    },

    mounted() {
      console.log('Component mounted.')
    },

    methods: {
      initialize: function() {
        this.getUsers()
      },

      getUsers: function() {
        var params = new URLSearchParams()
        this.loading = true
        axios.post('/api/admin/user/', params, {headers: {'Content-Type': 'application/x-www-form-urlencoded'}})

        .then( function (response) {
          this.loading = false
          console.log(response)
          this.users = response.data.users
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error.response)
        }.bind(this))
      },

    },
  }
</script>
