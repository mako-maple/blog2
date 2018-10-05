<template>
  <v-btn
    :color="color ? color : 'primary'"
    block flat
    :loading="csvuploading"
    :disabled="csvuploading"
    @click="$refs.input_csvup.click()"
  >
    <v-icon dark class="mr-1">{{icon ? icon : 'cloud_upload' }}</v-icon> {{title ? title : 'CSV アップロード'}}
    <v-progress-circular slot="csvuploading" indeterminate color="primary" dark></v-progress-circular>

    <input
      name="file"
      :value="csvupfile"
      type="file"
      style="display: none"
      ref="input_csvup"
      accept=".csv,.txt"
      :multiple="multiple"
      @change="onFilePicked"
    >
  </v-btn>
</template>

<script>
  export default {
    name: 'csvupload',
 
    props: {
      color: String,
      icon: String,
      title: String,
      url: String,
      multiple: String,
      updata: Object,
    },

    data: () => ({
      csvuploading: false,
      csvupfile: null,
    }),

    created() {
      console.log('CSV Upload Btn Component created.')
    },

    methods: {
      async onFilePicked(e) {
        console.log('on File Picked')
        const files = e.target.files
        if(files[0] == undefined) return

console.log(files)
console.log('length: '+ files.length)
        for (var i=0 ; i<files.length ; i++) {
          // ファイル送信
          console.log("FILE: " + files[i].name)
          console.log("SIZE: " + files[i].size)
          console.log( await this.csvupload(files[i]) )
        }
      },

      csvupload(file) {
        return new Promise((resolve, reject) => {
        console.log('csv upload')
        var config = {
          headers: {'Content-Type': 'multipart/form-data'}
        }

        var formData = new FormData()
        formData.append('csvfile', file)
        if (this.updata) {
          formData.append(this.updata.key, this.updata.value)
        }

        this.csvuploading = true
        axios.post(this.url, formData, config)
        .then( function (response) {
          this.csvuploading = false
//          console.log(response)
          this.$emit('csvuploaded', response.data)
        }.bind(this))

        .catch(function (error) {
          this.csvuploading = false
          console.log(error)
          alert('アップロードに失敗しました' + error)
          if (error.response) {
            if (error.response.status) {
              if (error.response.status == 401 || error.response.status == 419) {
                this.$emit('axios-logout')
              }
            }
          }
        }.bind(this))
        return resolve(file)
        })
      },
    },
  }
</script>
