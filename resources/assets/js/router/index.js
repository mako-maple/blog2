import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import example_component from '../components/ExampleComponent.vue'
import admin_component   from '../components/AdminComponent.vue'

import home              from '../components/HomeComponent.vue'

import rlink             from '../components/RouterLink.vue'
import csv_upload        from '../components/Admin/CSVUpload.vue'
import csv_download      from '../components/Admin/CSVDownload.vue'

import admin_actlog      from '../components/Admin/ActlogComponent.vue'
import admin_user        from '../components/Admin/UserComponent.vue'
import admin_csvslip     from '../components/Admin/CsvSlipComponent.vue'
import admin_sliplist    from '../components/Admin/PaySlipComponent.vue'


Vue.component('example_component', example_component)
Vue.component('admin_component', admin_component)

Vue.component('rlink', rlink)
Vue.component('csv_upload', csv_upload)
Vue.component('csv_download', csv_download)

Vue.component('admin_sliplist', admin_sliplist)

export default new Router({
  mode: 'history',
  routes: [
    { path: '/home/',      name: 'home',          component: home,          meta: {name: 'ホーム',   icon: 'home'}},
    { path: '/admin/actlog', name: 'admin_actlog',component: admin_actlog,  meta: {name: '操作履歴', icon: 'developer_board'}},
    { path: '/admin/user', name: 'admin_user',    component: admin_user,    meta: {name: '社員管理', icon: 'supervisor_account'}},
    { path: '/admin/slip', name: 'admin_csvslip', component: admin_csvslip, meta: {name: '給与管理', icon: 'playlist_add'}},


    // catch all redirect
    //{ path: '*', component: {template: '<div>{{$route.params.link}}</div>',created() {location.href='/login'}}},
  ]
})
