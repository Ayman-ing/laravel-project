import Aura from '@primevue/themes/aura';
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },

  // Add Webpack polling for better hot-reloading in Docker
  vite: {
    server: {
      watch: {
        usePolling: true,
        interval: 300, // This sets the polling interval
      },
    },
  },

  modules: ['@nuxtjs/tailwindcss', '@primevue/nuxt-module'],
  css: [
    'primeicons/primeicons.css', // PrimeIcons CSS for icons

  ],
  primevue: {
    options: {
      
        theme: {
            preset: Aura,
            options : {
              darkModeSelector: '.my-app-dark',
            }
        },
        
        
        
      
    }
    
}
 

})