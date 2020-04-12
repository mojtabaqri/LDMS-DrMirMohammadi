import Vue from  'vue';
import VueRouter from 'vue-router';
import Login from './components/LoginComponent'
import Register from './components/RegisterComponent'
import Verify from './components/AdminComponents/VerifyComponent'
import Admin from './components/AdminComponent'
import Users from './components/AdminComponents/Users'
import Demands from './components/AdminComponents/Demands'
import index from './components/HomeComponents/IndexComponent'
import content from "./components/HomeComponents/contentComponent";
import requestDemand from "./components/HomeComponents/requestDemand";
import newReport from "./components/HomeComponents/newReport"
import lastestDemand from "./components/HomeComponents/latestDemand"
Vue.use(VueRouter);
const routes=[

    {
        path:'/',
        name:'index',
        component:index,
        children:[
            {
                path:"content",
                component:content,
                name:'content'
            },
            {
                path:"requestDemand",
                component:requestDemand,
                name:'requestDemand'
            },
            {
                path:"newReport",
                component:newReport,
                name:'newReport'
            },
            {
                path:"lastestDemand",
                component:lastestDemand,
                name:'lastestDemand'
            },

        ],


    }
    ,
    {
        path:"/admin",
        name:"admin",
        component:Admin,
        children:[
            {
                path:"users",
                component:Users,
                name:'users',
                beforeEnter:(to,from,next)=> {
                 let loggedLevel=localStorage.getItem('loggedLevel');
                 if(loggedLevel==="83eebac535d14f791f6ee4dbefe689dc")
                 {
                     next();
                 }
                 else {
                     next('/admin')
                 }
                }
                },
            {
                path:"demands",
                component:Demands,
                name:'demands'
            },
            {
                path:"verify",
                name:"verify",
                component:Verify,
            },
        ],
        beforeEnter:(to,from,next)=> {
            axios.get('api/verify').then(res=>{
                next();
            }).catch(err=>{
                next('/login')
            });
        }

    },
    {
        path:"/login",
        name:"login",
        component:Login,
        beforeEnter (to, from, next) {
            let token=localStorage.getItem('token');
            if (token)
                next('/admin')
            else
                next()
        }

    },
    {
        path:"/register",
        name:"register",
        component:Register,
        beforeEnter (to, from, next) {
            let token=localStorage.getItem('token');
            if (token)
            next('/admin')
            else
                next()
        }

    },

];
const router=new VueRouter({
    routes
});
router.beforeEach((to,from,next)=>{
    const token=localStorage.getItem('token') || null;
    window.axios.defaults.headers['Authorization'] = 'Bearer '+token;
    next();
});
export default router
