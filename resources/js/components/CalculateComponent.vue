<template>
    <Button native-type="button"
            :disabled="isPending"
            :loading="loading"
            type="outline-secondary"
            @click.native="send">
        <span v-if="isPending">{{ $t('calculate_pending') }}</span>
        <span v-else-if="hasPredicted">{{ $t('calculate_finished') }}</span>
        <span v-else>{{ $t('calculate') }}</span>
    </Button>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';

    import Button from "./ui/Button";

    export default {
        name: "CalculateComponent",
        components: {
            Button
        },
        data: () => ({
            loading: false,
        }),
        computed: {
            ...mapGetters('calculate', ['isPending', 'hasPredicted'])
        },
        methods: {
            async send() {
                this.loading = true;
                await this.startCalculating();
                this.loading = false;
            },
            ...mapActions('calculate', ['startCalculating'])
        }
    }
</script>

<style lang="scss">

</style>
