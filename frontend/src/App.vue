<template>
  <div id="app" class="font-sans antialiased text-gray-800">
    <header class="bg-blue-600 text-white p-4 shadow-md">
      <nav class="container mx-auto flex justify-between items-center">
        <router-link to="/" class="text-2xl font-bold">PetCareHub</router-link>
        <div class="space-x-4">
          <router-link to="/about" class="hover:underline">關於</router-link>
          <router-link to="/tasks" class="hover:underline">任務</router-link>
          <!-- <router-link to="/login" class="hover:underline">登入</router-link> -->
        </div>
      </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
      <router-view />
    </main>
    <footer class="bg-gray-100 p-4 mt-8 text-center text-gray-600 text-sm">
      © 2023 PetCareHub. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
</script>

<style>
/* You might add basic global styles or Tailwind directives here */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';
</style>
