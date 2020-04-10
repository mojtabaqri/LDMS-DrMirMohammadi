<template>
    <v-app>
        <v-card width="400" class="mx-auto mt-5">
            <v-card-title class="pb-0">
                <h5>تایید حساب کاربری   </h5>
            </v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field
                        v-model="code"
                        label="کد ارسالی را در اینجا وارد کنید"
                        prepend-icon="mdi-account-circle"
                    />
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="info" @click="requestVerify" >ارسال کد </v-btn>
                <v-btn color="success" @click="verify" >تایید حساب </v-btn>
            </v-card-actions>
        </v-card>
        <v-snackbar
            v-model="snackbar"
        >
            {{ snackbarText }}
            <v-btn
                color="pink"
                text
                @click="snackbar = false"
            >
                بستن
            </v-btn>
        </v-snackbar>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                code:'',
                snackbar:false,
                snackbarText:'',
            }
        },
        methods:{
            requestVerify(){
              axios.post('/api/user/mobileVerify').then(res=>{

              }).catch(err=>{
                  this.snackbarText=err.response.data.msg;
                  this.snackbar=true;

              })
            },
            verify(){
                axios.post('/api/user/mobileVerify',{"code":this.code}).then(res=>{

                }).catch(err=>{
                    this.snackbarText=err.response.data.msg;
                    this.snackbar=true;

                })
            },
        }
    }
</script>
<style scoped>


</style>
