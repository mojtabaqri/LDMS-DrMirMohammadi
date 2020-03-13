require('./bootstrap');
window.Vue = require('vue');
import vuetify from "./vuetify"
import router from "./router"
import App from './components/AppComponent'

new Vue({
    el: '#app',
    router,
    vuetify,
    components:{
        "app-component":App,
    }

});
