import Vue from  'vue';
import VueRouter from 'vue-router';
import Login from './components/LoginComponent'
import Register from './components/RegisterComponent'
import Verify from './components/AdminComponents/VerifyComponent'
import Admin from './components/AdminComponent'
import Users from './components/AdminComponents/Users'
import Demands from './components/AdminComponents/Demands'
import Reports from "./components/AdminComponents/Reports";
Vue.use(VueRouter);
const routes=[
    {
        path:"/",
        beforeEnter:(to,from,next)=> {
            axios.get('api/verify').then(res=>{
                next('/panel');
            }).catch(err=>{
                next('/login')
            });
        }
    },
    {
        path:"/panel",
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
                     next('/panel')
                 }
                }
                },
            {
                path:"demands",
                component:Demands,
                name:'demands',
                beforeEnter:(to,from,next)=> {
                    let loggedLevel=localStorage.getItem('loggedLevel');
                    if((loggedLevel==="83eebac535d14f791f6ee4dbefe689dc")|| loggedLevel==="21232f297a57a5a743894a0e4a801fc3")
                    {
                        next();
                    }
                    else {
                        next('/panel')
                    }
                }
            },
            {
                path:"reports",
                component:Reports,
                name:'reports',
                beforeEnter:(to,from,next)=> {
                    let loggedLevel=localStorage.getItem('loggedLevel');
                    if((loggedLevel==="83eebac535d14f791f6ee4dbefe689dc")|| loggedLevel==="21232f297a57a5a743894a0e4a801fc3")
                    {
                        next();
                    }
                    else {
                        next('/panel')
                    }
                }
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
                next('/panel')
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
            next('/panel')
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
