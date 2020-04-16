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
        pageText:'مطالبه در این صفحه ',
        'show-current-page':true,
        'show-first-last-page':true,



    }"
        sort-by="calories"
        class="elevation-1"
        :loading="loading" loading-text="اندکی صبر کنید ...."    >
        <template v-slot:top>
            <v-toolbar flat >
                <v-toolbar-title>مدیریت مطالبات  کاربران </v-toolbar-title>
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

                <v-dialog v-model="dialog" max-width="800px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="error" dark class="mb-2 mx-2" v-on:click="deleteAll"> حذف مطالبات</v-btn>
                    </template>

                    <v-card>
                        <v-card-title>
                            <span class="title">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                    <v-form>
                                        <v-row>
                                            <v-col cols="8" class="offset-4">
                                                <v-text-field
                                                    label="عنوان مطالبه"
                                                    readonly
                                                    v-model="editedItem.title"
                                                    ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" class=" p-3 mt-2 ">
                                                <v-textarea
                                                    name="input-7-1"
                                                    filled
                                                    label="متن مطالبه"
                                                    readonly
                                                    v-model="editedItem.content"
                                                ></v-textarea>
                                            </v-col>
                                            <v-col cols="12" class=" p-3 mt-2">
                                             <attach-file-component :file_directory=filesObject></attach-file-component>
                                            </v-col>
                                            <v-col cols="12" class=" p-3 mt-2">
                                                <v-textarea
                                                    name="input-7-1"
                                                    filled
                                                    label="پاسخ به مطالبه"
                                                    v-model="editedItem.reply"
                                                ></v-textarea>
                                            </v-col>

                                        </v-row>

                                    </v-form>




                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">انصراف</v-btn>
                            <v-btn color="blue darken-1" text @click="save">ذخیره</v-btn>
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
        <template v-slot:item.userprofile="{ item }">
                <v-list-item-avatar>
                    <v-img
                        :src="item.userprofile"
                        :lazy-src="item.userprofile"
                        aspect-ratio="1"
                        class="grey lighten-2"
                        max-width="50"
                        max-height="50"
                    >
                    </v-img>
                </v-list-item-avatar>
        </template>
        <template v-slot:item.action="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="singleItem(item)"
            >
                mdi-file-document-box-search           </v-icon>
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
    import attachFileComponent from "../customComponent/attachFileComponent";
    export default {
        components:{
          'attachFileComponent':attachFileComponent
        },
        data: () => ({
            filesObject:null,
            snackbarText:'',
            selected:[],
            snackbarColor:'',
            snackbar:false,
            loading:false,
            dialog: false,
            headers: [
                {
                    text: ' سریال',
                    align: 'start',
                    sortable: false,
                    value: 'id',
                },
                { text: 'عنوان', value: 'title' },
                { text: 'متن مطالبه', value: 'content' },
                { text: 'متن پاسخ', value: 'reply' },
                { text: 'نام کاربر', value: 'username' },
                { text: 'تصویر کاربر', value: 'userprofile' },
                { text: 'شماره همراه کاربر', value: 'userphone' },
                { text: 'عملیات', value: 'action', sortable: false },
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {
                id:'',
                title: '',
                content:'',
                reply:'',
                username:'',
                userphone:'',
                userprofile: null,
            },
            defaultItem: {
                id:'',
                title: '',
                content:'',
                reply:'',
                username:'',
                userphone:'',
                userprofile: null,

            },
        }),
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? ' حذف مطالبه' : 'مشاهده مطالبه:  '+this.editedItem.title
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
            selectAll(e){
                this.selected=[];
                if(e.length>0){
                    this.selected=e.map(val => val.id);
                }
            },
            deleteAll(){
                let decide=confirm(' آیا برای حذف این آیتم  ها اطمینان دارید؟')
                if(decide) {
                    axios.post('/api/demand/delete',{ 'demands':this.selected} ).then(res => {
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
                    axios.get('/api/demand/'+e).then(res=>{
                        this.desserts=res.data.demand
                    }).catch(err=>{
                    });
                }
                else if(e.length==0){
                    axios.get('/api/demand').then(res=>{
                        this.desserts=res.data.demand
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
                axios.get('/api/demand?page='+e.page,parameters).then(res=>{
                    this.desserts=res.data.demand
                }).catch(err=>{
                    if(err.response.status==401)
                    {
                        axios.post('/api/logout').then(res=>{
                            localStorage.removeItem('token');
                            this.$router.push('/login');
                        }).catch(err=>{
                        });
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
            singleItem (item) {
                this.editedIndex = this.desserts.data.indexOf(item)
                this.editedItem = Object.assign({}, item)
                axios.get('/api/demand/singleDemand/'+item.id,).then(res=>{
                     this.filesObject = res.data.data.attachment;
                    this.dialog = true

                }).catch(err=>{
                });
            },
            deleteItem (item) {
                const index = this.desserts.data.indexOf(item)
                let decide=confirm(' آیا برای حذف این آیتم اطمینان دارید؟')
                if(decide) {
                    axios.delete('/api/demand/' + item.id).then(res => {
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
                    let index=this.editedIndex;
                    axios.put('/api/demand/'+this.editedItem.id,{
                        'reply': this.editedItem.reply,
                    }).then(res=>{
                        this.snackbarColor='success';
                        this.snackbarText ='پاسخ داده شد   !';
                        this.snackbar=true;
                        Object.assign(this.desserts.data[index],res.data.demand)
                    }).catch(err=>{
                    });
                }
                this.close()
            },
        },
    }
</script>
