import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import HelloWorld from '../../src/components/HelloWorld.vue'
import HomeView from '../../src/views/HomeView.vue'
import TaskListView from '../../src/views/TaskListView.vue'
import AboutView from '../../src/views/AboutView.vue'
import { createTestingPinia } from '@pinia/testing' // For mocking Pinia in tests

describe('Components Rendering', () => {
  it('HelloWorld component renders with message', () => {
    const wrapper = mount(HelloWorld, { props: { msg: 'Test Message' } })
    expect(wrapper.find('[data-test="hello-world-component"]').exists()).toBe(true)
    expect(wrapper.text()).toContain('Test Message')
  })

  it('HomeView renders main elements', () => {
    // Mount HomeView with Pinia testing plugin
    const wrapper = mount(HomeView, {
      global: {
        plugins: [createTestingPinia()],
      },
    })
    expect(wrapper.find('[data-test="home-view"]').exists()).toBe(true) // Assuming you add data-test="home-view" to HomeView's root div
    expect(wrapper.text()).toContain('歡迎來到 PetCareHub！')
    expect(wrapper.findComponent(HelloWorld).exists()).toBe(true)
  })

  it('TaskListView renders task list', () => {
    const wrapper = mount(TaskListView)
    expect(wrapper.find('[data-test="task-list-view"]').exists()).toBe(true)
    expect(wrapper.text()).toContain('我的寵物任務')
    expect(wrapper.findAll('li').length).toBeGreaterThan(0) // Should have initial tasks
  })

  it('AboutView renders content', () => {
    const wrapper = mount(AboutView)
    expect(wrapper.find('[data-test="about-view"]').exists()).toBe(true)
    expect(wrapper.text()).toContain('關於 PetCareHub')
  })
})
