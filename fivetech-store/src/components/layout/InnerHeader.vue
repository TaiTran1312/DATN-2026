<template>
  <header class="home-header">
    <div class="header-container">
      <!-- Logo -->
      <a href="/" class="logo">
        <span class="logo-icon">T5</span>
        <span class="logo-text">Techfive</span>
      </a>

      <!-- Navigation Menu -->
      <nav class="nav-menu">
        <a href="/" class="nav-link active">Trang chủ</a>
        <a href="/products" class="nav-link">Sản phẩm</a>
        <a href="/news" class="nav-link">Tin tức</a>
        <a href="/contact" class="nav-link">Liên hệ</a>
      </nav>

      <!-- Search Box -->
      <div class="search-box" ref="searchBox">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Tìm kiếm sản phẩm..."
          class="search-input"
          @focus="showDropdown = true"
          @keyup.enter="goToSearch"
        />

        <button class="search-btn" @click="goToSearch">
          🔍
        </button>

      </div>

      <!-- Auth Buttons -->
      <div class="auth-buttons">
        <a href="/login" class="auth-btn login-btn">Đăng nhập</a>
        <a href="/register" class="auth-btn register-btn">Đăng ký</a>
      </div>

      <!-- Cart Icon -->
      <a href="/cart" class="cart-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="8" cy="21" r="1"></circle>
          <circle cx="19" cy="21" r="1"></circle>
          <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
        </svg>
        <span class="cart-count">3</span>
      </a>

      <!-- Mobile Menu Toggle -->
      <button class="mobile-menu-toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="4" x2="20" y1="12" y2="12"></line>
          <line x1="4" x2="20" y1="6" y2="6"></line>
          <line x1="4" x2="20" y1="18" y2="18"></line>
        </svg>
      </button>
    </div>
  </header>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const searchQuery = ref('')
const results = ref([])
const showDropdown = ref(false)
const searchBox = ref(null)

let debounceTimer = null

// Gọi API search gợi ý
watch(searchQuery, (val) => {
  clearTimeout(debounceTimer)

  if (!val.trim()) {
    results.value = []
    return
  }

  debounceTimer = setTimeout(async () => {
    try {
      const res = await axios.get('/api/search', {
        params: { q: val, limit: 6 }
      })
      results.value = res.data.data
      showDropdown.value = true
    } catch {
      results.value = []
    }
  }, 300)
})

// Enter hoặc click icon
const goToSearch = () => {
  const q = searchQuery.value.trim()
  if (!q) return

  showDropdown.value = false
  router.push({
    name: 'Products',
    query: { search: q }
  })
}

// Click ra ngoài thì đóng dropdown
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!searchBox.value?.contains(e.target)) {
      showDropdown.value = false
    }
  })
})
</script>



<style scoped>
.search-box {
  position: relative;
}

.search-item {
  display: flex;
  gap: 12px;
  padding: 10px;
  text-decoration: none;
  color: #111;
}

.search-item:hover {
  background: #f3f4f6;
}

.search-item img {
  width: 48px;
  height: 48px;
  object-fit: cover;
  border-radius: 8px;
}

.name {
  font-weight: 600;
}

.price {
  color: #e11d48;
  font-size: 14px;
}

/* ================= HOME HEADER ================= */
.home-header {
  position: sticky;
  top: 0;
  z-index: 1000;
  background: linear-gradient(135deg, #0a1628 0%, #1a2d4a 100%);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.header-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
  height: 72px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
}

/* Logo */
.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
}

.logo-icon {
  width: 44px;
  height: 44px;
  background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  font-size: 18px;
  font-weight: 800;
  box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
}

.logo-text {
  font-size: 26px;
  font-weight: 800;
  color: #ffffff;
  letter-spacing: -0.5px;
}

/* Navigation */
.nav-menu {
  display: flex;
  align-items: center;
  gap: 8px;
}

.nav-link {
  padding: 10px 20px;
  color: #cbd5e1;
  text-decoration: none;
  font-weight: 500;
  font-size: 15px;
  border-radius: 8px;
  transition: all 0.3s ease;
  position: relative;
}

.nav-link:hover {
  color: #ffffff;
  background: rgba(255, 255, 255, 0.1);
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #ff6b35, #f7931e);
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 60%;
}

/* Search Box */
.search-box {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.search-box:focus-within {
  background: rgba(255, 255, 255, 0.15);
  border-color: #ff6b35;
  box-shadow: 0 0 20px rgba(255, 107, 53, 0.2);
}

.search-input {
  width: 240px;
  padding: 12px 16px;
  border: none;
  background: transparent;
  color: #ffffff;
  font-size: 14px;
  outline: none;
}

.search-input::placeholder {
  color: #94a3b8;
}

.search-btn {
  padding: 12px 16px;
  background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
  border: none;
  color: #ffffff;
  cursor: pointer;
  transition: all 0.3s ease;
}

.search-btn:hover {
  background: linear-gradient(135deg, #e85a2a 0%, #e88619 100%);
}

/* Cart Icon */
.cart-icon {
  position: relative;
  width: 48px;
  height: 48px;
  padding: 0;
  color: #ffffff;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-icon:hover {
  background: rgba(255, 107, 53, 0.2);
  transform: translateY(-2px);
}

/* Auth Buttons */
.auth-buttons {
  display: flex;
  align-items: center;
  gap: 12px;
}

.auth-btn {
  padding: 10px 20px;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}

.login-btn {
  color: #ffffff;
  background: transparent;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.login-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.5);
}

.register-btn {
  color: #ffffff;
  background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
  box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.register-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
}

.cart-count {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 20px;
  height: 20px;
  background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
  color: #ffffff;
  font-size: 11px;
  font-weight: 700;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
  display: none;
  padding: 8px;
  background: transparent;
  border: none;
  color: #ffffff;
  cursor: pointer;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
  .nav-menu {
    display: none;
  }

  .search-box {
    flex: 1;
    max-width: 300px;
  }

  .search-input {
    width: 100%;
  }

  .mobile-menu-toggle {
    display: block;
  }
}

@media (max-width: 640px) {
  .header-container {
    padding: 0 16px;
    height: 64px;
  }

  .logo-text {
    display: none;
  }

  .search-box {
    display: none;
  }
}
</style>
