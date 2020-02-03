import axios from 'axios';
import Swal from 'sweetalert2';
import i18n from '~/plugins/i18n';

// state
import {
    SET_CALCULATING_STATUS
} from "~/store/mutation-types";

import {
    PENDING,
    HAS_PREDICTED
} from '~/store/calc-statuses';

const {calculating_status} = window.CONFIG;

export const state = {
    status: calculating_status,
};

// getters
export const getters = {
    isPending: state => state.status === PENDING,
    hasPredicted: state => state.status === HAS_PREDICTED
};

// mutations
export const mutations = {
    [SET_CALCULATING_STATUS](state, status){
        state.status = status;
    }
};

// actions
export const actions = {
    async startCalculating({state, commit}){
        try {
            let {data} = await axios.post('/phrases/calculate');
            commit(SET_CALCULATING_STATUS, PENDING);
        } catch (e) {

        }
    },

    setStatus({commit, dispatch}, status){

        commit(SET_CALCULATING_STATUS, status);

        if([PENDING,HAS_PREDICTED].includes(status)){
            Swal.fire({
                type: 'success',
                title: status === PENDING
                    ? i18n.t('calculate_message_pending')
                    : i18n.t('calculate_message_has_predicted'),
                reverseButtons: true,
                confirmButtonText: i18n.t('ok'),
                cancelButtonText: i18n.t('cancel')
            });

            if(HAS_PREDICTED === status){
                dispatch('phrases/loadPhrases', 1, {root: true});
            }
        }
    }
};
