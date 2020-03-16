require('./bootstrap');
window.Vue = require('vue');
import vuetify from "./vuetify"
import router from "./router"
import store from "./store";
import App from './components/AppComponent'

new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    components:{
        "app-component":App,
    }

});
