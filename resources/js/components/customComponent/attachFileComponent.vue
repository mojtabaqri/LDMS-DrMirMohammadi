<template>
        <v-list

        >
            <v-subheader> پیوست ها </v-subheader>
            <v-list-item-group color="primary">
                <v-list-item
                    v-for="(item, i) in file_directory"
                    :key="i"
                    @click="downloadItem(item)"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-cloud-download-outline</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{item.slice(item.lastIndexOf('/')+1)}}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>

</template>

<script>
    export default {
        name: "attachFileComponent",
        props:{
            'file_directory':Array,
        },
        methods:{
            downloadItem(item){
              axios.post('/api/downloadFile',{'file':item}).then(res=>{
                  console.log(res)
                  let blob = new Blob([res.data], { type: res.headers['content-type'] });
                  let link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download =item.slice(item.lastIndexOf('/')+1);
                  link.click()
              }).catch(err=>{

              })
            },
        }
    }
</script>

<style scoped>

</style>
