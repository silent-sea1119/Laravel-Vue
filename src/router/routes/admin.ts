export default [
    {
        path: '/admin/dashboard',
        name: 'adminDashboard',
        component: () => import('@/views/Admin/DashboardView.vue'),
        meta: {
            title: "Dashboard",
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/admin/users',
        name: 'adminUsers',
        component: () => import('@/views/Admin/Users/UsersView.vue'),
        meta: {
            title: "Users",
            requiresPermission: 'admin.users.index',
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users'},
            ]
        }
    },
    {
        path: '/admin/user/:id?',
        name: 'viewUser',
        component: () => import('@/views/Admin/Users/UserView.vue'),
        meta: {
            title: "View User",
            requiresPermission: 'admin.users.show',
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users', link: '/admin/users'},
            { name: 'View User'},
            ]
        }
    },
    {
        path: '/admin/user/manage/:id?',
        name: 'manageUser',
        component: () => import('@/views/Admin/Users/ManageUser.vue'),
        meta: {
            title: "Add User",
            requiresPermission: 'admin.users.create',
            breadcrumb: [
                { name: 'Dashboard', link: '/admin/dashboard'},
                { name: 'Users', link: '/admin/users'},
                { name: 'Add User'},
            ]
        }
    },
    {
        path: '/admin/transactions/:id?',
        name: 'manageTransactions',
        component: () => import('@/views/Admin/Users/TransactionsView.vue'),
        meta: {
            title: "Transactions",
            breadcrumb: [
                { name: 'Dashboard', link: '/admin/dashboard'},
                { name: 'Users', link: '/admin/users'},
                { name: 'Transactions'},
            ]
        }
    },

    // Card.
    {
        path: '/admin/cards',
        name: 'adminCards',
        component: () => import('@/views/Admin/Cards/CardsView.vue'),
        meta: {
            title: "Cards",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users'},
            ]
        }
    },
    {
        path: '/admin/card/import',
        name: 'adminCardImport',
        component: () => import('@/views/Admin/Cards/CardImport.vue'),
        meta: {
            title: "Import Cards",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users'},
            ]
        }
    },
    {
        path: '/admin/card/:id?',
        name: 'viewCard',
        component: () => import('@/views/Admin/Cards/CardView.vue'),
        meta: {
            title: "View Card",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users', link: '/admin/users'},
            { name: 'View User'},
            ]
        }
    },
    {
        path: '/admin/card/manage/:id?',
        name: 'manageCard',
        component: () => import('@/views/Admin/Cards/manageCard.vue'),
        meta: {
            title: "Add Card",
            breadcrumb: [
                { name: 'Dashboard', link: '/admin/dashboard'},
                { name: 'Users', link: '/admin/users'},
                { name: 'Add User'},
            ]
        }
    },

    // Default Card.
    {
        path: '/admin/default-cards',
        name: 'adminCardsD',
        component: () => import('@/views/Admin/CardsD/CardsView.vue'),
        meta: {
            title: "Cards",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users'},
            ]
        }
    },
    {
        path: '/admin/card-default/import',
        name: 'adminCardImportD',
        component: () => import('@/views/Admin/CardsD/CardImport.vue'),
        meta: {
            title: "Import Cards",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users'},
            ]
        }
    },
    {
        path: '/admin/card-default/manage/:id?',
        name: 'manageCardD',
        component: () => import('@/views/Admin/CardsD/manageCard.vue'),
        meta: {
            title: "Add Default Card",
            breadcrumb: [
                { name: 'Dashboard', link: '/admin/dashboard'},
                { name: 'Users', link: '/admin/users'},
                { name: 'Add User'},
            ]
        }
    },

    // Card requests
    {
        path: '/admin/card-requests',
        name: 'adminCardRequest',
        component: () => import('@/views/Admin/CardRequests/CardsView.vue'),
        meta: {
            title: "Card Requests",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users'},
            ]
        }
    },
    {
        path: '/admin/card-request/:id?',
        name: 'viewCardRequest',
        component: () => import('@/views/Admin/CardRequests/CardView.vue'),
        meta: {
            title: "View Card Request",
            breadcrumb: [
            { name: 'Dashboard', link: '/admin/dashboard'},
            { name: 'Users', link: '/admin/users'},
            { name: 'View User'},
            ]
        }
    },
]
