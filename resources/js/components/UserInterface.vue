<template>
    <div class="user-interface">
        <div class="row justify-content-end">
            <div class="col-auto">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <ImportComponent />
                    <ExportComponent />
                    <CalculateComponent />
                </div>
            </div>
        </div>
        <table class="table table-sm">
            <thead>
            <tr>
                <th style="min-width: 60%;">Фраза</th>
                <th>Класс</th>
                <th style="width: 150px">#</th>
            </tr>
            </thead>
            <tbody>
            <PhraseRowComponent v-for="phrase in phrases"
                                :key="phrase.id"
                                :value="phrase"/>
            </tbody>
            <tfoot>
            <tr>
                <td>
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="total"
                        :per-page="perPage"
                    />
                </td>
                <td>
                    <div class="d-flex justify-content-between align-items-center">
                        <span v-if="total !== null">Всего: <strong>{{ total }}</strong></span>
                        <b-spinner variant="primary" label="Spinning" v-if="phrasesLoading"/>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'
    import PhraseRowComponent from "~/components/PhraseRowComponent";
    import Button from "./ui/Button";
    import ImportComponent from "~/components/ImportComponent";
    import ExportComponent from "~/components/ExportComponent";
    import CalculateComponent from "~/components/CalculateComponent";
    import {SET_PER_PAGE} from "~/store/mutation-types";

    export default {
        name: "UserInterface",
        components: {
            Button,
            PhraseRowComponent,
            ImportComponent,
            ExportComponent,
            CalculateComponent
        },
        data: () => ({

        }),
        computed: {
            currentPage:{
                get(){
                    return this.$store.getters['phrases/page'];
                },
                set(val){
                    this.loadPhrases(val);
                }
            },
            perPage:{
                get(){
                    return this.$store.getters['phrases/perPage'];
                },
                set(val){
                    this.$store.commit(`phrases/${SET_PER_PAGE}`, val);
                }
            },
            ...mapGetters('phrases', {
                phrases: 'items',
                phrasesLoading: 'loading',
                total: 'info_total'
            })
        },
        created() {
            this.loadPhrases();
        },
        methods: {
            ...mapActions('phrases', ['loadPhrases'])
        }
    }
</script>
