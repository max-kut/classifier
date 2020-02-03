import Vue from "vue";
import axios from 'axios'
import Swal from 'sweetalert2'
import i18n from '~/plugins/i18n'

Vue.prototype.$axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const CSRF_TOKEN = document.head.querySelector('meta[name="csrf-token"]');
if (CSRF_TOKEN) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = CSRF_TOKEN.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Request interceptor
axios.interceptors.request.use(request => {
    // request.headers['X-Socket-Id'] = Echo.socketId()

    return request
});

// Response interceptor
axios.interceptors.response.use(response => response, error => {
    const {status, data} = error.response;

    if(status === 423){
        Swal.fire({
            type: 'error',
            title: data.message,
            reverseButtons: true,
            confirmButtonText: i18n.t('ok'),
            cancelButtonText: i18n.t('cancel')
        })
    }

    if (status >= 500) {
        Swal.fire({
            type: 'error',
            title: i18n.t('error_alert_title'),
            text: i18n.t('error_alert_text'),
            reverseButtons: true,
            confirmButtonText: i18n.t('ok'),
            cancelButtonText: i18n.t('cancel')
        })
    }

    return Promise.reject(error)
});
