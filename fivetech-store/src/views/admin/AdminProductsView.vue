<!-- src/views/admin/AdminProductsView.vue -->
<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Quản lý sản phẩm</h1>
      <router-link to="/admin/products/new" class="btn-primary">Thêm sản phẩm mới</router-link>
    </div>

    <div class="card p-6">
      <table class="w-full table-auto mb-8">
        <thead>
          <tr class="border-b">
            <th class="text-left p-4">ID</th>
            <th class="text-left p-4">Tên sản phẩm</th>
            <th class="text-left p-4">Giá</th>
            <th class="text-left p-4">Danh mục</th>
            <th class="text-left p-4">Tồn kho</th>
            <th class="text-left p-4">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in paginatedProducts" :key="p.id" class="border-b hover:bg-gray-50">
            <td class="p-4">{{ p.id }}</td>
            <td class="p-4">{{ p.name }}</td>
            <td class="p-4">{{ formatPrice(p.price) }}</td>
            <td class="p-4">{{ p.category }}</td>
            <td class="p-4">{{ p.stock }}</td>
            <td class="p-4">
              <router-link :to="`/admin/products/${p.id}/edit`" class="text-blue-600 mr-4">Sửa</router-link>
              <button @click="deleteProduct(p.id)" class="text-red-600">Xóa</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex items-center justify-between">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="btn-outline disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Trang trước
        </button>

        <span class="text-gray-600">
          Trang {{ currentPage }} / {{ totalPages }}
        </span>

        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="btn-outline disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Trang sau
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Dữ liệu tĩnh (thay bằng API sau)
const products = ref([
  { id: 1, name: 'Ốp lưng iPhone 15', price: 250000, category: 'Ốp lưng', stock: 50 },
  { id: 2, name: 'Pin dự phòng 20000mAh', price: 450000, category: 'Pin', stock: 30 },
  { id: 3, name: 'Cáp sạc Baseus', price: 150000, category: 'Cáp', stock: 100 },
  { id: 4, name: 'Tai nghe Bluetooth', price: 800000, category: 'Tai nghe', stock: 20 },
  { id: 5, name: 'Kính cường lực Samsung', price: 120000, category: 'Kính', stock: 80 },
  { id: 6, name: 'Sạc nhanh 65W', price: 350000, category: 'Sạc', stock: 45 },
  // Thêm nhiều hơn để test pagination (tổng 6 item, 3 item/trang → 2 trang)
])

const itemsPerPage = ref(3)  // Số item mỗi trang (chỉnh tùy ý)
const currentPage = ref(1)   // Trang hiện tại

// Computed cho dữ liệu phân trang
const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return products.value.slice(start, end)
})

// Tổng số trang
const totalPages = computed(() => Math.ceil(products.value.length / itemsPerPage.value))

// Chuyển trang trước
const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--
}

// Chuyển trang sau
const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++
}

const formatPrice = (price) => price.toLocaleString('vi-VN') + ' ₫'

const deleteProduct = (id) => {
  // Logic xóa
  alert(`Xóa sản phẩm ${id}`)
}
</script>