import { describe, it, expect, beforeEach } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAppStore } from '../../src/stores/appStore'

describe('App Store', () => {
  beforeEach(() => {
    // 每次測試前重置 Pinia 狀態
    setActivePinia(createPinia())
  })

  it('initial state is correct', () => {
    const appStore = useAppStore()
    expect(appStore.message).toBe('Hello from Pinia Store!')
    expect(appStore.isLoggedIn).toBe(false)
    expect(appStore.user).toBe(null)
  })

  it('setMessage action updates message', () => {
    const appStore = useAppStore()
    appStore.setMessage('New message!')
    expect(appStore.message).toBe('New message!')
  })

  it('login action updates isLoggedIn and user', () => {
    const appStore = useAppStore()
    const userData = { id: 1, name: 'Test User' }
    appStore.login(userData)
    expect(appStore.isLoggedIn).toBe(true)
    expect(appStore.user).toEqual(userData)
  })

  it('logout action resets isLoggedIn and user', () => {
    const appStore = useAppStore()
    appStore.login({ id: 1, name: 'Test User' }) // 先登入
    appStore.logout()
    expect(appStore.isLoggedIn).toBe(false)
    expect(appStore.user).toBe(null)
  })

  it('isAuthenticated getter reflects login status', () => {
    const appStore = useAppStore()
    expect(appStore.isAuthenticated).toBe(false)
    appStore.login({ id: 1, name: 'Test User' })
    expect(appStore.isAuthenticated).toBe(true)
  })
})
