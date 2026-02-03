<!-- src/views/admin/AdminDashboardView.vue -->
<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <h1 class="text-3xl font-bold mb-8 text-gray-900">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
      <!-- Dashboard Cards -->
      <div class="card p-6">
        <h3 class="text-xl font-semibold mb-2">Tổng sản phẩm</h3>
        <p class="text-4xl font-bold text-gray-900">256</p>
      </div>

      <div class="card p-6">
        <h3 class="text-xl font-semibold mb-2">Tổng đơn hàng</h3>
        <p class="text-4xl font-bold text-gray-900">1,024</p>
      </div>

      <div class="card p-6">
        <h3 class="text-xl font-semibold mb-2">Doanh thu tháng</h3>
        <p class="text-4xl font-bold text-gray-900">500M</p>
      </div>

      <div class="card p-6">
        <h3 class="text-xl font-semibold mb-2">Khách hàng mới</h3>
        <p class="text-4xl font-bold text-gray-900">128</p>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="text-2xl font-bold mb-6">Thống kê nhanh</h2>
      <!-- Có thể thêm chart ở đây (sử dụng Chart.js sau) -->
      <p class="text-gray-600">Biểu đồ doanh thu, đơn hàng...</p>
    </div>
  </div>
</template>

<script setup>
// Có thể fetch data từ API sau
</script>