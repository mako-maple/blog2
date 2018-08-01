<template>
          <v-card xs12 class="m-3">

            <v-card-title class="title">
              <v-icon class="ml-2">supervisor_account</v-icon> CSV明細データ
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
                <template v-for="n in (headers.length - 1)">
                  <td :class="'text-xs-' + headers[n].align">{{ props.item[headers[n].value] }}</td>
                </template>
              </template>

            </v-data-table>
          </v-card>
</template>

<script>
  export default {
    name: 'payslips',
    props: {
      target: String,
      csvheader: Object,
    },
    data: () => ({
      loading: true,
      search: '',
      pagination: { sortBy: 'no', descending: false, },

      show_target: '',
      tabledata: [],
      headers: [],
    }),

    created() {
      console.log('Component created.')
    },

    beforeUpdate() {
      console.log('Component beforeUpdate.')
      if( this.show_target != this.target ) {
        this.show_target = this.target
        this.setHeader(this.csvheader)
        this.initialize(this.target)
      }
    },

    methods: {
      initialize: function(id) {
        this.loading = true
        axios.post('/api/admin/slip/'+ id)

        .then( function (response) {
          this.loading = false
          console.log(response)
          this.tabledata = response.data.payslips
        }.bind(this))

        .catch(function (error) {
          this.loading = false
          console.log(error)
        }.bind(this))
      },
      
      setHeader(h) {
        this.headers = []
        var cnt = 0
        for(var key in h) {
          var line = { sortable: true, align: 'right' }
          if( key == 'no' ) line['align'] = 'center'
          if( key == 'name' ) line['align'] = 'left'
          if( key == 'download' ) line['align'] = 'center'
          line['value'] = key
          line['text'] = h[key]
          this.headers[cnt] = line
          cnt++
        }

console.log('setHeader')
console.log(this.headers)
      },
    },
  }
</script>
