<template>
    <v-carousel>
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
                        'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg',
                        'https://cdn.vuetifyjs.com/images/carousel/sky.jpg',
                        'https://cdn.vuetifyjs.com/images/carousel/bird.jpg',
                        'https://cdn.vuetifyjs.com/images/carousel/planet.jpg',

                ],
            }
        },
    }
</script>

<style scoped>

</style>
