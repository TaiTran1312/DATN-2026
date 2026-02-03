<template>
  <div class="flex gap-6 bg-white p-6 rounded-xl shadow">
    <img
      :src="item.variant.image_urls?.[0] || 'https://via.placeholder.com/120'"
      class="w-32 h-32 object-cover rounded-lg"
    />

    <div class="flex-1">
      <h3 class="font-semibold text-lg mb-2">
        {{ item.variant.name || 'Biến thể' }}
      </h3>

      <p class="text-gray-600 mb-2">
        Màu: {{ item.variant.color }} • Size: {{ item.variant.size || 'N/A' }}
      </p>

      <div class="flex items-center justify-between">
        <div class="text-xl font-bold text-blue-600">
          {{ formatPrice(item.variant.price * item.quantity) }}
        </div>

        <div class="flex items-center border rounded">
          <button @click="updateQuantity(-1)" class="px-4 py-1">-</button>
          <span class="px-6 py-1 font-medium">{{ item.quantity }}</span>
          <button @click="updateQuantity(1)" class="px-4 py-1">+</button>
        </div>
      </div>

      <button
        @click="cartStore.removeItem(item.variant.variant_id)"
        class="text-red-600 mt-3 hover:underline"
      >
        Xóa
      </button>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart'

const props = defineProps({
  item: Object
})

const cartStore = useCartStore()

const formatPrice = (price) =>
  price.toLocaleString('vi-VN') + ' ₫'

const updateQuantity = (change) => {
  const id = props.item.variant.variant_id

  if (change === 1) {
    cartStore.increase(id)
  }

  if (change === -1 && props.item.quantity > 1) {
    cartStore.decrease(id)
  }
}
</script>
