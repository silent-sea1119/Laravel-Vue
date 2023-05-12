import { createApp } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import {themeSetup} from './utils/index';

// Emitter.
import mitt from 'mitt';
const emitter = mitt();

// styles
import "@fortawesome/fontawesome-free/css/all.min.css";
import './assets/css/tailwind.css'

// mavonEditor plugin.
import mavonEditor from 'mavon-editor'
import 'mavon-editor/dist/css/index.css'


const app = createApp(App);
app.config.globalProperties.emitter = emitter;
app.use(store).use(router).use(mavonEditor).mount('#app');

themeSetup(); // setup theme.
