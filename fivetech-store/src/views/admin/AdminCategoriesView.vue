<!-- src/views/admin/AdminCategoriesView.vue -->
<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Quản lý danh mục</h1>
      <button class="btn-primary">Thêm danh mục mới</button>
    </div>

    <div class="card p-6">
      <table class="w-full table-auto">
        <thead>
          <tr class="border-b">
            <th class="text-left p-4">ID</th>
            <th class="text-left p-4">Tên danh mục</th>
            <th class="text-left p-4">Danh mục cha</th>
            <th class="text-left p-4">Số sản phẩm</th>
            <th class="text-left p-4">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categories" :key="cat.id" class="border-b hover:bg-gray-50">
            <td class="p-4">{{ cat.id }}</td>
            <td class="p-4">{{ cat.name }}</td>
            <td class="p-4">{{ cat.parent || 'Không' }}</td>
            <td class="p-4">{{ cat.productsCount }}</td>
            <td class="p-4">
              <button class="text-blue-600 mr-4">Sửa</button>
              <button class="text-red-600">Xóa</button>
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
const categories = ref([
  { id: 1, name: 'Ốp lưng', parent: null, productsCount: 120 },
  { id: 2, name: 'Pin dự phòng', parent: null, productsCount: 45 },
  // Thêm nhiều hơn
])
</script>