import { createStore } from 'vuex'
import Theme from './modules/Theme'
import Auth from './modules/Auth'
import User from './modules/User'
import Admin from './modules/Admin'
import AdminUser from './modules/AdminUser'
import Card from './modules/Card'
import CardRequest from './modules/CardRequest'

export default createStore({
  modules: {
    Theme,
    Auth,
    User,
    Admin,
    AdminUser,
    Card,
    CardRequest,
  }
})
