import './bootstrap';
import {useToDoStore} from "../vue/store.js";
import { createApp } from 'vue'
import { createPinia } from 'pinia'
const pinia = createPinia()
import App from './../vue/App.vue'


const app = createApp(App);
app.use(pinia);


const todoStore = useToDoStore();
app.mount("#app");


