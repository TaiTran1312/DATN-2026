<template>
  <div class="admin-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <AdminSidebar />
    </aside>

    <!-- MAIN -->
    <div class="flex flex-col flex-1">
      <AdminHeader />
      <main class="main-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import AdminHeader from '@/components/layout/AdminHeader.vue'
import AdminSidebar from '@/components/layout/AdminSidebar.vue'
</script>
