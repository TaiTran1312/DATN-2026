<template>
  <div class="h-full flex flex-col">
    <!-- Logo -->
    <router-link
      to="/admin"
      class="flex items-center gap-3 px-6 py-5 border-b border-gray-700"
    >
      <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-900 font-bold text-xl">
        FT
      </div>
      <span class="text-xl font-bold">FiveTech</span>
    </router-link>

    <!-- Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2">
      <SidebarLink to="/admin" label="Dashboard" icon="Dashboard" />
      <SidebarLink to="/admin/products" label="Sản phẩm" icon="Package" />
      <SidebarLink to="/admin/categories" label="Danh mục" icon="Folder" />
      <SidebarLink to="/admin/orders" label="Đơn hàng" icon="ShoppingCart" />
      <SidebarLink to="/admin/users" label="Khách hàng" icon="Users" />
    </nav>

    <!-- Đăng xuất dưới cùng -->
    <div class="border-t border-gray-700 mt-auto px-6 py-4">
      <button
        @click="logout"
        class="w-full text-left text-red-400 hover:text-red-300 transition"
      >
        Đăng xuất
      </button>
    </div>
  </div>
</template>

<script setup>
import SidebarLink from '@/components/SidebarLink.vue'  // Đảm bảo đường dẫn đúng

const logout = () => {
  // Logic đăng xuất (xóa token, redirect...)
  localStorage.removeItem('token')
  window.location.href = '/'
}
</script>

<style scoped>
/* Tùy chỉnh thêm nếu cần */
</style>