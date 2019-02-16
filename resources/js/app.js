/*global require*/
/*global Vue*/
/*eslint no-unused-vars: ["error", { "varsIgnorePattern": "app" }]*/
require('./bootstrap')

window.Vue = require('vue')
import App from './components/App'

const app = new Vue({
    el: '#app',
    render: h => h(App)
})
