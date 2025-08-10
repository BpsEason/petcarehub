import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0', # 讓 Docker 容器可以訪問
    port: 5173,
    watch: {
        usePolling: true, # 適用於 WSL/Docker 環境的熱重載
    },
    proxy: {
      '/api': {
        target: 'http://localhost:80', # 透過 Nginx 代理到 Laravel API
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '') # 移除 /api 前綴
      },
      '/ai': { # AI Service 代理
        target: 'http://localhost:80', # 透過 Nginx 代理到 AI Service
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/ai/, '') # 移除 /ai 前綴
      }
    }
  }
})
