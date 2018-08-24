<template>
  <v-flex>
    <v-card xs12 class="m-3 px-3">

      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* 給与管理 */ }}
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
        class="elevation-0 px-1"
        item-key="csvid"
      >

        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

        <template slot="items" slot-scope="props">
          <tr @click="showSlipList(props)" v-bind:class="{'red--text': sliptarget == props.item.csvid}">
            <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
            <template v-for="n in (headers.length - 1)">
              <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;">{{ props.item[headers[n].value] }}</td>
            </template>
          </tr>
        </template>

      </v-data-table>

      <v-spacer></v-spacer>

      <div class="mx-5 py-3" v-if="up.result" @dblclick="clearResult">
        <v-alert outline type="info"    v-model="up.insert" v-html="up.insert"></v-alert>
        <v-alert outline type="error"   v-model="up.error"  v-html="up.error"></v-alert>
        <v-btn flat block ref="closebtn" @click="clearResult" color="secondary">閉じる</v-btn>
      </div>

      <v-card-actions>
        <v-flex xs4>
          <v-text-field 
            type="text"
            label="対象年月" 
            v-model="upload_YM" 
            name="" 
            placeholder="明細の対象年月入力(YYYYMM)" 
            required
            class="px-2"
          >
          </v-text-field>
        </v-flex>

        <csv_upload 
          url="/api/admin/slip/upload"
          :updata="{key: 'target', value: upload_YM}" 
          @csvuploaded="csvuploaded" 
          multiple="multiple" 
          @axios-logout="$emit('axios-logout')"
        >
        </csv_upload>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
    <v-spacer></v-spacer>

    <template v-if="sliptarget != 0">
      <!-- admin_sliplist ref="aslst" :target="sliptarget" :csvheader="csvheader" :yyyymm="slip_YM"></admin_sliplist -->
      <admin_sliplist ref="aslst" :target="sliptarget" :yyyymm="slip_YM"></admin_sliplist>
    </template>

  </v-flex>
</template>

<script>
  export default {
    name: 'csvslip',

    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: null, descending: true, },

      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },
        { align: 'left',   sortable: true,  text: '対象年月',   value: 'target' },
        { align: 'left',   sortable: true,  text: 'アップロードファイル名', value: 'filename' },
        { align: 'right',  sortable: true,  text: '有効行数',   value: 'line' },
        { align: 'right',  sortable: true,  text: 'エラー行数', value: 'error' },
        { align: 'left',   sortable: true,  text: '登録者',     value: 'name' },
        { align: 'center', sortable: true,  text: '登録日',     value: 'created_at' },
      ],
      //csvheader: [],

      sliptarget: 0,
      upload_YM: '',
      slip_YM: '',

      up: { 
        insert: '',
        error: '',
        result: false,
      },
    }),

    created() {
      console.log('Component created. :: CsvSlipComponent')
      this.upload_YM = moment().format('YYYYMM').toString()
      this.initialize()
    },

    methods: {
      initialize() {
        this.getSlipCsvList()
      },

      getSlipCsvList() {
        this.clearResult()
        this.sliptarget = 0

        this.loading = true
        axios.post('/api/admin/slip/csvlist')

        .then( function (response) {
          this.loading = false
          console.log(response)
          if(response.data.csvslips) {
            this.tabledata = response.data.csvslips
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

      showSlipList(d) {
        d.expanded = !d.expanded
        this.up.result = false
        if (this.sliptarget == 0) {
//          this.csvheader = d.item.header
          this.slip_YM = d.item.target
          this.sliptarget = d.item.csvid
        }
        else {
          this.sliptarget = 0
        }
      },

      clearResult() {
        this.up.insert = ''
        this.up.error = ''
        this.up.result = false
      },

      csvuploaded(data) {
        console.log('csv uploaded')
        this.getSlipCsvList()
        this.clearResult()

        // import ERROR
        if (data.import.errors) {
          this.up.error +=  data.import.errors.length + ' 件のエラーが発生しました' + '\n'
          for(var i=0; i<data.import.errors.length; i++) {
//            console.log(data.import.errors[i])
            this.up.error +=  '<br>&nbsp;&nbsp;'+ data.import.errors[i].line +'　行目:　'+ data.import.errors[i].error
          }
        }

        // import INSERT
        if (data.import.insert) {
          this.up.insert +=  data.import.insert.length + ' レコードを新規登録しました' + '\n'
          for(var i=0; i<data.import.insert.length; i++) {
//            console.log(data.import.insert[i])
            this.up.insert +=  '<br>&nbsp;&nbsp;'+ data.import.insert[i].line +'　行目:　'+ data.import.insert[i].message
          }
        }

        this.sliptarget = 0
        this.up.result = true
      },

    },
  }
</script>
