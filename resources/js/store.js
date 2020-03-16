import Vue from 'vue'
import Vuex from  'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state:{
      'pic':''
    },
    mutations:{
     setProfile:(state ,Pic)=>{
       state.pic=Pic;
     }
    },

})
