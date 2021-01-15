require('./bootstrap');

window.Vue = require('vue');

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import VModal from 'vue-js-modal';

Vue.use(VModal);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('new-project-modal', require('./components/NewProjectModal.vue').default);
Vue.component('dropdown', require('./components/Dropdown.vue').default);

const app = new Vue({
    el: '#app',
});
