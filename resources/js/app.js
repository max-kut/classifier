import Vue from "vue";
import i18n from '~/plugins/i18n'
import store from '~/store'
import '~/plugins'
import { strStudly } from "~/utils";

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

Vue.config.productionTip = false;

// init vue declarations
document.querySelectorAll('.vue').forEach(el => {
    const props = typeof el.dataset.props !== 'undefined' ? JSON.parse(el.dataset.props) : {};

    const studlyTagName = strStudly(el.tagName);

    const component = () => import('./components/' + studlyTagName);

    const APP = (new Vue({
        i18n,
        store,
        render(h) {
            return h(component, {props})
        }
    }));

    APP.$mount(el);
});

const {user_id} = window.CONFIG;

if (user_id) {
    window.Echo.private(`user.${user_id}`)
        .listen('.calculating.completed', ({user}) => {
            store.dispatch('calculate/setStatus', user.calculating_status);
        });
}

