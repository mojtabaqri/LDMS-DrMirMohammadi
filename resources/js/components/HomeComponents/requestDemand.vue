<template>
    <v-container>
        <div class="requestDemand">
            <v-row class="align">
                <v-col cols="8">

                    <v-file-input
                        v-model="files"
                        color="deep-purple accent-4"
                        counter
                        label="File input"
                        multiple
                        placeholder="Select your files"
                        prepend-icon="mdi-paperclip"
                        outlined
                        :show-size="1000"
                    >
                        <template v-slot:selection="{ index, text }">
                            <v-chip
                                v-if="index < 2"
                                color="deep-purple accent-4"
                                dark
                                label
                                small
                            >
                                {{ text }}
                            </v-chip>

                            <span
                                v-else-if="index === 2"
                                class="overline grey--text text--darken-3 mx-2"
                            >
        +{{ files.length - 2 }} File(s)
      </span>
                        </template>
                    </v-file-input>
                </v-col>

                <v-col cols="4">
                    <v-form
                        ref="form"
                    >
                        <v-text-field
                            v-model="name"
                            label="نام شما"
                        ></v-text-field>

                        <v-text-field
                            label="شماره همراه "
                            required
                        ></v-text-field>
                        <v-text-field
                            label=" عنوان مطالبه "
                            required
                        ></v-text-field>
                        <v-textarea
                            clearable
                            label="متن مطالبه"
                        ></v-textarea>
                        <v-btn
                            class="mr-4 b"
                        >
                            ارسال مطالبه
                        </v-btn>

                    </v-form>
                </v-col>
            </v-row>



        </div>
    </v-container>

</template>

<script>
    export default {
        name: "requestDemand",
        mounted() {
        }
    }
</script>

<style scoped>
.align{
    text-align:right;
}
</style>
