<template>
    <v-app id="inspire">
        <v-content>
            <v-container
                class="fill-height"
                fluid
            >
                <v-row
                    align="center"
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="8"
                        md="4"
                    >
                        <v-card class="elevation-12">
                            <v-toolbar
                                color="primary"
                                dark
                                flat
                            >
                                <v-toolbar-title>ورود </v-toolbar-title>
                                <v-spacer />

                            </v-toolbar>
                            <v-card-text>

                                <v-progress-linear
                                    :active="loading"
                                    :indeterminate="loading"
                                    absolute
                                    top
                                     color="white accent-4"
                                ></v-progress-linear>
                                <v-form
                                    ref="form"
                                    v-model="valid"
                                >

                                    <v-text-field
                                        label="نام کاربری "
                                        name="login"
                                        prepend-icon="mdi-account-tie"
                                        type="text"
                                        v-model="user"
                                        :rules="userRules"
                                        required
                                    />

                                    <v-text-field
                                        id="password"
                                        label="گذرواژه"
                                        name="password"
                                        prepend-icon="mdi-lock"
                                        type="password"
                                        v-model="pass"
                                        :rules="passwordRules"
                                        required
                                    />
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn :disabled="!valid" color="primary" @click.prevent="login">ورود</v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-snackbar
                            v-model="snackbar"
                        >
                            {{ text }}
                            <v-btn
                                color="pink"
                                text
                                @click="snackbar = false"
                            >
                                بستن
                            </v-btn>
                        </v-snackbar>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        data(){
          return{
              valid:true,
              user:'',
              pass:'',
              loading:false,
              text:'',
              snackbar:false,
              userRules: [
                  v => !!v || ' نام کاربری الزامی است! ',
                  v => /.+@.+\..+/.test(v) || 'نام کاربری صحیح نیست! ',
              ],
              passwordRules: [
                  v => !!v || 'گذرواژه مورد نیار است!  ',
                  v => (v && v.length <= 12) || 'گذرواژه نباید کمتر از 12 کاراکتر باشد',
              ],
          }
        },
        props: {
            source: String,
        },
        methods:{
          login(){
              axios.interceptors.request.use((config)=> {
                  this.loading=true;
                  return config;
              },(error)=> {
                  this.loading=false;
                  return Promise.reject(error);
              });
              axios.interceptors.response.use((response) =>{
                  this.loading=false;
                  return response;
              },  (error) =>{
                  this.loading=false;
                  return Promise.reject(error);
              });
              axios.post('/api/login',{'email':this.user,'password':this.pass}).then(res=>{
                  localStorage.setItem("token",res.data.token);
                  this.$router.push('/admin').then(res=>{console.log(res)}).catch(err=>{console.log(err)})
              }).catch(err=>{
                  this.text=err.response.data.status;
                  this.snackbar=true;
              });
          }
        },
        mounted() {
            console.log('login Component mounted.')
        }
    }
</script>
<style scoped>


</style>
