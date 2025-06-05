import { createRouter, createWebHistory } from 'vue-router'
import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router'

import MainLayout from '@/layouts/MainLayout.vue'
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'
import Projects from '@/pages/Projects.vue'
import ProjectCreate from '@/components/ProjectCreate.vue'
import ProjectEdit from '@/components/ProjectEdit.vue'
import TaskList from '@/pages/TaskList.vue'
import TaskCreate from '@/components/TaskCreate.vue'
import TaskEdit from '@/components/TaskEdit.vue'
import TaskComments from '@/pages/TaskComments.vue'
import Profile from '@/pages/Profile.vue'
import EditProfile from '@/components/EditProfile.vue'
import EditPassword from '@/components/EditPassword.vue'
import UserManagement from '@/pages/UserManagement.vue'
import KanbanBoard from '@/components/KanbanBoard.vue'

// Ambil role user dari localStorage
function getUserRole(): string | null {
  const userJson = localStorage.getItem('user')
  if (!userJson) return null

  try {
    const user = JSON.parse(userJson)
    return user.role || null
  } catch {
    return null
  }
}

// Fungsi cek token
function isLoggedIn(): boolean {
  return !!localStorage.getItem('token')
}

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login, meta: { guestOnly: true } },
  { path: '/register', component: Register, meta: { guestOnly: true } },

  // 🧩 Parent route dengan MainLayout
  {
    path: '/',
    component: MainLayout,
    meta: { requiresAuth: true }, // semua child di sini butuh login
    children: [
      { path: 'dashboard', component: Dashboard },
      { path: 'projects', component: Projects },
      { path: 'projects/create', component: ProjectCreate },
      { path: 'projects/:id/edit', component: ProjectEdit },
      { path: 'projects/:id/tasks', component: TaskList, name: 'task-list' },
      { path: 'projects/:id/tasks/create', component: TaskCreate, name: 'task-create' },
      { path: 'tasks/:id/edit', component: TaskEdit, name: 'TaskEdit' },
      { path: 'tasks/:id/comments', component: TaskComments, name: 'TaskComments' },
      { path: 'profile', component: Profile, name: 'Profile' },
      { path: 'edit-profile', component: EditProfile, name: 'EditProfile' },
      { path: 'edit-password', component: EditPassword, name: 'EditPassword' },
      {path: 'projects/:id/kanban', component: KanbanBoard, name: 'KanbanBoard'},
      {
        path: 'users',
        component: UserManagement,
        beforeEnter: (
          _to: RouteLocationNormalized,
          _from: RouteLocationNormalized,
          next: NavigationGuardNext
        ) => {
          const role = getUserRole()
          if (role === 'Admin') {
            next()
          } else {
            alert('Hanya Admin yang dapat mengakses halaman ini')
            next('/dashboard')
          }
        }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Global navigation guard
router.beforeEach(
  (to: RouteLocationNormalized, _from: RouteLocationNormalized, next: NavigationGuardNext) => {
    const token = localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
      // Belum login tapi mau akses halaman login required
      return next('/login')
    }

    if (to.meta.guestOnly && token) {
      // Sudah login tapi coba buka login/register
      return next('/dashboard')
    }

    next()
  }
)

export default router