<template>
  <div class="bg-blue-100 p-6 rounded-lg shadow-md max-w-lg mx-auto" data-test="hello-world-component">
    <p class="text-xl font-semibold text-blue-800">{{ msg }}</p>
    <button class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
      開始使用
    </button>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
const props = defineProps({
  msg: String
})
</script>

<style scoped>
/* Add component specific styles here if needed */
</style>
