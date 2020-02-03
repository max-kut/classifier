<template>
    <Button native-type="button" :loading="loading"
            type="outline-secondary" @click.native="$refs.file.click()">
        <span class="import-progress" v-if="loading"
              :style="{width:`${progress}%`}"/>
        {{ $t('import') }}
        <input type="file" hidden ref="file" @change="handleFileUpload" accept=".csv"/>
    </Button>
</template>

<script>
    import Button from "./ui/Button";
    import Swal from 'sweetalert2'
    import i18n from "~/plugins/i18n";
    import {  mapActions } from 'vuex'

    export default {
        name: "ImportComponent",
        components: {Button},
        props: {},
        data: () => ({
            loading: false,
            progress: 0,
        }),
        computed: {},
        methods: {
            async handleFileUpload() {
                this.loading = true;

                let formData = new FormData();
                formData.append('file', this.$refs.file.files[0]);

                try {
                    let {data} = await this.$axios.post('/phrases/import', formData, {
                        onUploadProgress: (progressEvent) => {
                            this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        },
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    });
                    this.loading = false;
                    this.progress = 100;
                    this.loadPhrases(1);

                    Swal.fire({
                        type: 'success',
                        title: i18n.t('imported_success'),
                        reverseButtons: true,
                        confirmButtonText: i18n.t('ok')
                    });
                    setTimeout(()=>{
                        this.progress = 0;
                    }, 500)
                } catch (e) {
                    console.log(e);

                    Swal.fire({
                        type: 'error',
                        title: e.toString(),
                        reverseButtons: true,
                        confirmButtonText: i18n.t('ok')
                    });

                    this.loading = false;
                    this.progress = 0;
                }
            },
            ...mapActions('phrases',['loadPhrases'])
        }
    }
</script>

<style lang="scss" scoped>
    button {
        position: relative;
        overflow: hidden;
        .import-progress{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            opacity: .5;
            background-color: blue;
            transition: all .1s;
        }
    }

</style>
