<template>
  <v-content>
    <v-container fluid fill-height
      <v-layout justify-center fluid column>
        <v-flex>
          <v-card xs12 class="m-3">

            <v-card-title class="title">
              <v-icon class="ml-2">supervisor_account</v-icon> 給与管理
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
              class="elevation-0"
            >

              <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

              <template slot="items" slot-scope="props">
                <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>

                <template v-for="n in (headers.length - 1)">
                  <td :class="'text-xs-' + headers[n].align">{{ props.item[headers[n].value] }}</td>
                </template>

              </template>

              <template slot="footer" v-if="(up_result.length + up_error.length) != 0">
                <td colspan="100%">
                  <v-alert
                    v-model="up_result.length != 0"
                    dismissible
                    type="success"
                    outline
                  > 
                    <pre>{{up_result}}</pre>
                  </v-alert>
                  <v-alert
                    :value="up_error.length != 0"
                    dismissible
                    outline
                    type="error"
                  > 
                    <pre>{{up_error}}</pre>
                  </v-alert>
                </td>
              </template>
            </v-data-table>

            <v-spacer></v-spacer>

            <v-card-actions>
              <v-btn
                color="indigo"
                block flat
                :loading="csvdownloading"
                :disabled="csvdownloading"
                @click="csvdownload"
              >
                <v-icon dark class="mr-1">cloud_download</v-icon> CSV ダウンロード
                <v-progress-circular slot="csvdownload" indeterminate color="indigo" dark></v-progress-circular>
              </v-btn>

              <v-btn
                color="indigo"
                block flat
                :loading="csvuploading"
                :disabled="csvuploading"
                @click="$refs.csvup.click()"
              >
                <v-icon dark class="mr-1">cloud_upload</v-icon> CSV アップロード
                <v-progress-circular slot="csvuploading" indeterminate color="indigo" dark></v-progress-circular>
              </v-btn>

              <input
                name="file"
                :value="csvupfile"
                type="file"
                style="display: none"
                ref="csvup"
                accept=".csv,.txt"
                @change="onFilePicked"
              >
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
              <v-spacer></v-spacer>


          <sliplist target="123" :csvheader="csvheader"></sliplist>
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
      pagination: { sortBy: null, descending: true, },

      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },
        { align: 'left',   sortable: true,  text: '対象',       value: 'target' },
        { align: 'left',   sortable: true,  text: 'ファイル名', value: 'name' },
        { align: 'left',   sortable: true,  text: '社員数',     value: 'line' },
        { align: 'left',   sortable: true,  text: 'エラー数',   value: 'error' },
        { align: 'left',   sortable: true,  text: '登録者',     value: 'user' },
        { align: 'left',   sortable: true,  text: '登録日',     value: 'created_at' },
      ],
      csvheader: { 
        no: 'No',
        target: '対象',
        name: '生天目',
         
      },

      csvdownloading: false,
      csvuploading: false,
      csvupfile: null,
      up_error: '',
      up_result: '',
    }),

    props: {
    },

    created() {
      console.log('Component created.')
      console.log(this.headers)
      this.initialize()

    },

    mounted() {
      console.log('Component mounted.')
    },

    methods: {
      initialize: function() {
        this.getCsv()
      },

      getCsv: function() {
        var params = new URLSearchParams()
        this.loading = true
        axios.post('/api/admin/slip/', params)

        .then( function (response) {
          this.loading = false
          console.log(response)
          this.csvdata = response.data.csvdata
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error.response)
        }.bind(this))
      },

      csvdownload: function() {
        var params = new URLSearchParams()
        var config = {
          responseType: 'blob',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }
        this.csvdownloading = true
        axios.post('/api/admin/user/download/', params, config)

        .then( function (response) {
          this.csvdownloading = false
          console.log(response)

          // Get FileName
          var filename = 'userlist.csv'
          response.headers['content-disposition'].split(/;|\s/).forEach( function( value ) {
            if(value.match(/^filename=/i)) filename = value.replace(/^filename=/i,'')
          })

          // Save CSV
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url
          link.setAttribute('download', filename)
          document.body.appendChild(link)
          link.click()
        }.bind(this))

        .catch(function (error) {
          this.csvdownloading = false
          console.log(error.response)
          alert('ダウンロードに失敗しました' + error.response.status + ' (' + error.response.statusText + ')')
        }.bind(this))
      },

      onFilePicked: function(e) {
        console.log('on File Picked')
        const files = e.target.files
        if(files[0] == undefined) return
        console.log("FILE: " + files[0].name)
        console.log("SIZE: " + files[0].size)

        // ファイル送信
        this.csvupload(files[0])
      },

      csvupload: function(file) {
        console.log('csv upload')
        var config = {
          headers: {'Content-Type': 'multipart/form-data'}
        }

        var formData = new FormData()
        formData.append('csvfile', file)

        this.up_error = ''
        this.up_result = ''
        this.csvuploading = true
        axios.post('/api/admin/slip/upload/', formData, config)
        .then( function (response) {
          this.csvuploading = false
          console.log(response)

          // error
          if (response.data.import.errors) {
            for(var i=0; i<response.data.import.errors.length; i++) {
              console.log(response.data.import.errors[i])
              this.up_error +=  'CSV ' + response.data.import.errors[i].line + '行目 ' 
              this.up_error +=  response.data.import.errors[i].error + '\n'
            }
          }
          if (response.data.import.insert) {
            this.up_result +=  response.data.import.insert.length + ' レコードを新規登録しました' + '\n'
          }
          if (response.data.import.update) {
            this.up_result +=  response.data.import.update.length + ' レコードを更新しました' + '\n'
          }
        }.bind(this))

        .catch(function (error) {
          this.csvuploading = false
          console.log(error)
          alert('アップロードに失敗しました' + error.response.status + ' (' + error.response.statusText + ')')
          if (error.response.status === 401) {
            this.$emit('axios-logout')  
          }
          else if (error.response.status === 419) {
            this.$emit('axios-logout')  
          }
        }.bind(this))
      },

    },
  }
</script>