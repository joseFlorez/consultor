
window.Vue = require('vue');

Vue.component('consultor-component', require('./components/ConsultorComponent.vue').default);
Vue.component('grafica-component', require('./components/GraficaComponent.vue').default);
Vue.component('pizza-component', require('./components/PizzaComponent.vue').default);

import vuetify from './vuetify'

const app = new Vue({
    vuetify,
    el: '#app',
});
