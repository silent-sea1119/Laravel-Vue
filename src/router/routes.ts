import {RouteRecordRaw} from 'vue-router'
import userRoutes from './routes/user'
//import siteRoutes from './routes/site'
import adminRoutes from './routes/admin'

const routes: RouteRecordRaw[] = [
  //...siteRoutes,
  ...userRoutes,
  ...adminRoutes,

  {
    path: '/:pathMatch(.*)*',
    name: "404",
    component: () => import('../views/site/404View.vue'),
    meta: {
      title: "404 Not Found"
    }
  },
];

export default routes;
