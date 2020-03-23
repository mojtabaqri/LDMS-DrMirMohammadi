<template>

    <v-carousel height="295" >
        <v-carousel-item
            v-for="(item,i) in items"
            :key="i"
            :src="item"
            reverse-transition="fade-transition"
            transition="fade-transition"
        ></v-carousel-item>
    </v-carousel>
</template>

<script>
    export default {
        name: "HeaderComponent",
        data () {
            return {
                items: [
                        'http://drmirmohamadi.com/wp-content/uploads/2020/03/MG-0214-copy-1.jpg',
                        'http://drmirmohamadi.com/wp-content/uploads/2020/03/%D8%A7%D8%B5%D9%84.jpg',
                        'http://drmirmohamadi.com/wp-content/uploads/2020/02/IMG_4834-450x320.jpg',
                        'http://drmirmohamadi.com/wp-content/uploads/2020/02/DSC0022-copy.jpg',

                ],
            }
        },
    }
</script>

<style scoped>

</style>
