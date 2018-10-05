
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
require('babel-polyfill')

// Vue
import Vue from 'vue'
import colors from 'vuetify/es5/util/colors'

// Vuetify
import Vuetify from 'vuetify'
Vue.use(Vuetify, {
  theme: {
    primary: colors.deepOrange.base,
    secondary: colors.orange.base,
    accent: colors.indigo.base,
//    primary: colors.indigo.base,
//    secondary: colors.blue.base,
//    accent: colors.amber.base,
  }
});

import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

// Vue-Router
import router from './router'

// moment
window.moment = require('moment')
window.moment.locale('ja', {
    weekdays: ["日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"],
    weekdaysShort: ["日","月","火","水","木","金","土"],
})


// main app
const app = new Vue({
  el: '#app',
  router,
});
