
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Helper classes
 */
require('./helpers/http');
require('./helpers/params');
require('./helpers/routes');
require('./globals');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('my-vuetable', require('./components/MyVuetable.vue'));
Vue.component('subscription-plan', require('./components/SubscriptionPlan.vue'));

const app = new Vue({
    el: '#app'
});
