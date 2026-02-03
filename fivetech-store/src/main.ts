import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { pinia } from './stores'  // giả sử bạn có file stores/index.ts export pinia

// Import Tailwind CSS (hoặc file style chính)
import './style.css'
// Tạo app
const app = createApp(App)

// Sử dụng router và Pinia
app.use(router)
app.use(pinia)

// Mount vào #app
app.mount('#app')

// Optional: Global error handler (hữu ích khi phát triển)
app.config.errorHandler = (err, instance, info) => {
  console.error('Global error:', err, info)
}