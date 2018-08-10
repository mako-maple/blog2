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
        :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
        :loading="loading"
        :search="search"
        class="elevation-0 px-1"
        item-key="csvid"
      >

        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

        <template slot="items" slot-scope="props">
          <tr @click="props.expanded = !props.expanded; sliptarget = props.expanded ? props.item.csvid : 0 " 
              v-bind:class="{'red--text': props.expanded}"
          >
            <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
            <template v-for="n in (headers.length - 1)">
              <td :class="'text-xs-' + headers[n].align">{{ props.item[headers[n].value] }}</td>
            </template>
          </tr>
        </template>

      </v-data-table>

      <v-spacer></v-spacer>

      <v-card-actions>
        <v-flex xs4>
          <v-text-field 
            type="text"
            label="対象年月" 
            v-model="target_YM" 
            name="" 
            placeholder="明細の対象年月入力(YYYYMM)" 
            required>
          </v-text-field>
        </v-flex>

        <csv_upload 
          url="/api/admin/slip/upload/"
          :updata="{key: 'target', value: target_YM}" 
          @csvuploaded="csvuploaded" 
          @axios-logout="$emit('axios-logout')"
        >
        </csv_upload>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
    <v-spacer></v-spacer>

    <template v-if="sliptarget != 0">
      <admin_sliplist :target="sliptarget" :csvheader="csvheader"></admin_sliplist>
    </template>

  </v-flex>
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
        { align: 'left',   sortable: true,  text: '対象年月',   value: 'target' },
        { align: 'left',   sortable: true,  text: 'アップロードファイル名', value: 'filename' },
        { align: 'right',  sortable: true,  text: '有効行数',   value: 'line' },
        { align: 'right',  sortable: true,  text: 'エラー行数', value: 'error' },
        { align: 'left',   sortable: true,  text: '登録者',     value: 'name' },
        { align: 'center', sortable: true,  text: '登録日',     value: 'created_at' },
      ],
      csvheader: { no: 'No', target: '対象', name: '氏名', },

      sliptarget: 0,
      target_YM: '',
    }),

    props: {
    },

    created() {
      console.log('Component created.')
      this.target_YM = moment().format('YYYYMM').toString()
      this.initialize()
    },

    methods: {
      initialize() {
        this.getSlipCsvList()
      },

      getSlipCsvList() {
        var params = new URLSearchParams()
        this.loading = true
        axios.post('/api/admin/csvslip', params)

        .then( function (response) {
          this.loading = false
          console.log(response)
console.log('api/admin/csvslip')
console.log(response)
          this.tabledata = response.data.csvslips
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
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

      csvuploaded: function(data) {
        console.log(data)
        
        if (data.errors) {
          alert(data.errors)
          return
        }

        this.getSlipCsvList()
      },

      csvupload: function(file) {
        console.log('csv upload')
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
          this.getSlipCsvList()

      },
    },
  }
</script>
