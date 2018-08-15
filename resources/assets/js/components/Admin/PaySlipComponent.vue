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
            <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;" v-text="props.item[headers[n].value]"></td>
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
      csvheader: Object,
      yyyymm: String,
    },
    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'line', descending: false, },

      card_title: '',
      show_target: '',
      tabledata: [],
      headers: [
        { align: 'center', sortable: false,                  text: 'No',  },
        { align: 'left',   sortable: true,  value: 'name',   text: '氏名',  },
        { align: 'center', sortable: true,  value: 'line',   text: 'CSV行',  },
        { align: 'center', sortable: true,  value: 'download',  text: 'DL回数',  },
        { align: 'left',   sortable: true,  value: 'filename',  text: '修正ファイル名',  },
/*
        { align: 'rigth',  sortable: true,  value: 'item2',  text: 'item2',  },
        { align: 'rigth',  sortable: true,  value: 'item3',  text: 'item3',  },
        { align: 'rigth',  sortable: true,  value: 'item4',  text: 'item4',  },
        { align: 'rigth',  sortable: true,  value: 'item5',  text: 'item5',  },
        { align: 'rigth',  sortable: true,  value: 'item6',  text: 'item6',  },
        { align: 'rigth',  sortable: true,  value: 'item7',  text: 'item7',  },
        { align: 'rigth',  sortable: true,  value: 'item8',  text: 'item8',  },
        { align: 'rigth',  sortable: true,  value: 'item9',  text: 'item9',  },
        { align: 'rigth',  sortable: true,  value: 'item10', text: 'item10', },
        { align: 'rigth',  sortable: true,  value: 'item11', text: 'item11', },
        { align: 'rigth',  sortable: true,  value: 'item12', text: 'item12', },
        { align: 'rigth',  sortable: true,  value: 'item13', text: 'item13', },
        { align: 'rigth',  sortable: true,  value: 'item14', text: 'item14', },
        { align: 'rigth',  sortable: true,  value: 'item15', text: 'item15', },
        { align: 'rigth',  sortable: true,  value: 'item16', text: 'item16', },
        { align: 'rigth',  sortable: true,  value: 'item17', text: 'item17', },
        { align: 'rigth',  sortable: true,  value: 'item18', text: 'item18', },
        { align: 'rigth',  sortable: true,  value: 'item19', text: 'item19', },
*/
      ],
      sliptarget: 0,
    }),

    created() {
      console.log('Component created.')
    },

    beforeUpdate() {
      console.log('Component beforeUpdate.')
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

      initialize: function(id) {
        var params = new URLSearchParams()
        params.append('id', id)

        this.loading = true
        axios.post('/api/admin/slip/sliplist/', params)

        .then( function (response) {
          this.loading = false
          //console.log(response)
          if (response.data.slips) {
console.log('TABLE DATA0')
console.log(response.data.slips)
            this.tabledata = response.data.slips
          }
          else {
            console.log('response error! slip list not found')
          }
//console.log('HEADERS')
//console.log(this.headers)
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
        }.bind(this))
      },

      showSlip(i) {
console.log('showSlip')
console.log(i)
        var params = new URLSearchParams()
        params.append('csv_id', i.item.csv_id)
        params.append('slipid', i.item.slipid)

        this.loading = true
        axios.post('/api/admin/slip/pdf/', params)

        .then( function (response) {
          this.loading = false
          //console.log(response)
          if (response.data) {
//console.log('HTML')
//console.log(response.data)


            // CSV データ取得
            var blob = new Blob([response.data.pdf])

            // ファイル名設定
            var filename = i.item.filename +'_'+ this.card_title +'.pdf'// + moment(Date.now()).format("YYYYMMDD_HHmmss") + '.csv'

            // IE11
            if (window.navigator.msSaveBlob) {
              window.navigator.msSaveBlob(blob, filename)
              window.navigator.msSaveOrOpenBlob(blob, filename)
            }

            // Chrome, Firefox
            else {
              const url = window.URL.createObjectURL(blob)
window.open(url, '_blank');
              const link = document.createElement('a')
              link.href = url
              link.setAttribute('download', filename)
              document.body.appendChild(link)
              link.click()
            }
          }
          else {
            console.log('response error! slip not found')
          }
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
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
