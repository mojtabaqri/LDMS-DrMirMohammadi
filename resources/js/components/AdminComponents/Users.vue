<template>

    <v-data-table
        :headers="headers"
        :items="desserts.data"
         @pagination="paginate"
        :items-per-page="5"
        show-select
        @input="selectAll"
        :footer-props="{
        itemsPerPageOptions:[5,10,15],
        itemsPerPageText:'نمایش  ',
        pageText:'کاربر در این صفحه ',
        'show-current-page':true,
        'show-first-last-page':true,



    }"
        sort-by="calories"
        class="elevation-1"
        :loading="loading" loading-text="اندکی صبر کنید ...."    >
        <template v-slot:top>
            <v-toolbar flat >
                <v-toolbar-title>مدیریت کاربران سیستم </v-toolbar-title>
                <v-divider
                    class="mx-5 mt-4"
                    inset
                    vertical
                ></v-divider>

                <v-col cols="3" sm="3" class="mt-3">
                    <v-text-field label="جستجو..." @input="search">
                    </v-text-field>
                </v-col>
                <v-spacer></v-spacer>

                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2 mx-2" v-on="on">کاربر جدید</v-btn>
                        <v-btn color="error" dark class="mb-2 mx-2" v-on:click="deleteAll"> حذف کاربر</v-btn>
                    </template>

                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="12">
                                    <v-form
                                        ref="form"
                                        v-model="valid"
                                        >
                                            <v-text-field v-model="editedItem.name" :rules="nameRules" required label="نام"></v-text-field>
                                            <v-text-field color="error" @blur="checkValidMail" :error-messages="invalidEmailMsg" :success-messages="validEmailMsg" v-model="editedItem.email" :rules="emailRules" required label="ایمیل"></v-text-field>
                                        <v-select
                                            v-model="editedItem.role"
                                            :items="roles"
                                            label="سطح دسترسی"
                                        ></v-select>
                                    </v-form>
                                    </v-col>


                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">انصراف</v-btn>
                            <v-btn color="blue darken-1" text @click="save" :disabled="!valid">ذخیره</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
            <v-snackbar
                v-model="snackbar"
                :left=true
                :color="snackbarColor"
                :bottom=true
            >
                {{ snackbarText }}
                <v-btn
                    color="white"
                    text
                    @click="snackbar = false"
                >
                    بستن
                </v-btn>
            </v-snackbar>
        </template>
        <template v-slot:item.role="{ item }">
            <v-edit-dialog cancel-text="انصراف" save-text="اعمال"  large block persistent :return-value.sync="item.role"  @save="updateRole(item)">
                {{item.role}}
                <template v-slot:input>
                    <h1>تغیر سطح دسترسی </h1>
                </template>
                <template v-slot:input>
                    <v-select
                        v-model="item.role"
                        :items="roles"
                        label="سطح دسترسی"
                    ></v-select>
                </template>

            </v-edit-dialog>
        </template>
        <template v-slot:item.photo="{ item }">
            <v-img
                :src="item.photo"
                :lazy-src="item.photo"
                aspect-ratio="1"
                class="grey lighten-2"
                max-width="50"
                max-height="50"
            >
            </v-img>
        </template>
        <template v-slot:item.action="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>

        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>

<script>
    export default {
        data: () => ({
            validEmailMsg:'',
            invalidEmailMsg:'',
            roles:[],
            snackbarText:'',
            selected:[],
            snackbarColor:'',
            snackbar:false,
            loading:false,
            valid:true,
            dialog: false,
            headers: [
                {
                    text: 'کد',
                    align: 'start',
                    sortable: false,
                    value: 'id',
                },
                { text: 'نام', value: 'name' },
                { text: 'سمت  ', value: 'role' },
                { text: 'ویرایش شده در ', value: 'updated_at' },
                { text: 'ایمیل', value: 'email' },
                { text: 'ایمیل تاییده شده در ', value: 'email_verified_at' },
                { text: 'تصویر کاربر', value: 'photo' },
                { text: 'عملیات', value: 'action', sortable: false },
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {
                id:'',
                name: '',
                email:'',
                role:'',
                photo:'',
            },
            defaultItem: {
                name: '',
                email:'',
                id:'',
                photo:'',
                role:'',


            },
            emailRules: [
                v => !!v || ' پست الکترنیکی الزامیست! ',
                v => /.+@.+\..+/.test(v) || 'فرمت ایمیل وارد شده صحیح نیست! ',
            ],
            nameRules: [
                v => !!v || ' نام الزامی است ',
                v => (v && v.length >= 2) || 'نام باید بیش از 1 حرف باشد',
            ],
        }),
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'کاربر جدید' : 'ویرایش کاربر '
            },
        },
        watch: {
            dialog (val) {
                val || this.close()
            },
        },
        created () {
            this.initialize()
        },
        methods: {
            updateRole(item){
                const index = this.desserts.data.indexOf(item);
                    axios.post('/api/user/updateRole',{
                        'role':item.role,'id':item.id,
                    }).then(res => {
                        this.snackbarColor='success';
                        this.snackbarText =res.data.msg;
                        this.snackbar=true;

                    }).catch(err => {
                        this.desserts.data[index].role=err.response.data.user.role
                        this.snackbarText ='سمت تغیر نکرد'
                        this.snackbarColor='error';
                        this.snackbar=true;
                    });

            },
            checkValidMail()
            {
                let mail=this.editedItem.email;
                axios.post('/api/user/verifyEmail',{
                    'email':mail
                }).then(res=>{
                    this.validEmailMsg=res.data.msg;
                    this.invalidEmailMsg='';
                }).catch(err=>{
                        this.validEmailMsg='';
                        this.invalidEmailMsg='این ایمیل قبلا توسط کاربر دیگری ثبت شده است ';
                });
            },
            selectAll(e){
                this.selected=[];
                if(e.length>0){
                    this.selected=e.map(val => val.id);
                }
            },
            deleteAll(){
                let decide=confirm(' آیا برای حذف این آیتم  ها اطمینان دارید؟')
                if(decide) {
                    axios.post('/api/user/delete',{ 'users':this.selected} ).then(res => {
                         this.selected.map(value => {
                             let index=this.desserts.data.indexOf(value)
                             this.desserts.data.splice(index,1);
                         });
                        //snackbar setting
                        this.snackbarColor='success';
                        this.snackbarText ='این آیتم ها با موفقیت حذف شندند !  ';
                        this.snackbar=true;
                    }).catch(err => {
                        this.snackbarColor='error';
                        this.snackbarText =err.response.data.state;
                        this.snackbar=true;
                    });
                }

            },
            search(e){
                if(e.length >3)
                {
                    axios.get('/api/user/'+e).then(res=>{
                        this.desserts=res.data.user
                    }).catch(err=>{
                    });
                }
                else if(e.length==0){
                    axios.get('/api/user').then(res=>{
                        this.desserts=res.data.user
                    }).catch(err=>{
                    });
                }
            },
            paginate(e){
                let parameters={
                    'params':{
                        'per_page':e.itemsPerPage
                    },
                };
                axios.get('/api/user?page='+e.page,parameters).then(res=>{
                    this.desserts=res.data.user
                    this.roles=res.data.roles
                }).catch(err=>{
                    if(err.response.status==401)
                    {
                        localStorage.removeItem('token');
                        this.$router.push('/login');
                    }
                });
            },
            initialize(){
                axios.interceptors.request.use((config)=> {
                    this.loading=true
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


            },
            editItem (item) {
                this.editedIndex = this.desserts.data.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            deleteItem (item) {
                const index = this.desserts.data.indexOf(item)
                let decide=confirm(' آیا برای حذف این آیتم اطمینان دارید؟')
                if(decide) {
                    axios.delete('/api/user/' + item.id).then(res => {
                    this.desserts.data.splice(index,1)
                        this.snackbarColor='error';
                        this.snackbarText ='این آیتم با موفقیت حذف شد !  ';
                        this.snackbar=true;
                    }).catch(err => {

                    });
                }
            },
            close () {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            save () {

                if (this.editedIndex > -1) {
                    axios.put('/api/user/'+this.editedItem.id,{
                        'name': this.editedItem.name,
                        'email':this.editedItem.email,
                        'role':this.editedItem.role,
                    }).then(res=>{
                        this.snackbarColor='success';
                        this.snackbarText ='ویرایش انجام شد !';
                        this.snackbar=true;
                        Object.assign(this.desserts.data[this.editedIndex], res.data.user)
                    }).catch(err=>{

                    });
                } else {
                    axios.post('/api/user',
                        {
                            'name': this.editedItem.name,
                            'email':this.editedItem.email,
                            'role':this.editedItem.role,

                        }
                    ).then(res=>{
                        this.desserts.data.push(res.data.user);
                        this.snackbarColor='success';
                        this.snackbarText ='با موفقیت اضافه شد !';
                        this.snackbar=true;
                    }).catch(err=>{})
                }
                this.close()

            },
        },
    }
</script>
