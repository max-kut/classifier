<template>
    <Button native-type="button" :loading="loading"
            type="outline-secondary" @click.native="load">
        {{ $t('export') }}
    </Button>
</template>

<script>
    import axios from 'axios';
    import Button from "./ui/Button";
    export default {
        name: "ExportComponent",
        components: {Button},
        data: () => ({
            loading: false,
        }),
        computed: {},
        methods: {
            load(){
                axios({
                    url: '/phrases/download',
                    method: 'GET',
                    responseType: 'blob', // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'export.csv');
                    document.body.appendChild(link);
                    link.click();
                });
            }
        }
    }
</script>

<style lang="scss">

</style>
