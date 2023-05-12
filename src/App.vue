<template>
<div>
  <main :class="theme" v-if="isLogin">
    <div class="flex bg-gray-50 dark:bg-gray-900">
      <!-- Desktop sidebar -->
      <aside
        class="
          z-20
          hidden
          w-64
          overflow-y-auto
          bg-white
          dark:bg-gray-800
          md:block
          flex-shrink-0
          shadow-right
        "
      >
        <div class="py-4 text-gray-500 dark:text-gray-400">
          <a
            class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
            href="#"
          >
            <img src="@/assets/img/logo_blue.png" alt="logo" style="width: 60%;
    height: 60%;
    margin-left: 15%;"  />
          </a>
          <ul class="mt-5">
          <template v-for="item in menuItems">
            <!-- eslint-disable vue/require-v-for-key -->
            <li
              class="relative px-6 py-3"
              v-if="item.role == user.user_role"

            >
              <router-link
                class="
                  inline-flex
                  items-center
                  w-full
                  text-sm
                  font-semibold
                  transition-colors
                  duration-150
                  hover:text-gray-800
                  dark:hover:text-gray-200
                "
                :class="item.isActive ? 'text-blue-500' : 'text-gray-500'"
                :to="item.to"
              >
                <i :class="item.icon"></i>
                <span class="ml-4">{{ item.label }}</span>
              </router-link>
            </li>
          </template>
          </ul>
        </div>
      </aside>
      <!-- Mobile sidebar -->
      <!-- Backdrop -->
      <div
        v-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="
          fixed
          inset-0
          z-10
          flex
          items-end
          bg-black bg-opacity-50
          sm:items-center sm:justify-center
        "
      ></div>
      <aside
        class="
          fixed
          inset-y-0
          z-20
          flex-shrink-0
          w-64
          mt-16
          overflow-y-auto
          bg-white
          dark:bg-gray-800
          md:hidden
        "
        v-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0 transform -translate-x-20"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform -translate-x-20"
      >
        <div class="pt-6 pb-4 text-gray-500 dark:text-gray-400">
          <a
            class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
            href="#"
          >
            <img src="@/assets/img/logo_blue.png" alt="logo" style="width: 60%;
    height: 60%;
    margin-left: 15%;"  />
          </a>
          <ul class="mt-6">
          <template v-for="item in menuItems">
            <!-- eslint-disable vue/require-v-for-key -->
            <li
              class="relative px-6 py-3"
              v-if="item.role == user.user_role"
            >
              <router-link
                class="
                  inline-flex
                  items-center
                  w-full
                  text-sm
                  font-semibold
                  transition-colors
                  duration-150
                  hover:text-gray-800
                  dark:hover:text-gray-200
                "
                :class="item.isActive ? 'text-blue-500' : 'text-gray-500'"
                :to="item.to"
              >
                <i :class="item.icon"></i>
                <span class="ml-4">{{ item.label }}</span>
              </router-link>
            </li>
          </template>
          </ul>
        </div>
      </aside>
      <div class="flex flex-col flex-1 w-full">
        <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
          <div
            class="
              container
              flex
              items-center
              justify-between
              h-full
              px-6
              mx-auto
              text-purple-600
              dark:text-purple-300
            "
          >
            <!-- Mobile hamburger -->
            <button
              class="
                p-1
                mr-5
                -ml-1
                rounded-md
                md:hidden
                focus:outline-none focus:shadow-outline-purple
              "
              aria-label="Menu"
              @click="toggleSideBar"
            >
              <svg
                class="w-6 h-6"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path
                  fill-rule="evenodd"
                  d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </button>
            <!-- Search input -->
            <div class="flex justify-center flex-1 lg:mr-32">
              <div
                class="
                  relative
                  w-full
                  max-w-xl
                  mr-6
                  focus-within:text-purple-500
                  py-2
                "
              >
                <div class="absolute inset-y-0 flex items-center pl-2 hidden">
                  <svg
                    class="w-4 h-4"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </div>
                <input
                  class="
                    w-full
                    pl-8
                    pr-2
                    py-2
                    text-sm text-gray-700
                    placeholder-gray-600
                    bg-gray-100
                    border-0
                    rounded-md
                    dark:placeholder-gray-500
                    dark:focus:shadow-outline-gray
                    dark:focus:placeholder-gray-600
                    dark:bg-gray-700
                    dark:text-gray-200
                    focus:placeholder-gray-500
                    focus:bg-white
                    focus:border-purple-300
                    focus:outline-none
                    focus:shadow-outline-purple
                    form-input
                    hidden
                  "
                  type="text"
                  placeholder="Search..."
                  aria-label="Search"
                />
              </div>
            </div>
            <ul class="flex items-center flex-shrink-0 space-x-6">
              <li class="relative">
                <button
                  class="
                    align-middle
                    rounded-full
                    focus:shadow-outline-purple focus:outline-none
                  "
                  aria-label="Account"
                  aria-haspopup="true"
                  @click="toggleProfileMenu"
                  ref="profileMenu"
                >
                  <span class="flex">
                    <span class="mt-1 px-1 text-black dark:text-white text-lg"
                    
                      >
                      <i class="fas fa-user" style="font-size: 35px;" ref="profileMenuName"></i>
                    </span>
                  </span>
                </button>
                <template v-if="isProfileMenuOpen">
                  <ul
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="
                      absolute
                      right-0
                      w-56
                      p-2
                      mt-2
                      space-y-2
                      text-gray-600
                      bg-white
                      border border-gray-100
                      rounded-md
                      shadow-md
                      dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700
                    "
                    aria-label="submenu"
                  >
                   <li class="flex">
                      <router-link
                        class="
                          inline-flex
                          items-center
                          w-full
                          px-2
                          py-1
                          text-sm
                          font-semibold
                          transition-colors
                          duration-150
                          rounded-md
                          hover:bg-gray-100 hover:text-gray-800
                          dark:hover:bg-gray-800 dark:hover:text-gray-200
                        "
                        to="/admin/dashboard"
                        v-if="user.user_role == 'admin'"
                      >
                        <i class="fa fa fa-tasks mx-2"></i>
                        <span>Admin Dashboard</span>
                      </router-link>
                    </li>
                  <li class="flex">
                      <router-link
                        class="
                          inline-flex
                          items-center
                          w-full
                          px-2
                          py-1
                          text-sm
                          font-semibold
                          transition-colors
                          duration-150
                          rounded-md
                          hover:bg-gray-100 hover:text-gray-800
                          dark:hover:bg-gray-800 dark:hover:text-gray-200
                        "
                        to="/user/update-profile"
                      >
                        <i class="fa fa-user-secret mx-2"></i>
                        <span>Update Profile</span>
                      </router-link>
                    </li>
                    <li class="flex">
                      <router-link
                        class="
                          inline-flex
                          items-center
                          w-full
                          px-2
                          py-1
                          text-sm
                          font-semibold
                          transition-colors
                          duration-150
                          rounded-md
                          hover:bg-gray-100 hover:text-gray-800
                          dark:hover:bg-gray-800 dark:hover:text-gray-200
                        "
                        to="/user/change-password"
                      >
                        <i class="fa fa-user mx-2"></i>
                        <span>Change Password</span>
                      </router-link>
                    </li>
                    <li class="flex">
                      <a
                        class="
                          inline-flex
                          items-center
                          w-full
                          px-2
                          py-1
                          text-sm
                          font-semibold
                          transition-colors
                          duration-150
                          rounded-md
                          hover:bg-gray-100 hover:text-gray-800
                          dark:hover:bg-gray-800 dark:hover:text-gray-200
                        "
                        href="#!"
                        @click.prevent="logout"
                      >
                        <i class="fa fa-sign-out mx-2"></i>
                        <span>Log out</span>
                      </a>
                    </li>
                  </ul>
                </template>
              </li>
            </ul>
          </div>
        </header>
        <main class="h-screen" ref="main">
          <!-- <BreadCrumb /> -->
          <router-view />
        </main>
      </div>
    </div>
  </main>
  <main v-else>
   <router-view />
  </main>
  </div>
</template>

<script lang="js">

// fancybox.
import {
    Fancybox
} from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";

export default {
    name: 'app',

    watch: {
        $route(to, from) {
            const base = ` App`
            let title = ""
            if (to.path !== "/") this.path = '/'
            else this.path = ""

            if (typeof to.meta.title === 'string')
                title = `${to.meta.title} - ${base}`
            else if (typeof to.meta.title === 'function')
                title = `${to.meta.title(to)} - ${base}`
            else title = base
            document.title = title

            // check if route contain admin.
            if (to.path.indexOf('admin') > -1) {
                this.isAdmin = true
            } else {
                this.isAdmin = false
            }

            // if route comes from login.
            if (from.path.indexOf('login') > -1) {
                this.user = this.$store.getters['Auth/user']
            }
        },
    },
    data() {
        return {
            top: false,
            appTheme: localStorage.getItem('theme') || 'light',
            path: "",
            scroll: 0,
            isProfileMenuOpen: false,
            user: {},
            isAdmin: false,
            mobileMenuOpen: false,
            showNotification: false,
            isSideMenuOpen: false,
            menuItems: [
                {
                    label: 'Dashboard',
                    icon: 'fas fa-home',
                    to: '/user/dashboard',
                    name: 'userDashboard',
                    isActive: false,
                    role: 'user',
                },
                                {
                    label: 'Dashboard',
                    icon: 'fas fa-home',
                    to: '/admin/dashboard',
                    name: 'adminDashboard',
                    isActive: false,
                    role: 'admin',
                },
                {
                    label: 'Add Funds',
                    icon: 'fas fa-money-bill-alt',
                    to: '/user/add-fund',
                    name: 'adminUsers',
                    isActive: false,
                    role: 'user',
                },
                {
                    label: "Create Card",
                    icon: 'fas fa-credit-card',
                    to: '/user/create-card',
                    name: 'userCreateCard',
                    isActive: false,
                    role: 'user',
                },
                {
                    label: "Virtual Card List",
                    icon: 'fas fa-list',
                    to: '/user/cards',
                    name: 'userCards',
                    isActive: false,
                    role: 'user',
                },
                {
                  label: 'Order Status',
                  icon: 'fas fa-regular fa-cart-shopping',
                  to: '/user/card-issue-status',
                  name: 'userCardIssueStatus',
                  isActive: false,
                  role: 'user',
                },
                {
                    label: "Transactions",
                    icon: 'fas fa-exchange-alt',
                    to: '/user/transactions',
                    name: 'userTransaction',
                    isActive: false,
                    role: 'user',
                },
                {
                    label: "Users",
                    icon: 'fas fa-users',
                    to: '/admin/users',
                    name: 'adminUsers',
                    isActive: false,
                    role: 'admin',
                },
                {
                    label: "Cards",
                    icon: 'fas fa-credit-card',
                    to: '/admin/cards',
                    name: 'adminCards',
                    isActive: false,
                    role: 'admin',
                },
                {
                    label: "Default Cards",
                    icon: 'fas fa-credit-card',
                    to: '/admin/default-cards',
                    name: 'adminCardDefault',
                    isActive: false,
                    role: 'admin',
                },
                {
                    label: "Card Requests",
                    icon: 'fas fa-credit-card',
                    to: '/admin/card-requests',
                    name: 'adminCardRequests',
                    isActive: false,
                    role: 'admin',
                },
            ],
        }
    },
    created() {
        // Fancybox init.
        let fancyboxELems = ["[data-fancybox='default']"]
        // for in loop.
        for (let i = 0; i < fancyboxELems.length; i++) {
            Fancybox.bind(fancyboxELems[i], {
                caption: function (fancybox, carousel, slide) {
                    return (
                        slide.caption || ""
                    );
                },
            });
        }

        // back to top button handler.
        window.addEventListener('scroll', this.handleScroll);

        // subscribe to theme changes.
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            if (mutation.type === 'Theme/setTheme') {
                this.appTheme = mutation.payload
            }
        })

        // if user is logged in, set the api heade token.
        if (this.$store.getters['Auth/login']) {
            this.$store.dispatch('Auth/setToken')
            this.$store.dispatch('Auth/getMe')
        }
    },
    computed: {
        theme() {
            return this.$store.getters['Theme/theme']
        },
        isLogin() {
            return this.$store.getters['Auth/login']
        },
    },
    mounted() {
        window.addEventListener("click", (e) => {
            // if the target of the click isn't the container nor a descendant of the container.
            if (this.$refs.profileMenu !== e.target &&
                this.$refs.profileMenuName !== e.target &&
                this.$refs.profileMenuPic !== e.target
            ) {
                this.isProfileMenuOpen = false
            }
        })


        this.menuHandler(this.$route);
        this.emitter.on('profile-updated', this.profileUpdated)

        this.user = this.$store.getters['Auth/user']
    },
    methods: {
        profileUpdated() {
            // refresh.
            this.$store.dispatch('Auth/getMe').then(() => {
                this.user = this.$store.getters['Auth/user']
            })
        },
        scrollTop() {
            window.scrollTo(0, 0);
        },
menuHandler(item) {
            // for (let i = 0; i < this.menuItems.length; i++) {
            //     if (this.menuItems[i].name.includes(item.name)) {
            //         this.menuItems[i].isActive = true;
            //     } else {
            //         this.menuItems[i].isActive = false;
            //     }
            // }
        },
          toggleSideBar() {
            this.isSideMenuOpen = !this.isSideMenuOpen
        },
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },
        // isLogin() {
        //     return this.$store.getters['Auth/login']
        // },
        handleScroll(event) {
            let scroll = document.body.scrollTop || document.documentElement.scrollTop;
            if (scroll > 150) {
                this.top = true
            } else {
                this.top = false
                //this.manageNavScroll('remove')
            }
            this.scroll = scroll;
        },

         
        logout() {
            this.isProfileMenuOpen = false;
            this.$store.dispatch('Auth/logout').then(() => {
                localStorage.removeItem('accessToken');
                localStorage.removeItem('refreshToken');
                localStorage.removeItem('user');
                localStorage.removeItem('meta');
                localStorage.removeItem('permissions');

                this.$router.push('/')
            })
        },
    },
    beoreDestroy() {
        this.unsubscribe()
    },
}
</script>
