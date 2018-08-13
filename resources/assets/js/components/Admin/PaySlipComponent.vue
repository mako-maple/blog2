<template>
  <v-card xs12 class="m-3 px-3">
    <v-card-title class="title">
      <v-icon class="mr-2">supervisor_account</v-icon> CSV明細データ
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
        <template v-for="n in (headers.length - 1)">
          <td style="white-space: nowrap;" :class="'text-xs-' + headers[n].align">{{ props.item[headers[n].value] }}</td>
        </template>
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
    },
    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'no', descending: false, },

      show_target: '',
      tabledata: [],
      headers: [
        { align: 'center', sortable: false, value: 'no',     text: 'No',  },
        { align: 'left',   sortable: true,  value: 'target', text: '対象年月',  },
        { align: 'left',   sortable: true,  value: 'name',   text: '氏名',  },
        { align: 'center', sortable: true,  value: 'download',  text: 'DL回数',  },
        { align: 'left',   sortable: true,  value: 'item0',  text: 'item0',  },
        { align: 'left',   sortable: true,  value: 'item1',  text: 'item1',  },
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
      ],
    }),

    created() {
      console.log('Component created.')
    },

    beforeUpdate() {
      console.log('Component beforeUpdate.')
      if( this.show_target != this.target ) {
      console.log('show_target:' + this.show_target + '　=　' + this.target )
        this.show_target = this.target
        this.setHeader(this.csvheader)
        this.initialize(this.target)
      }
    },

    methods: {
      initialize: function(id) {
        var params = new URLSearchParams()
        params.append('id', id)

        this.loading = true
        axios.post('/api/admin/slip/sliplist/', params)

        .then( function (response) {
          this.loading = false
          console.log(response)
          if (response.data.slips) {
            this.tabledata = response.data.slips
// ヘッダーのvalue名でループするか？ 2018.08.13
console.log('TABLE DATA')
console.log(this.tabledata)
            for(var i=0; this.tabledata.length; i++){
              for(let j of Object.keys(this.tabledata[i].slip)) {
//console.log(j + ' : ' + this.tabledata[i].slip[j])
                this.tabledata[i][j] = this.tabledata[i].slip[j]
              }
            }
          }
          else {
            console.log('response error! slip list not found')
          }
console.log('TABLE DATA')
console.log(this.tabledata)
console.log('HEADERS')
console.log(this.headers)
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
        }.bind(this))
      },
      
      setHeader(h) {
//console.log('set header')
//console.log(h)
        //csvheader: { no: 'No', target: '対象', name: '氏名', },
        for(var key in h) {
//console.log('key:'+ key)
          for(var i=0; i<this.headers.length; i++) {
            if(this.headers[i].value == key ) {
//console.log('value: '+ h[key])
              this.headers[i].text = h[key]
              break
            }
          }
        }
/*
          var line = { sortable: true, align: 'right' }
          if( key == 'no' ) line.align = 'center'
          if( key == 'name' ) line.align = 'left'
          if( key == 'download' ) line.align = 'center'
          line.value = key
          line.text = h[key]
          this.headers[this.headers.length] = line
        }
*/

//console.log('setHeader')
//console.log(this.headers)
      },
    },
  }
</script>
