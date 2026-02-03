<!-- src/views/admin/AdminUsersView.vue -->
<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <h1 class="text-3xl font-bold mb-8 text-gray-900">Quản lý khách hàng</h1>

    <div class="card p-6">
      <table class="w-full table-auto">
        <thead>
          <tr class="border-b">
            <th class="text-left p-4">ID</th>
            <th class="text-left p-4">Tên</th>
            <th class="text-left p-4">Email</th>
            <th class="text-left p-4">Số đơn hàng</th>
            <th class="text-left p-4">Trạng thái</th>
            <th class="text-left p-4">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users" :key="u.id" class="border-b hover:bg-gray-50">
            <td class="p-4">{{ u.id }}</td>
            <td class="p-4">{{ u.name }}</td>
            <td class="p-4">{{ u.email }}</td>
            <td class="p-4">{{ u.ordersCount }}</td>
            <td class="p-4">
              <span :class="u.active ? 'text-green-600' : 'text-red-600'">{{ u.active ? 'Hoạt động' : 'Khóa' }}</span>
            </td>
            <td class="p-4">
              <button class="text-blue-600 mr-4">Chi tiết</button>
              <button class="text-red-600">Khóa</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Dữ liệu tĩnh
const users = ref([
  { id: 1, name: 'Nguyễn Văn A', email: 'a@example.com', ordersCount: 5, active: true },
  { id: 2, name: 'Trần Thị B', email: 'b@example.com', ordersCount: 3, active: false },
  // Thêm nhiều hơn
])
</script>