<template>
    <v-app>
            <v-row align="center" class="mx-auto">
                <v-col >
                    <v-form
                        ref="form"
                        v-model="valid"
                        :lazy-validation="lazy"
                    >
                        <v-text-field
                            v-model="name"
                            :counter="20"
                            :rules="nameRules"
                            label="نام و نام خانوادگی  :"
                            required
                        ></v-text-field>

                        <v-text-field
                            v-model="email"
                            :rules="emailRules"
                            label="پست الکترونیک"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="password"
                            :rules="passowrdRules"
                            label="رمز عبور ! "
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="confirmPass"
                            label="تکرار رمز عبور !"
                            :rules="[passwordConfirmationRule]"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="phone"
                            :rules="phoneRules"
                            label=" شماره تلفن همراه"
                            required
                        ></v-text-field>
                        <label class="overline"> به عنوان مثال :989025149871+</label>

                        <v-checkbox
                            v-model="checkbox"
                            :rules="[v => !!v || 'کلیه قوانین  وبسایت را باید بپذیرید!']"
                            label="قوانین وبسایت را قبول دارم!"
                            required
                        ></v-checkbox>

                        <v-btn
                            color="success"
                            class="mr-4"
                            @click="register"
                        >
 ثبت نام
                         </v-btn>
                    </v-form>

                </v-col>
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
            </v-row>
    </v-app>
</template>
<script>
    export default {
        data: () => ({
            valid: true,
            name: '',
            snackbar:false,
            snackbarText:'',
            nameRules: [
                v => !!v || 'ورود نام الزامی است!',
            ],
            email: '',
            emailRules: [
                v => !!v || 'پست الکترونیک الزامی است!',
                v => /.+@.+\..+/.test(v) || 'پست الکترونیک صحیح نیست !',
            ],
            password:'',
            passowrdRules:[
                v => !!v || ' ورود رمز عبور  الزامی است!',
            ],
            confirmPass:'',
            phone:'',
            phoneRules:[
                v => !!v || 'شماره تلفن الزامی است !' ,
                v => /^(\+98|0)?9\d{9}$/.test(v) || '   شماره همراه وارد شده صحیح نیست ! !',
            ],
            checkbox: false,
            lazy: false,
        }),
        computed: {
            passwordConfirmationRule() {
                return () => (this.password === this.confirmPass) || 'رمز عبور و تکرار آن یکسان نیست !'
            },
        },
        methods: {
            register(){
                axios.post('/api/register',{
                    "name":this.name,
                    "email":this.email,
                    "password":this.password,
                }).then(res=>{
                    this.snackbarText=res.data.status;
                    this.snackbar=true;
                }).catch(err=>{
                    this.snackbarText=err.response.data.status;
                    this.snackbar=true;
                })
            },

        },
    }
</script>
