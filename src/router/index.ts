import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes'
import store from '../store'
// Nprogress
const Nprogress = require('nprogress')
// Nprogress CSS
import 'nprogress/nprogress.css';

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  scrollBehavior (to: any, from: any, savedPosition: any) {
    if (to.hash) {
      let elem = document.getElementById(to.hash.replace("#", ""))
      if (elem)
        window.scrollTo(0, elem.offsetTop);
    } else {
      // @ts-ignore
      document.getElementById('app').scrollIntoView();
    }
  }
})

// @ts-ignore
router.beforeEach((to, from, next) => {
  Nprogress.start()
  // navigation guard to check for logged in users
  const requiresAuth: boolean = to.matched.some(x => x.meta.requiresAuth)
  // navigation gaurd to check admin premission.
  const requiresAdmin: boolean = to.matched.some(x => x.meta.requiresAdmin)

  // get the user
  const user = store.getters['Auth/user']

  // check if the from contain word admin.
  const fromAdmin: boolean = to.path.includes('admin')

  // where to redirect to.
  let redirectTo: string = '/'
  if (fromAdmin) redirectTo = '/admin/login'

  if (requiresAuth && store.getters['Auth/login'] == false) {
    next(redirectTo);
  }

  // If the current route is login, and user already logged in, redirect to home.
  else if ((to.name === 'Login' || to.name === 'adminLogin') && store.getters['Auth/login'] == true) {
    if (to.name === 'Login') next('/user/dashboard')
    next('/');
  }
  // If the login user is not admin but admin is required.
  else if (requiresAdmin && (user.user_role !== 'admin' && user.user_role !== 'superadmin')) {
    next('/user/dashboard')
  }

  // if route.path contain user but user is admin. redirect to admin dashboard.
  // if (to.path.includes('user') && (user.user_role === 'admin' || user.user_role === 'superadmin')) {
  //   next('/admin/dashboard')
  // }  
  next()
})

// @ts-ignore
router.afterEach((to, from) => {
  // Complete the animation of the route progress bar.
  Nprogress.done()
})

export default router
