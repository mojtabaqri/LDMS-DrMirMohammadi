import Vue from  'vue';
import VueRouter from 'vue-router';
import Login from './components/LoginComponent'
import Admin from './components/AdminComponent'
import Users from './components/AdminComponents/Users'
import Demands from './components/AdminComponents/Demands'
import index from './components/HomeComponents/IndexComponent'

Vue.use(VueRouter);
const routes=[

    {
        path:'/',
        name:'index',
        component:index,
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
                name:'users'
            },
            {
                path:"demands",
                component:Demands,
                name:'demands'
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
