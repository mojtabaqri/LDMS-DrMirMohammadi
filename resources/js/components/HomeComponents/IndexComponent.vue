<template>
    <div class="index" >
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

</style>
