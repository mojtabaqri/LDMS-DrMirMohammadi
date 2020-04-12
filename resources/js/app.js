require('./bootstrap');
window.Vue = require('vue');
import vuetify from "./vuetify"
import router from "./router"
import store from "./store";
import App from './components/AppComponent'
import crypt from "./crypt";
new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    crypt,
    components:{
        "app-component":App,
    }

});
