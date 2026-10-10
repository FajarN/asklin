import _ from 'lodash';
window._ = _;

import 'bootstrap';

// Configure Bootstrap 5 Modal defaults globally to static backdrop
if (typeof window !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function () {
        if (window.bootstrap && window.bootstrap.Modal && window.bootstrap.Modal.Default) {
            window.bootstrap.Modal.Default.backdrop = 'static';
            window.bootstrap.Modal.Default.keyboard = false;
        }

        // Global dismiss bridge for BS4 & BS5 compatibility
        document.addEventListener('click', function (e) {
            const dismissBtn = e.target.closest('[data-dismiss="modal"], [data-bs-dismiss="modal"], .modal .close, .modal .btn-close');
            if (dismissBtn) {
                const modalEl = dismissBtn.closest('.modal');
                if (modalEl) {
                    if (window.bootstrap && window.bootstrap.Modal) {
                        try {
                            const instance = window.bootstrap.Modal.getOrCreateInstance(modalEl, {
                                backdrop: 'static',
                                keyboard: false
                            });
                            if (instance) instance.hide();
                        } catch (_) {}
                    }
                    if (window.$ && typeof window.$.fn.modal === 'function') {
                        try { window.$(modalEl).modal('hide'); } catch (_) {}
                    }
                    setTimeout(() => {
                        if (!modalEl.classList.contains('show')) {
                            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                            document.body.classList.remove('modal-open');
                            document.body.style.removeProperty('padding-right');
                        }
                    }, 200);
                }
            }
        });
    });
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
