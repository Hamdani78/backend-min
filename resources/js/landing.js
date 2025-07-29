import { createApp } from 'vue'
import { createPinia } from 'pinia'


// 
import App from './Pages/Landing/App.vue'
import router from './Pages/Landing/router/index.js'

// 
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './Pages/Landing/assets/css/nucleo-icons.css'
import './Pages/Landing/assets/css/nucleo-svg.css'
import materialKit from './Pages/Landing/material-kit.js'
import '@fortawesome/fontawesome-free/css/all.min.css'


const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(materialKit)
app.mount('#landing-app')
