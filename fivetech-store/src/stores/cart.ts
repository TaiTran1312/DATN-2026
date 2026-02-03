import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: []
  }),

  getters: {
    totalItems: (state) =>
      state.items.reduce((sum, i) => sum + i.quantity, 0),

    totalPrice: (state) =>
      state.items.reduce(
        (sum, i) => sum + i.variant.price * i.quantity,
        0
      )
  },

  actions: {
    addItem(variant, quantity = 1) {
      const item = this.items.find(
        i => i.variant.variant_id === variant.variant_id
      )

      if (item) {
        item.quantity += quantity
      } else {
        this.items.push({ variant, quantity })
      }
    },

    removeItem(id) {
      this.items = this.items.filter(
        i => i.variant.variant_id !== id
      )
    },

    // ✅ ĐẶT Ở ĐÂY
    increase(id) {
      const item = this.items.find(
        i => i.variant.variant_id === id
      )
      if (item) item.quantity++
    },

    decrease(id) {
      const item = this.items.find(
        i => i.variant.variant_id === id
      )
      if (item && item.quantity > 1) item.quantity--
    }
  }
})
