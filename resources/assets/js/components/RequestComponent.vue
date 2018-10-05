<template>
  <v-flex>
    <v-card xs12 class="m-3 px-3">
      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* 各種申請 */ }}
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
        :rows-per-page-items='[5,10,25,{"text":"All","value":-1}]'
        :loading="loading"
        :search="search"
        class="elevation-0 p-1"
      >

        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>

            <template v-for="n in (headers.length - 1)">
              <td :class="'text-xs-' + headers[n].align" class="nowrap" xs1>{{ props.item[headers[n].value] }}</td>
            </template>

            <td class="text-xs-left"><v-btn color="info">詳細</v-btn></td>
          </tr>
        </template>
      </v-data-table>

    </v-card>
  </v-flex>
</template>

<style scoped>
  .nowrap {
    white-space: nowrap;
  }
</style>

<script>
  export default {
    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'use_date', descending: true, },

      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },

        { align: 'left',   sortable: true,  text: '状態',         value: 'status' },
        { align: 'left',   sortable: true,  text: '対象日',       value: 'use_date' },
        { align: 'left',   sortable: true,  text: '種別',         value: 'type' },
        { align: 'left',   sortable: true,  text: '対象時間',     value: 'use' },

        { align: 'left',   sortable: true,  text: '申請日時',     value: 'request_date' },
        { align: 'left',   sortable: true,  text: 'メモ',         value: 'memo' },

//        { align: 'left',   sortable: true,  text: '受理日時',     value: 'accept_date' },
//        { align: 'center', sortable: true,  text: '管理者設定',   value: 'admin_flg' },
      ],
    }),

    created() {
      console.log('Request Component created.')
      this.initialize()
    },

    methods: {
      initialize: function() {
        this.getTableData()
      },

      getTableData() {
        this.loading = true
        axios.post('/request')

        .then( function (response) {
          this.loading = false
//          console.log(response)
          if (response.data.tabledata) {
            this.tabledata = response.data.tabledata
            this.setTableDataDetails()
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

      setTableDataDetails() {
        for (var i=0; i<this.tabledata.length; i++) {
          // 申請タイプ
          this.setRequestType(i)
          
          // ステータス
          this.setStatus(i)
        }
      },

      setRequestType(i) {
        var wk=''
        // 申請タイプ
        switch (this.tabledata[i].request_type_id) {
          case  '01':
            wk='有給休暇'
            break
          case  '99':
            wk='不明'
            break
          default:
            wk='不明'
            break
        }
        this.tabledata[i].request_type = wk
      },
      
      setStatus(i) {
        var wk=''
        // ステータス
        switch (this.tabledata[i].accept_type) {
          case  '0':
            wk='申請中'
            break
          case  '1':
            wk='受理済'
            break
          case  '9':
            wk='管理者設定'
            break
          default:
            wk='不明'
            break
        }
        this.tabledata[i].status = wk
      },
    },
  }
</script>
