import '@/bootstrap.js';
import '../../resources/css/app.css';
import { createApp } from 'vue';
import MainApplication from '@/MainApplication.vue';

// PRIME VUE COMPONENTS
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
//ROUTER
import router from '@/router/index.js';

createApp(MainApplication)
  .use(router)
  .use(PrimeVue)
  .use(ToastService)
  .mount('#app');