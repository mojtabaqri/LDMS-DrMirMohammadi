<template>
        <v-list

        >
            <v-subheader> پیوست ها </v-subheader>
            <v-list-item-group color="primary">
                <v-list-item
                    v-for="(item, i) in file_directory"
                    :key="i"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-cloud-download-outline</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title> <router-link :to="{ name: item.address }"  target= '_blank'>{{item.ext}}</router-link>
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
        }
    }
</script>

<style scoped>

</style>
