
window.Vue = require('vue');

Vue.component('consultor-component', require('./components/ConsultorComponent.vue').default);

import vuetify from './vuetify'

const app = new Vue({
    vuetify,
    el: '#app',
});
