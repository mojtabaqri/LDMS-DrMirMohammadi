<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            right
            app
            :clipped=true
        >
            <v-list dense>
                <v-list-item
                    v-for="item in items"
                    :key="item.text"
                    link
                >
                    <v-list-item-action >
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{ name: item.link }">{{item.text}}</router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link @click.prevent="logout">
                    <v-list-item-action>
                        <v-icon color="grey darken-1">mdi-logout</v-icon>
                    </v-list-item-action>
                    <v-list-item-title class="grey--text text--darken-1"> خروج</v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-action>
                        <v-icon color="grey darken-1">mdi-theme-light-dark</v-icon>
                    </v-list-item-action>
                    <v-switch
                        v-model="theme"
                        color="info"
                        hide-details
                        label=" پوسته شب"
                    ></v-switch>
                </v-list-item>

            </v-list>

        </v-navigation-drawer>

        <v-app-bar
            app
            clipped-left
            color="red"
            dense
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-icon class="mx-4">fab fa-youtube</v-icon>
            <v-toolbar-title class="mr-12 align-center text ">
                <span class="title white--text "  >سامانه ارتباط با نماینده محترم حوزه تفت- میبد دکتر سید جلیل میر محمدی </span>
            </v-toolbar-title>
            <v-spacer />
            <v-row
                align="right"
                style="max-width: 650px"
            >



            </v-row>
        </v-app-bar>

        <v-content>
            <v-container class="">

                <v-row
                    justify="center"
                    align="center"
                >

                    <v-col>
                        <router-view></router-view>
                    </v-col>
                </v-row>
            </v-container>

        </v-content>

    </v-app>

</template>

<script>
    export default {
        props: {
            source: String,
        },
        created(){
         this.$vuetify.theme.dark=true;
        },
        data: () => ({
            theme:true,
            drawer: null,
            items: [
                { icon: 'mdi-account-group-outline', text: '  مدیریت کاربران ' ,link:'users'},
                { icon: 'mdi-account-badge-outline', text: 'آخرین مطالبات ثبت شده ' ,link:'demands'},

            ],

        }),
        methods:{
            logout(){
                localStorage.removeItem('token');
                this.$router.push('/login').then(res=>{console.log(res)}).catch(err=>{console.log(err)})
            }
        },
        watch:{
            theme:function(oldVal,NewVal){
                this.$vuetify.theme.dark=oldVal;
            },
        }

    }
</script>
