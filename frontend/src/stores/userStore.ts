// src/stores/userStore.ts
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as any,
    token: localStorage.getItem('token') || ''
  }),
  actions: {
    setUser(user: any) {
      this.user = user
      localStorage.setItem('user', JSON.stringify(user))
    },
    setToken(token: string) {
      this.token = token
      localStorage.setItem('token', token)
    }
  }
})