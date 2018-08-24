<template>
  <v-flex>
    <v-card xs12 class="m-3 px-3">
      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* 社員管理 */ }}
        <v-spacer></v-spacer>
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
        :items="tabledata"
        :pagination.sync="pagination"
        :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
        :loading="loading"
        :search="search"
        class="elevation-0 p-1"
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

      <v-spacer></v-spacer>

      <div class="mx-5 py-3" v-if="up.result" @dblclick="clearResult">
        <v-alert outline type="info"    v-model="up.insert" v-html="up.insert"></v-alert>
        <v-alert outline type="success" v-model="up.update" v-html="up.update"></v-alert>
        <v-alert outline type="error"   v-model="up.error"  v-html="up.error"></v-alert>
        <v-btn flat block ref="closebtn" @click="clearResult" color="secondary">閉じる</v-btn>
      </div>
      <v-card-actions>
        <csv_download url="/api/admin/user/download"></csv_download>
        <csv_upload url="/api/admin/user/upload" @csvuploaded="csvuploaded" @axios-logout="$emit('axios-logout')"></csv_upload>
      </v-card-actions>
      
    </v-card>
  </v-flex>
</template>

<script>
  export default {
    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'name', descending: true, },

      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },
        { align: 'left',   sortable: true,  text: '社員ID',   value: 'loginid' },
        { align: 'left',   sortable: true,  text: '氏名',     value: 'name' },
        { align: 'left',   sortable: true,  text: '権限',     value: 'role' },
      ],

      up: { 
        insert: '',
        update: '',
        error: '',
        result: false,
      },
    }),

    created() {
      console.log('User Component created.')
      this.initialize()
    },

    methods: {
      initialize: function() {
        this.getUsers()
      },

      getUsers() {
        this.loading = true
        axios.post('/api/admin/user')

        .then( function (response) {
          this.loading = false
          console.log(response)
          if (response.data.users) {
            this.tabledata = response.data.users
          }
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
          if (error.response) {
            if (error.response.status) {
              if (error.response.status == 401 || error.response.status == 419) {
                this.$emit('axios-logout')
              }
            }
          }
        }.bind(this))
      },

      clearResult() {
        this.up.insert = ''
        this.up.update = ''
        this.up.error = ''
        this.up.result = false
      },

      csvuploaded(data) {
        console.log('csv uploaded')
        this.clearResult()

        if (data.import) {
          // import ERROR
          if (data.import.errors) {
            this.up.error +=  data.import.errors.length + ' 件のエラーが発生しました' + '\n'
            for(var i=0; i<data.import.errors.length; i++) {
//              console.log(data.import.errors[i])
              this.up.error +=  '<br>&nbsp;&nbsp;'+ data.import.errors[i].line +'　行目:　'+ data.import.errors[i].error
            }
          }

          // import INSERT
          if (data.import.insert) {
            this.up.insert +=  data.import.insert.length + ' レコードを新規登録しました' + '\n'
            for(var i=0; i<data.import.insert.length; i++) {
//              console.log(data.import.insert[i])
              this.up.insert +=  '<br>&nbsp;&nbsp;'+ data.import.insert[i].line +'　行目:　'+ data.import.insert[i].name
            }
          }

          // import UPDATE
          if (data.import.update) {
            this.up.update +=  data.import.update.length + ' レコードを更新しました' + '\n'
            for(var i=0; i<data.import.update.length; i++) {
//              console.log(data.import.update[i])
              this.up.update +=  '<br>&nbsp;&nbsp;'+ data.import.update[i].line +'　行目:　'+ data.import.update[i].name
            }
          }
          this.up.result = true
        }
        if (data.errors) {
          if (data.errors.csvfile) {
            this.up.error +=  data.errors.csvfile.length + ' 件のエラーが発生しました'
            for(var i=0; i<data.errors.csvfile.length; i++) {
              this.up.error +=  '<br>&nbsp;&nbsp;'+ data.errors.csvfile[i]
            }
            this.up.result = true
          }
        }

      },

    },
  }
</script>
