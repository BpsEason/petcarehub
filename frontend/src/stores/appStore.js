import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    message: 'Hello from Pinia Store!',
    isLoggedIn: false,
    user: null
  }),
  actions: {
    setMessage(newMessage) {
      this.message = newMessage
    },
    login(userData) {
      this.isLoggedIn = true;
      this.user = userData;
    },
    logout() {
      this.isLoggedIn = false;
      this.user = null;
    }
  },
  getters: {
    currentMessage: (state) => state.message,
    isAuthenticated: (state) => state.isLoggedIn
  }
})
