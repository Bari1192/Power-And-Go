import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { router } from '@/router/index.js'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { plugin, defaultConfig } from '@formkit/vue'
import config from '../formkit.config.js'
// cloudhoz
import VueGoogleMaps from '@fawmi/vue-google-maps'; 

import App from '@/App.vue'

import '@assets/app.scss'

const app = createApp(App)

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

app.use(router)
app.use(plugin, defaultConfig(config))
app.use(pinia)

// A GoogleCloud import
app.use(VueGoogleMaps, {
    load: {
        key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY, // A helyes env változó
        libraries: 'places', // A szükséges könyvtárak betöltése
    },
});

app.mount('#app')
