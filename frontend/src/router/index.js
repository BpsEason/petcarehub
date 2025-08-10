import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    component: () => import('../views/AboutView.vue')
  },
  {
    path: '/tasks',
    name: 'tasks',
    component: () => import('../views/TaskListView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Basic route guard example
router.beforeEach((to, from, next) => {
  // const appStore = useAppStore(); // You would get the store here
  // if (to.meta.requiresAuth && !appStore.isLoggedIn) {
  //   next('/login');
  // } else {
  //   next();
  // }
  next(); // Allow all for now
})

export default router
