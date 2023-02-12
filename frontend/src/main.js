import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

import './assets/main.css'


axios.defaults.baseURL = 'http://localhost:80/';
axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
