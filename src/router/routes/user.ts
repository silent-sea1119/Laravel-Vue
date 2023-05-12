export default [
    {
        path: '/user/update-profile',
        name: 'EditProfile',
        component: () => import('@/views/User/ManageProfile.vue'),
        meta: {
            title: "Edit Profile",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/change-password',
        name: 'ChangePassword',
        component: () => import('@/views/User/ChangePassword.vue'),
        meta: {
            title: "Change Password",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/cards',
        name: 'Cards',
        component: () => import('@/views/User/CardsView.vue'),
        meta: {
            title: "Cards",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/card-issue-status',
        name: 'CardIssues',
        component: () => import('@/views/User/CardRequests.vue'),
        meta: {
            title: "Cards",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/dashboard',
        name: 'Dashboard',
        component: () => import('@/views/User/DashboardView.vue'),
        meta: {
            title: "Dashboard",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/transactions',
        name: 'Transactions',
        component: () => import('@/views/User/TransactionsView.vue'),
        meta: {
            title: "Transactions",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/add-fund',
        name: 'AddFunds',
        component: () => import('@/views/User/AddFunds.vue'),
        meta: {
            title: "Add Funds",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: '/user/create-card',
        name: 'CreateCard',
        component: () => import('@/views/User/RequestCard.vue'),
        meta: {
            title: "Create New Card",
            requiresAuth: true,
            requiresAdmin: false,
        },
    },
    {
        path: "/",
        name: "Login",
        component: () => import('@/views/LoginView.vue'),
        meta: {
            title: "Login",
            requiresAuth: false,
        }
    },
    {
        path: "/register",
        name: "Register",
        component: () => import('@/views/RegisterView.vue'),
        meta: {
            title: "Register",
            requiresAuth: false,
        }
    },
    {
        path: "/reset",
        name: "Reset",
        component: () => import('@/views/ResetPassword.vue'),
        meta: {
            title: "Reset",
            requiresAuth: false,
        }
    },
]
