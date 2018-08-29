<template>
  <v-flex>
    <v-card xs12 class="m-3 px-3">
      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* ホーム */ }}
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
          <tr>
            <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
            <template v-for="n in (headers.length - 1)">
              <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;" v-text="props.item[headers[n].value]"></td>
            </template>
          </tr>
        </template>
      </v-data-table>

      <v-spacer></v-spacer>

      <v-card-actions>
        <csv_download url="/api/admin/actlog/download"></csv_download>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
      </v-card-actions>
      
    </v-card>
  </v-flex>
</template>

<script>
  export default {
    name: 'actlog',

    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'actid', descending: true, },

      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',     },
        { align: 'left',   sortable: true,  text: '日時',   value: 'accessdate' },
        { align: 'left',   sortable: true,  text: '氏名',   value: 'name' },
        { align: 'left',   sortable: true,  text: '操作',   value: 'action' },
//        { align: 'left',   sortable: true,  text: 'url',    value: 'url' },
//        { align: 'left',   sortable: true,  text: 'method', value: 'method' },
//        { align: 'left',   sortable: true,  text: 'status', value: 'status' },
//        { align: 'left',   sortable: true,  text: 'MSG',    value: 'message' },
        { align: 'left',   sortable: true,  text: '対象',   value: 'target' },
        { align: 'left',   sortable: true,  text: 'IP',     value: 'remote_addr' },
        { align: 'left',   sortable: true,  text: 'UA',     value: 'user_agent' },
      ],
    }),

    created() {
      console.log('Actlog Component created.')
      this.initialize()
    },

    methods: {
      initialize: function() {
        this.getActlogs()
      },

      getActlogs() {
        this.loading = true
        axios.post('/api/admin/actlog')

        .then( function (response) {
          this.loading = false
//        console.log(response)
          if (response.data.actlogs) {
            this.tabledata = this.setActRouteName(response.data.actlogs)
//            this.tabledata = response.data.actlogs
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

      setActRouteName(data) {
        for(let i=0; i<data.length; i++) {
          data[i].target = ''
          switch (data[i].route) {
            // Login - Logout
            case 'show.login':           data[i].action = 'ログイン画面'; break;
            case 'login':                data[i].action = 'ログイン'; break;
            case 'logout':               data[i].action = 'ログアウト'; break;

            // USER
            case 'admin.user':           data[i].action = '管理：社員管理'; break;
            case 'admin.user.download':  data[i].action = '管理：社員管理 Download'; break;
            case 'admin.user.upload':    data[i].action = '管理：社員管理 Upload'; break;

            // SLIP
            case 'admin.slip.csvlist':   data[i].action = '管理：給与管理'; break;
            case 'admin.slip.upload':    data[i].action = '管理：給与CSV Upload'; break;
            case 'admin.slip.sliplist':  data[i].action = '管理：給与CSV明細'; 
              var t = this.setMessage2Target(data[i])
              if (t.yyyymm) {
                data[i].target += t.yyyymm +' '
              }
              if (t.id) {
                data[i].target += 'ID: '+ t.id
              }
              break;

            // PDF
            case 'admin.pdf.slip':       data[i].action = '管理：明細PDF表示'
              var t = this.setMessage2Target(data[i])
              if (t.yyyymm) {
                data[i].target += t.yyyymm +' '
              }
              if (t.name) {
                data[i].target += t.name
              }
              break;

            // ActionLOG
            case 'admin.actlog':         data[i].action = '管理：操作履歴'; break;

            // Other
            default:                     data[i].action = data[i].route
          }
        }
        return data
      },

      setMessage2Target(data) {
        try {
          var d = (new Function("return " + data.message ))()
          return d 
        }
        catch (e) {
          return data
        }
      }

    },
  }
</script>
