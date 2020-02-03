import {
    SET_PHRASES,
    SET_PER_PAGE,
    SET_CURRENT_PAGE,
    SET_INFO,
    SET_PHRASES_LOADING, SET_PHRASE
} from "../mutation-types";
import axios from 'axios'
import Phrase from "~/models/Phrase";

// state
export const state = {
    loading: false,
    page: 1,
    perPage: 20,
    info: {
        total: null,
        last_page: 1,
    },
    items: [],
};

// getters
export const getters = {
    loading: state => state.loading,
    page: state => state.page,
    perPage: state => state.perPage,
    items: state => state.items,
    info_total: state => state.info.total
};

// mutations
export const mutations = {
    [SET_PHRASES](state, items) {
        state.items = items.slice().map(item => new Phrase(item))
    },
    [SET_PHRASE](state, phrase) {
        for (let i = 0; i < state.items.length; i++) {
            if (state.items[i].id === phrase.id) {
                state.items.splice(i, 1, new Phrase(phrase));
                break;
            }
        }
    },
    [SET_PHRASES_LOADING](state, loading) {
        state.loading = loading
    },
    [SET_PER_PAGE](state, perPage) {
        state.perPage = perPage
    },
    [SET_CURRENT_PAGE](state, page) {
        state.page = page
    },
    [SET_INFO](state, data) {
        state.info = Object.assign({}, data);
    },
};

// actions
export const actions = {
    /**
     * loading phrases
     * @param state
     * @param commit
     * @param page
     * @param data
     */
    async loadPhrases({state, commit}, page = null) {
        if (page !== null) {
            commit(SET_CURRENT_PAGE, page);
        }
        commit(SET_PHRASES_LOADING, true);

        let {data} = await axios.get('/phrases', {
            params: {
                page: state.page,
                per_page: state.perPage
            }
        });

        commit(SET_PHRASES, data.data);

        commit(SET_INFO, {
            total: data.total,
            last_page: data.last_page,
        });

        commit(SET_PHRASES_LOADING, false);
    },

    /**
     *
     * @param state
     * @param commit
     * @param phrase Phrase
     * @returns {Promise<void>}
     */
    async savePhrase({state, commit}, phrase) {
        let {data} = await axios.put(`/phrases/${phrase.id}`, phrase.toRequestObject());
        commit(SET_PHRASE, data.phrase);
    },

    async acceptPredicted({state, commit}, phrase) {
        let {data} = await axios.put(`/phrases/${phrase.id}/accept`, phrase.toRequestObject());
        commit(SET_PHRASE, data.phrase);
    },

    async rejectPredicted({state, commit}, phrase) {
        let {data} = await axios.put(`/phrases/${phrase.id}/reject`, phrase.toRequestObject());
        commit(SET_PHRASE, data.phrase);
    },
};
