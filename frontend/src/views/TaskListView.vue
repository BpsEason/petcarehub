<template>
  <div class="tasks" data-test="task-list-view">
    <h1 class="text-3xl font-bold text-blue-700 mb-4">我的寵物任務</h1>
    <p class="text-md text-gray-600">這裡將顯示您的寵物照護任務列表。</p>
    <!-- 未來會從 API 載入任務 -->
    <ul class="mt-4 space-y-2">
      <li v-for="task in tasks" :key="task.id" class="bg-white p-4 rounded-md shadow flex justify-between items-center">
        <span>{{ task.title }} - <span :class="{'text-green-600': task.completed, 'text-red-600': !task.completed}">{{ task.status }}</span></span>
        <button class="ml-4 px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">完成</button>
      </li>
    </ul>
    <p v-if="tasks.length === 0" class="text-gray-500 mt-4">目前沒有任務。</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const tasks = ref([
  { id: 1, title: '遛狗 (小黃)', status: '待辦', completed: false },
  { id: 2, title: '餵食 (貓咪)', status: '已完成', completed: true },
  { id: 3, title: '清理貓砂 (貓咪)', status: '待辦', completed: false },
]);
</script>
