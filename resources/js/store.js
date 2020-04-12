import Vue from 'vue'
import Vuex from  'vuex'
Vue.use(Vuex)

const adminRoute= [
    { icon: 'mdi-account-badge-outline', text: 'مدیریت  مطالبات   ' ,link:'demands'},
    { icon: 'mdi-account-badge-outline', text: 'مدیریت  گزارشات   ' ,link:'demands'},
];
const SuperAdminRoute= [
    { icon: 'mdi-account-group-outline', text: '  مدیریت کاربران ' ,link:'users'},
    { icon: 'mdi-account-badge-outline', text: 'مدیریت  مطالبات   ' ,link:'demands'},
    { icon: 'mdi-account-badge-outline', text: 'مدیریت  گزارشات   ' ,link:'demands'},
    { icon: 'mdi-account-badge-outline', text: 'آمار و ارقام     ' ,link:'demands'},
];
const NormalRoute= [
    { icon: 'mdi-account-group-outline', text: 'آخرین مطالبات به رای گذاشته شده' ,link:'selfDemand'},
    { icon: 'mdi-account-group-outline', text: 'مطالبات من  ' ,link:'selfDemand'},
    { icon: 'mdi-account-badge-outline', text: ' پیگیری مطالبه   ' ,link:'addReport'},
    { icon: 'mdi-checkbox-marked-circle-outline', text: 'تایید حساب کاربری' ,link:'verify'},
];
export default new Vuex.Store({
    state:{
      'pic':'',
       'level':localStorage.getItem('loggedLevel')
    },
    mutations:{
     setProfile:(state ,Pic)=>{
       state.pic=Pic;
     },
    },
    getters:{
        items:state =>{
            switch (state.level) {
                case "21232f297a57a5a743894a0e4a801fc3":
                    return adminRoute;
                case "83eebac535d14f791f6ee4dbefe689dc":
                    return SuperAdminRoute;
                default:
                    return NormalRoute;
            }
        }

    }
})
