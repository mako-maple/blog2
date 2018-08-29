<template>
  <v-card xs12 class="m-3 px-3">
    <v-card-title class="title">
      <v-icon class="mr-2">supervisor_account</v-icon> CSV明細データ ({{ card_title }})
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
        <tr @click="showSlip(props)" v-bind:class="{'red--text': sliptarget == props.item.slipid}">
          <td class="text-xs-center">{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
          <template v-for="n in (headers.length - 1)">
            <template v-if="headers[n].value == 'name' && props.item[headers[n].value] == null">
              <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;">{{ props.item.error }} : {{ props.item.loginid }}</td>
            </template>
            <template v-else>
              <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;" v-text="props.item[headers[n].value]"></td>
            </template>
          </template>
        </tr>
      </template>

    </v-data-table>
  </v-card>
</template>

<script>
  export default {
    name: 'payslips',

    props: {
      target: Number,
      //csvheader: Object,
      yyyymm: String,
    },

    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'line', descending: false, },

      card_title: '',
      show_target: '',
      sliptarget: 0,

      tabledata: [],
      headers: [
        { align: 'center', sortable: false,                  text: 'No',  },
        { align: 'left',   sortable: true,  value: 'name',   text: '氏名',  },
        { align: 'center', sortable: true,  value: 'line',   text: 'CSV行',  },
        { align: 'center', sortable: true,  value: 'download',  text: 'DL回数',  },
        { align: 'left',   sortable: true,  value: 'filename',  text: '修正ファイル名',  },
      ],
    }),

    created() {
      console.log('PaySlip Component created.')
    },

    beforeUpdate() {
      console.log('PaySlip Component beforeUpdate.')
      this.update()
    },

    methods: {
      update() {
        if( this.show_target != this.target ) {
          this.card_title = String(this.yyyymm).substr(0,4) +'年'+ String(this.yyyymm).substr(4,2) +'月'
          this.show_target = this.target
//        this.setHeader(this.csvheader)
          this.initialize(this.target)
        }
      },

      initialize(id) {
        var params = new URLSearchParams()
        params.append('yyyymm',  this.yyyymm)     // 操作ログ記録用
        params.append('id', id)

        this.loading = true
        axios.post('/api/admin/slip/sliplist', params)

        .then( function (response) {
          this.loading = false
          if (response.data.slips) {
            this.tabledata = response.data.slips
          }
          else {
            console.log('response error! slip list not found')
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

      showSlip(i) {
        if (i.item.error != null) {
          alert('CSVエラー「'+ i.item.error + '」のためPDFを生成できません')
          return
        }

        var params = new URLSearchParams()
        params.append('loginid', i.item.loginid)  // 操作ログ記録用
        params.append('name',    i.item.name)     // 操作ログ記録用
        params.append('yyyymm',  this.yyyymm)     // 操作ログ記録用
        params.append('csv_id',  i.item.csv_id)
        params.append('slipid',  i.item.slipid)
        var config = {
          responseType: 'blob',
        }

        this.loading = true
        axios.post('/api/admin/pdf/slip', params, config)

        .then( function (response) {
          this.loading = false
          if (response.data) {

            // PDFデータ取得
            var blob = new Blob([response.data], { "type" : "application/pdf" })

            // デフォルトファイル名設定
            var f = ''
            if (i.item.filename != null) {
              f = '('+ i.item.filename +')'
            }
            // ファイル名設定
            var filename = '給与明細'
            filename += this.yyyymm
            filename += '_'+ i.item.name.replace(/　/g,'').replace(/ /g,'').replace(/\//g,'')
            filename += f
            filename += '.pdf'
                // + moment(Date.now()).format("YYYYMMDD_HHmmss") + '.csv'

            // IE11
            if (window.navigator.msSaveBlob) {
              window.navigator.msSaveBlob(blob, filename)
              window.navigator.msSaveOrOpenBlob(blob, filename)
            }

            // Chrome, Firefox
            else {
              // SAVE PDF FILE
              const url = window.URL.createObjectURL(blob)
              const link = document.createElement('a')
              link.href = url
              link.setAttribute('download', filename)
              document.body.appendChild(link)
              link.click()
              link.remove()
            }
          }
          else {
            console.log('response error! slip not found')
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
      
      setHeader(h) {
        this.headers = []
        this.headers[this.headers.length] = { align: 'center', sortable: false, text: 'No', }
        this.headers[this.headers.length] = { align: 'center', sortable: true , text: '年月', value: 'target', }
        this.headers[this.headers.length] = { align: 'left',   sortable: true , text: '氏名', value: 'name', }
        this.headers[this.headers.length] = { align: 'right',  sortable: true , text: 'DL回数', value: 'download', }
        this.headers[this.headers.length] = { align: 'left',   sortable: true , text: 'ファイル名', value: 'filename', }
        for(var key in h) {
          if (key == 'item0') { continue; }
          this.headers[this.headers.length] = {
            align: 'right',
            sortable: true,
            value: key,
            text: h[key],
          }
        }
      },
    },
  }
</script>
