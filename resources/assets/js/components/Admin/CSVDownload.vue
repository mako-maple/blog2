<template>
  <v-btn
    :color="color ? color : 'primary'"
    block flat
    :loading="csvdownloading"
    :disabled="csvdownloading"
    @click="csvdownload(filename, url)"
  >
    <v-icon dark class="mr-1">{{icon ? icon : 'cloud_download' }}</v-icon> {{title ? title : 'CSV ダウンロード'}}
    <v-progress-circular slot="csvdownload" indeterminate color="indigo" dark></v-progress-circular>
  </v-btn>
</template>

<script>
  export default {
    name: 'csvdownload',

    props: {
      color: String,
      icon: String,
      title: String,
      url: String,
      filename: String,
    },

    data: () => ({
      csvdownloading: false,
    }),

    created() {
      console.log('CSV Download Btn Component created.')
    },
    
    methods: {
      csvdownload(filename, url) {
        console.log('csv download btn clicked')
//        var params = new URLSearchParams()
        var config = {
          responseType: 'blob',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }
        this.csvdownloading = true
//        axios.post(this.url, params, config)
        axios.post(this.url, config)

        .then( function (response) {
          this.csvdownloading = false

          // CSV データ取得
          var blob = new Blob([response.data])

          // ファイル名設定
          if(!filename) {
            filename = this.$route.meta.name
          }
          filename += '_' + moment(Date.now()).format("YYYYMMDD_HHmmss") + '.csv'

          // IE11
          if (window.navigator.msSaveBlob) { 
            window.navigator.msSaveBlob(blob, filename)
            window.navigator.msSaveOrOpenBlob(blob, filename)
          } 
      
          // Chrome, Firefox
          else {
            const url = window.URL.createObjectURL(blob)
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', filename)
            document.body.appendChild(link)
            link.click()
            link.remove()
          }
        }.bind(this))

        .catch(function (error) {
          this.csvdownloading = false
          console.log(error)
          alert('ダウンロードに失敗しました' + error)
          if (error.response) {
            if (error.response.status) {
              if (error.response.status == 401 || error.response.status == 419) {
                this.$emit('axios-logout')
              }
            }
          }
        }.bind(this))
      },
    },
  }
</script>
