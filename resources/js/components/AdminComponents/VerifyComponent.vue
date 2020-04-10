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
                <v-btn color="info" @click="verify"  > {{text}}  </v-btn>
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
                text:'ارسال کد'
            }
        },
        methods:{
            verify(){

                axios.post('/api/user/mobileVerify',{"code":this.code}).then(res=>{
                    this.snackbarText=res.data.msg;
                    this.snackbar=true;
                    this.text='تایید حساب'
                }).catch(err=>{
                    this.snackbarText=err.response.data.msg;
                    this.snackbar=true;
                    this.text='ارسال کد'
                })
            },
        }
    }
</script>
<style scoped>


</style>
