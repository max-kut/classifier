const {locale} = window.CONFIG;
import {
    SET_LOCALE
} from '../mutation-types'


// state
export const state = {
    locale: locale,
}

// getters
export const getters = {
    locale: state => state.locale,
};

// mutations
export const mutations = {
    [SET_LOCALE](state, {locale}) {
        state.locale = locale
    }
}

// actions
export const actions = {
    setLocale({commit}, {locale}) {
        commit(SET_LOCALE, {locale})
    }
}
