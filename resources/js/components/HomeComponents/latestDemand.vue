<template>
    <div class="lastest__demand">

        <v-row>
            <v-col>
                <div class="itemHodler">
                    <div class="item">


                        <div class="text-center">
                            <v-pagination
                                v-model="page"
                                :length="5"
                                :total-visible="5"
                            ></v-pagination>
                        </div>
                    </div>

                </div>
            </v-col>
        </v-row>    </div>
</template>

<script>
    export default {
        name: "latestDemand"
    }
</script>

<style scoped>

</style>
