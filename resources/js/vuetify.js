import Vue from 'vue'
import Vuetify from 'vuetify'
import colors from 'vuetify/lib/util/colors'
import fa from 'vuetify/es5/locale/fa'
Vue.use(Vuetify);
export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: colors.red.darken3, // #E53935 red darken-3
                secondary: colors.red.lighten4, // #FFCDD2
                accent: colors.indigo.base, // #3F51B5
            },
            dark: {
                primary: colors.green.accent4, // #E53935 red darken-3
                secondary: colors.red.lighten4, // #FFCDD2
                accent: colors.indigo.base, // #3F51B5
            },
        },
    },
    lang: {
        locales: {fa},
        current: 'fa'
    },
    rtl:true
})
