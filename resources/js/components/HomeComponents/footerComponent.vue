<template>
    <v-footer
        dark
        padless
    >
        <v-card
            flat
            tile
        >

            <v-card-text class="white--text pt-0 t-right ">
                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد
            </v-card-text>

            <v-divider></v-divider>

            <v-card-text class="white--text t-center">
                {{ new Date().getFullYear() }} — <strong>سامانه ی سما طراحی شده توسط : مجتبی غریب رضا یزدی </strong>
            </v-card-text>
        </v-card>
    </v-footer>
</template>

<script>
    export default {
        name: "footerComponent",
        data: () => ({

        }),
    }
</script>

<style scoped>
.t-right{
    text-align:right;
}
    .t-center{
        text-align:center;
    }
</style>
