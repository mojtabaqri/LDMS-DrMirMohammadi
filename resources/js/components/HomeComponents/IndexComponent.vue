<template>
    <div class="index" >
        <ul class="menu">
            <router-link :to="'newReport'">گزایش تخلفات</router-link>
            <router-link :to="'requestDemand'">ایجاد مطالبه جدید</router-link>
            <router-link :to="'lastestDemand'">آخرین مطالبات</router-link>
            <router-link :to="'content'">صفحه اصلی </router-link>

    </ul>
        <v-container >

            <header-component></header-component>
            <v-row>
                <v-col cols="12">
                    <router-view></router-view>
                </v-col>
            </v-row>
            <footer-component></footer-component>
        </v-container>
    </div>


</template>

<script>
    import HeaderComponent from "./HeaderComponent";
    import footerComponent from "./footerComponent";
    export default {
        name:'indexComponent',
        components:{
            HeaderComponent,
            footerComponent
        },
        mounted() {
            console.log('index mounted')
        }

    }
</script>

<style scoped>
    .index{
        background: #e3e3e3;
    }
    .menu {
        display: flex inline;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin:0rem 0rem 0.5rem 0rem;
        background: #d7d7d7;
    }
    .menu>a{
        padding: 0.2rem 2rem;
        list-style: none;
        margin:0 2rem;
        background: #0D47A1;
        color:#ffffff;
    }


</style>
