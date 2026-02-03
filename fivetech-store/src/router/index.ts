import { createRouter, createWebHistory } from 'vue-router'

import MainLayout from '@/layouts/MainLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

// Public routes (không cần đăng nhập)
const publicRoutes = [
  // Home page with its own header/footer (standalone)
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/cilent/Home.vue'),
    meta: { title: 'Techfive - Phụ kiện điện thoại chính hãng' }
  },
  // Auth pages (standalone - no layout)
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/cilent/Login.vue'),
    meta: { title: 'Đăng nhập', guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('@/views/cilent/Register.vue'),
    meta: { title: 'Đăng ký', guestOnly: true }
  },
  // Other pages use MainLayout
  {
    path: '/',
    component: MainLayout,
    children: [
      // Danh sách sản phẩm (phải nằm TRƯỚC chi tiết)
      {
        path: 'products',
        name: 'Products',
        component: () => import('@/views/cilent/Products.vue'),
        meta: { title: 'Tất cả sản phẩm' }
      },

      // Chi tiết sản phẩm - dùng :slug (đặt SAU danh sách)
      {
        path: 'products/:slug',
        name: 'ProductDetail',
        component: () => import('@/views/cilent/ProductDetail.vue'),
        props: true,
        meta: { title: 'Chi tiết sản phẩm' }
      },

      // Các trang khác
      {
        path: 'cart',
        name: 'Cart',
        component: () => import('@/views/cilent/CartView.vue'),
        meta: { title: 'Giỏ hàng' }
      },
      {
        path: 'news',
        name: 'News',
        component: () => import('@/views/cilent/News.vue'),
        meta: { title: 'Tin tức & Bài viết' }
      },
      {
        path: 'contact',
        name: 'Contact',
        component: () => import('@/views/cilent/Contact.vue'),
        meta: { title: 'Liên hệ' }
      },
    ]
  }
]

// // Auth routes (đăng nhập, đăng ký, quên mật khẩu)
// const authRoutes = [
//   {
//     path: '/login',
//     name: 'Login',
//     component: () => import('@/views/auth/LoginView.vue'),
//     meta: { title: 'Đăng nhập', guestOnly: true }
//   },
//   {
//     path: '/register',
//     name: 'Register',
//     component: () => import('@/views/auth/RegisterView.vue'),
//     meta: { title: 'Đăng ký', guestOnly: true }
//   },
//   {
//     path: '/forgot-password',
//     name: 'ForgotPassword',
//     component: () => import('@/views/auth/ForgotPasswordView.vue'),
//     meta: { title: 'Quên mật khẩu', guestOnly: true }
//   },
//   {
//     path: '/reset-password/:token',
//     name: 'ResetPassword',
//     component: () => import('@/views/auth/ResetPasswordView.vue'),
//     props: true,
//     meta: { title: 'Đặt lại mật khẩu' }
//   }
// ]

// // User routes (cần đăng nhập)
// const userRoutes = [
//   {
//     path: '/account',
//     name: 'Account',
//     component: () => import('@/views/user/AccountView.vue'),
//     meta: { title: 'Tài khoản của tôi', requiresAuth: true }
//   },
//   {
//     path: '/account/orders',
//     name: 'Orders',
//     component: () => import('@/views/user/OrdersView.vue'),
//     meta: { title: 'Đơn hàng của tôi', requiresAuth: true }
//   },
//   {
//     path: '/account/wishlist',
//     name: 'Wishlist',
//     component: () => import('@/views/user/WishlistView.vue'),
//     meta: { title: 'Danh sách yêu thích', requiresAuth: true }
//   },
//   {
//     path: '/account/addresses',
//     name: 'Addresses',
//     component: () => import('@/views/user/AddressesView.vue'),
//     meta: { title: 'Sổ địa chỉ', requiresAuth: true }
//   }
// ]

// Admin routes (prefix /admin, cần quyền admin)
const adminRoutes = [
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '',
        name: 'AdminDashboard',
        component: () => import('@/views/admin/AdminDashboardView.vue'),
        meta: { title: 'Admin Dashboard' }
      },
      {
        path: 'categories',
        name: 'AdminCategories',
        component: () => import('@/views/admin/AdminCategoriesView.vue'),
        meta: { title: 'Quản lý danh mục' }
      },
      {
        path: 'users',
        name: 'AdminUsers',
        component: () => import('@/views/admin/AdminUsersView.vue'),
        meta: { title: 'Quản lý người dùng' }
      }
    ]
  },
  // {
  //   path: '/admin/products',
  //   name: 'AdminProducts',
  //   component: () => import('@/views/admin/AdminProductsListView.vue'),
  //   meta: { title: 'Quản lý sản phẩm', requiresAuth: true, requiresAdmin: true }
  // },
  // {
  //   path: '/admin/products/new',
  //   name: 'AdminProductCreate',
  //   component: () => import('@/views/admin/AdminProductCreateView.vue'),
  //   meta: { title: 'Thêm sản phẩm mới', requiresAuth: true, requiresAdmin: true }
  // },
  // {
  //   path: '/admin/products/:id/edit',
  //   name: 'AdminProductEdit',
  //   component: () => import('@/views/admin/AdminProductEditView.vue'),
  //   props: true,
  //   meta: { title: 'Chỉnh sửa sản phẩm', requiresAuth: true, requiresAdmin: true }
  // },
  // {
  //   path: '/admin/orders',
  //   name: 'AdminOrders',
  //   component: () => import('@/views/admin/AdminOrdersView.vue'),
  //   meta: { title: 'Quản lý đơn hàng', requiresAuth: true, requiresAdmin: true }
  // },
]

// 404 Not Found
const notFoundRoute = {
  path: '/:pathMatch(.*)*',
  name: 'NotFound',
  component: () => import('@/views/cilent/NotFoundView.vue'),
  meta: { title: 'Không tìm thấy trang' }
}

const routes = [
  ...publicRoutes,
  // ...authRoutes,
  // ...userRoutes,
  ...adminRoutes,
  notFoundRoute
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    return { top: 0 }
  }
})

// Navigation Guards (ví dụ cơ bản - bạn có thể mở rộng sau)
// router.beforeEach((to, from, next) => {
//   // Cập nhật title
//   document.title = to.meta.title ? `${to.meta.title} | FiveTech Store` : 'FiveTech Store'

//   // Kiểm tra requiresAuth
//   const isAuthenticated = !!localStorage.getItem('token') // thay bằng logic auth thật của bạn (Pinia store)

//   if (to.meta.requiresAuth && !isAuthenticated) {
//     next({ name: 'Login', query: { redirect: to.fullPath } })
//   } else if (to.meta.guestOnly && isAuthenticated) {
//     next({ name: 'Home' })
//   } else if (to.meta.requiresAdmin) {
//     // Giả sử bạn có role trong store hoặc token
//     const isAdmin = true // thay bằng check thật: user.role === 'admin'
//     if (!isAdmin) {
//       next({ name: 'Home' })
//     } else {
//       next()
//     }
//   } else {
//     next()
//   }
// })

export default router