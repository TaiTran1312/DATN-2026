<template>
  <div class="cart-page">
    <!-- Page Header -->
    <div class="page-header">
      <div class="container">
        <nav class="breadcrumb">
          <a href="/">Trang chủ</a>
          <span class="separator">/</span>
          <span class="current">Giỏ hàng</span>
        </nav>
        <h1 class="page-title">Giỏ hàng của bạn</h1>
        <p class="page-subtitle">Kiểm tra lại các sản phẩm đã chọn trước khi thanh toán</p>
      </div>
    </div>

    <!-- Cart Content -->
    <div class="cart-content">
      <div class="container">
        <!-- Empty Cart State (Hidden by default for static demo) -->
        <div v-if="cartItems.length === 0" class="empty-cart">
          <span class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
          </span>
          <h2>Giỏ hàng trống</h2>
          <p>Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
          <a href="/products" class="btn-primary">Tiếp tục mua sắm</a>
        </div>

        <!-- Cart Layout -->
        <div v-else class="cart-layout">
          <!-- Left Column: Cart Items -->
          <div class="cart-items-wrapper">
            <div class="cart-items">
              <!-- Header -->
              <div class="cart-header">
                <div class="col-product">Sản phẩm</div>
                <div class="col-price">Đơn giá</div>
                <div class="col-qty">Số lượng</div>
                <div class="col-subtotal">Thành tiền</div>
                <div class="col-action"></div>
              </div>

              <!-- Item 1 -->
              <div class="cart-item" v-for="item in cartItems" :key="item.id">
                <div class="item-product" data-label="Sản phẩm">
                  <img :src="item.image" :alt="item.name" class="item-image" />
                  <div class="item-info">
                    <a href="#" class="item-name">{{ item.name }}</a>
                    <span class="item-variant">Phân loại: {{ item.variant }}</span>
                  </div>
                </div>

                <div class="item-price" data-label="Đơn giá">{{ formatPrice(item.price) }}</div>

                <div class="item-qty" data-label="Số lượng">
                  <div class="quantity-box">
                    <button class="qty-btn" @click="decreaseQty(item.id)">−</button>
                    <input type="text" class="qty-input" :value="item.quantity" readonly />
                    <button class="qty-btn" @click="increaseQty(item.id)">+</button>
                  </div>
                </div>

                <div class="item-subtotal col-subtotal" data-label="Thành tiền">
                  {{ formatPrice(item.price * item.quantity) }}
                </div>

                <div class="item-action">
                  <button class="remove-btn" title="Xóa sản phẩm" @click="removeItem(item.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Summary -->
          <div class="cart-summary">
            <h3 class="summary-title">Thông tin đơn hàng</h3>

            <div class="coupon-section">
              <label class="form-label" style="color: #94a3b8; margin-bottom: 8px; display: block;">Mã giảm giá</label>
              <div class="coupon-input-group">
                <input type="text" placeholder="Nhập mã voucher" class="coupon-input" />
                <button class="coupon-btn">Áp dụng</button>
              </div>
            </div>

            <div class="summary-details">
              <div class="summary-row">
                <span>Tạm tính</span>
                <span class="summary-value">{{ formatPrice(subtotal) }}</span>
              </div>
              <div class="summary-row">
                <span>Giảm giá</span>
                <span class="summary-value">-0đ</span>
              </div>
              <div class="summary-row">
                <span>Phí vận chuyển</span>
                <span class="summary-value" style="color: #22c55e;">Miễn phí</span>
              </div>

              <div class="summary-row total">
                <span>Tổng cộng</span>
                <span class="summary-value">{{ formatPrice(subtotal) }}</span>
              </div>
            </div>

            <button class="checkout-btn">
              Tiến hành thanh toán
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>

            <a href="/products" class="continue-shopping">← Tiếp tục mua sắm</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Dummy Data for Static UI
const cartItems = ref([
  {
    id: 1,
    name: "Ốp lưng iPhone 15 Pro Max MagSafe Premium",
    variant: "Đen",
    price: 299000,
    quantity: 1,
    image: "https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=150&h=150&fit=crop"
  },
  {
    id: 2,
    name: "Củ sạc nhanh 65W GaN USB-C",
    variant: "Trắng",
    price: 450000,
    quantity: 2,
    image: "https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=150&h=150&fit=crop"
  },
  {
    id: 3,
    name: "Tai nghe Bluetooth Pro ANC",
    variant: "Trắng",
    price: 1290000,
    quantity: 1,
    image: "https://images.unsplash.com/photo-1583394838336-acd977736f90?w=150&h=150&fit=crop"
  }
])

const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price)
}

const increaseQty = (id) => {
  const item = cartItems.value.find(i => i.id === id)
  if (item) item.quantity++
}

const decreaseQty = (id) => {
  const item = cartItems.value.find(i => i.id === id)
  if (item && item.quantity > 1) item.quantity--
}

const removeItem = (id) => {
  cartItems.value = cartItems.value.filter(i => i.id !== id)
}
</script>