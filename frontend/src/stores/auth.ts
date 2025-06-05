// src/stores/auth.ts
import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
  role: 'Admin' | 'Project Manager' | 'Team Member' // sesuai role backend kamu
}

export const useAuth = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    token: localStorage.getItem('token') || '',
  }),
  actions: {
    setUser(user: User) {
      this.user = user
    },
    setToken(token: string) {
      this.token = token
      localStorage.setItem('token', token)
    },
    logout() {
      this.user = null
      this.token = ''
      localStorage.removeItem('token')
    },
  },
})