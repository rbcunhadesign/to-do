/*global require*/
/*global Vue*/
/*eslint no-unused-vars: ["error", { "varsIgnorePattern": "app" }]*/
require('./bootstrap')

window.Vue = require('vue')
import App from './components/App'

import VueTextareaAutosize from 'vue-textarea-autosize'
Vue.use(VueTextareaAutosize)

const app = new Vue({
    el: '#app',
    render: h => h(App)
})
