<template>
  <div class="home text-center">
    <h1 class="text-4xl font-bold text-blue-700 mb-4">歡迎來到 PetCareHub！</h1>
    <p class="text-lg text-gray-700 mb-6">您的終極寵物照護任務管理平台。</p>
    <img src="/placeholder.svg" alt="PetCare Illustration" class="mx-auto w-1/2 rounded-lg shadow-lg mb-8" />
    <HelloWorld msg="立即開始管理您的寵物任務！" />
  </div>
</template>

<script setup>
import HelloWorld from '../components/HelloWorld.vue'
// 可以使用 Pinia store
import { useAppStore } from '../stores/appStore.js'
const appStore = useAppStore()
// console.log(appStore.currentMessage) // 'Hello from Pinia Store!'
</script>

<style scoped>
/* Specific styles for HomeView */
</style>
