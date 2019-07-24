import Vue from 'vue'
import App from './App.vue'
import router from './router' 
import store from './store/index.js'
// Bootstrap
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import { LayoutPlugin } from 'bootstrap-vue'
import { CardPlugin } from 'bootstrap-vue'

// Font awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

//Sidear

library.add(faUserSecret)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false
Vue.use(BootstrapVue)
Vue.use(LayoutPlugin)
Vue.use(CardPlugin)

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')


