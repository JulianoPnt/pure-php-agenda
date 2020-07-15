import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import '../node_modules/bootstrap/dist/css/bootstrap.min.css'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faTrash } from '@fortawesome/free-solid-svg-icons'
import { faPen } from '@fortawesome/free-solid-svg-icons'
import { faSpinner } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import axios from 'axios'

Vue.prototype.$http = axios

library.add(faTrash)
library.add(faPen)
library.add(faSpinner)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false

Vue.mixin({ //Usable in all application
  data: function() {
    return {
      api_url:'http://localhost/test-cohros/api/'
    }
  },
  methods: {
    checkToken() {
        return this.$http({
            url: this.api_url + 'auth/checktoken',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('user_token')
            },
        })
        .then(response => {
            console.log(response);
        })
        .catch(error => {
            console.log(error);
            localStorage.removeItem('user_token');
            localStorage.removeItem('expires_at');
            this.$router.push('/login');
        });
    },
 }
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
