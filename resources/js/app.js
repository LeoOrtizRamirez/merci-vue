import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PrimeVue from 'primevue/config';
import ToastService from "primevue/toastservice";
import pl from './Locales/pl.json';
import en from './Locales/en.json';
import { createI18n } from 'vue-i18n';

import VueGates from 'vue-gates';
import Permissions from './Plugins/Permissions';

/*Components*/
import InputSwitch from 'primevue/inputswitch';
import RadioButton from 'primevue/radiobutton';
import Button from 'primevue/button';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const i18n = createI18n({
    locale: 'pl',
    messages: {
        en: en,
        pl: pl
    }
});

const globalVariable = {
    theme: 'lara-light-indigo',
    darkTheme: false 
  };



createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {

        

        const myApp =  createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(PrimeVue)
            .use(i18n)
            .use(ToastService)
            .use(VueGates)
            .use(Permissions)
            .component('Button', Button)
            .component('InputSwitch', InputSwitch)
            .component('RadioButton', RadioButton)
            .mixin({ methods: { route } })

            //myApp.config.globalProperties.$appState = wp_wrapper;
            //myApp.config.globalProperties.$appState = h({ theme: 'lara-light-indigo', darkTheme: false })
            //myApp.prototype.$appName = 'My App'


            myApp.config.globalProperties.$appState = globalVariable
            myApp.mount(el);
            return myApp;
    },
});

InertiaProgress.init({ color: '#4B5563' });
