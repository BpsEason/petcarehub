import { describe, it, expect, beforeEach } from 'vitest'
import { createRouter, createWebHistory } from 'vue-router'
import { mount } from '@vue/test-utils'
import App from '../../src/App.vue'
import HomeView from '../../src/views/HomeView.vue'
import AboutView from '../../src/views/AboutView.vue'
import TaskListView from '../../src/views/TaskListView.vue'
import { createTestingPinia } from '@pinia/testing' // For mocking Pinia in router tests

const routes = [
  { path: '/', component: HomeView },
  { path: '/about', component: AboutView },
  { path: '/tasks', component: TaskListView },
]

describe('Router', () => {
  let router;

  beforeEach(() => {
    router = createRouter({
      history: createWebHistory(),
      routes,
    })
  })

  it('navigates to HomeView on /', async () => {
    router.push('/')
    await router.isReady()
    const wrapper = mount(App, {
      global: {
        plugins: [router, createTestingPinia()],
      },
    })
    expect(wrapper.findComponent(HomeView).exists()).toBe(true)
  })

  it('navigates to AboutView on /about', async () => {
    router.push('/about')
    await router.isReady()
    const wrapper = mount(App, {
      global: {
        plugins: [router, createTestingPinia()],
      },
    })
    expect(wrapper.findComponent(AboutView).exists()).toBe(true)
  })

  it('navigates to TaskListView on /tasks', async () => {
    router.push('/tasks')
    await router.isReady()
    const wrapper = mount(App, {
      global: {
        plugins: [router, createTestingPinia()],
      },
    })
    expect(wrapper.findComponent(TaskListView).exists()).toBe(true)
  })

  it('clicking a router-link navigates correctly', async () => {
    const wrapper = mount(App, {
      global: {
        plugins: [router, createTestingPinia()],
      },
    })
    await router.isReady() // Ensure router is ready before clicking

    // Simulate click on "關於" link
    await wrapper.find('a[href="/about"]').trigger('click')
    await wrapper.vm.() // Wait for Vue to update DOM
    expect(wrapper.findComponent(AboutView).exists()).toBe(true)

    // Simulate click on "任務" link
    await wrapper.find('a[href="/tasks"]').trigger('click')
    await wrapper.vm.()
    expect(wrapper.findComponent(TaskListView).exists()).toBe(true)
  })
})
