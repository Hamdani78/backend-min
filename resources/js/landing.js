import { createApp } from 'vue'
import { createPinia } from 'pinia'

// 
import App from './Pages/Landing/App.vue'
import router from './Pages/Landing/router/index.js'

// 
import './Pages/Landing/assets/css/nucleo-icons.css'
import './Pages/Landing/assets/css/nucleo-svg.css'
import materialKit from './Pages/Landing/material-kit.js'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(materialKit)
app.mount('#landing-app')
