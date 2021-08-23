import Vue from 'vue'
import Vuex from 'vuex'
import App from './App.vue'
import {DateTime} from 'luxon'

Vue.use(Vuex)

import store from './assets/store/index';

Vue.config.productionTip = false

Vue.prototype.$dateISOFormat = (date) => {
  return DateTime.fromISO(date).toFormat('dd/MM/yyyy hh:mm:ss')
}

Vue.prototype.$dateToFormat = (date, finalFormat, originalFormat = 'y-M-d') => {
  console.log(date);
  return DateTime.fromFormat(date, originalFormat).toFormat(finalFormat)
}

new Vue({
  render: h => h(App),
  store,
}).$mount('#app')
