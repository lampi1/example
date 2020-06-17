import Vue from 'vue';

//import App from './app/App.vue';
//import DaranVuetable from './components/DaranVuetable.vue';

Vue.component('daran-vuetable', require('./components/DaranVuetable.vue').default);

const app = new Vue({
  el: '#app',
  //template: '<app/>'
  //    render: h => h(App),
});
