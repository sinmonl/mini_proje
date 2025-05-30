// vue.config.js
const { defineConfig } = require('@vue/cli-service')

module.exports = defineConfig({
  transpileDependencies: true,

  devServer: {
    port: 8080,
    host: 'localhost',
    proxy: {
      '^/api': {
        target: 'http://localhost:8001',
        changeOrigin: true,
        logLevel: 'debug',
        secure: false,
        ws: true
      }
    }
  }
})
