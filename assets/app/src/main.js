import Vue from 'vue';
import 'bootstrap/dist/css/bootstrap-grid.css';

Vue.component('projects', require('./components/projects.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

window.apiUrl = "/wp-json/revista/v1/";
new Vue({ el: '#vue-app' });
