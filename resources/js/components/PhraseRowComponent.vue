<template>
    <tr>
        <td>
            <input type="text" class="form-control" v-model="phrase.text"
                   :placeholder="$t('enter_your_phrase')">
        </td>
        <td>
            <b-alert show variant="success" v-if="phrase.proposed_topic">
                {{ phrase.proposed_topic }}
            </b-alert>
            <input type="text" class="form-control" v-model="phrase.topic"
                   v-else
                   :placeholder="$t('enter_your_topic')">

        </td>
        <td>
            <b-button-group v-if="phrase.proposed_topic">
                <Button native-type="button"
                        type="outline-success"
                        :loading="accepting"
                        @click.native="accept">{{ $t('accept_predicted') }}
                </Button>
                <Button native-type="button"
                        type="outline-danger"
                        :loading="rejecting"
                        @click.native="reject">{{ $t('reject_predicted') }}
                </Button>
            </b-button-group>
            <Button native-type="button" v-else-if="phrase._isDirty"
                    type="outline-secondary"
                    :loading="loading"
                    @click.native="save">{{ $t('save_phrase') }}
            </Button>
        </td>
    </tr>
</template>

<script>
    import {mapActions} from 'vuex'
    import Phrase from "~/models/Phrase";
    import Button from '~/components/ui/Button'

    export default {
        name: "PhraseRowComponent",
        components: {
            Button
        },
        props: {
            value: {
                type: Phrase,
                required: true
            }
        },
        data: () => ({
            loading: false,
            accepting: false,
            rejecting: false,
            phrase: null
        }),
        computed: {},
        watch: {
            value: {
                handler() {
                    this.phrase = this.value.clone()
                },
                deep: true
            }
        },
        created() {
            this.phrase = this.value.clone()
        },
        methods: {
            async save() {
                this.loading = true;
                await this.savePhrase(this.phrase);
                this.loading = false;
            },
            async accept() {
                this.accepting = true;
                await this.acceptPredicted(this.phrase);
                this.accepting = false;
            },
            async reject() {
                this.rejecting = true;
                await this.rejectPredicted(this.phrase);
                this.rejecting = false;
            },
            ...mapActions('phrases', ['savePhrase', 'acceptPredicted', 'rejectPredicted'])
        }
    }
</script>

<style lang="scss">

</style>
