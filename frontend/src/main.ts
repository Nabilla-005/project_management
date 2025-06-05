import { createApp } from 'vue'
import './style.css'
import './assets/main.css'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import 'tailwindcss/tailwind.css'

createApp(App).mount('#app')

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.mount('#app')