<template>
  <div class="product-detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
      <div class="container">
        <nav class="breadcrumb">
          <router-link to="/">Trang chủ</router-link>
          <span class="separator">/</span>
          <router-link to="/products">Sản phẩm</router-link>
          <span class="separator">/</span>
          <span class="current">{{ product?.category?.name || 'Danh mục' }}</span>
          <span class="separator">/</span>
          <span class="current">{{ product?.name || 'Đang tải...' }}</span>
        </nav>
      </div>
    </div>

    <!-- Loading / Error -->
    <div v-if="loading" class="loading-container">
      <p>Đang tải thông tin sản phẩm...</p>
    </div>
    <div v-else-if="error" class="error-container">
      <p>{{ error }}</p>
      <router-link to="/products">Quay lại danh sách sản phẩm</router-link>
    </div>

    <!-- Nội dung chính -->
    <div v-else class="content">
      <!-- Product Section -->
      <section class="product-section">
        <div class="container">
          <div class="product-layout">
            <!-- Product Gallery -->
            <div class="product-gallery">
              <div class="main-image">
                <span v-if="product.is_featured" class="product-badge badge-hot">Hot</span>
                <img
                  :src="selectedImage || 'https://via.placeholder.com/600?text=' + encodeURIComponent(product.name)"
                  :alt="product.name"
                  class="gallery-image"
                />
                <button class="zoom-btn" title="Phóng to">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="zoom-icon">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                    <line x1="11" y1="8" x2="11" y2="14"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                  </svg>
                </button>
              </div>
              <div class="thumbnail-list">
                <button
                  v-for="(img, index) in images"
                  :key="index"
                  class="thumbnail"
                  :class="{ active: selectedImage === img }"
                  @click="selectedImage = img"
                >
                  <img :src="img" :alt="`Hình ${index + 1}`" />
                </button>
              </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
              <h1 class="product-title">{{ product.name }}</h1>

              <div class="product-meta">
                <div class="rating">
                  <span class="stars">★★★★★</span>
                  <span class="rating-text">{{ averageRating.toFixed(1) }} ({{ product.comments?.length || 0 }} đánh giá)</span>
                </div>
                <span class="divider">|</span>
                <span class="sold">Đã bán {{ product.sales_count || '1.2K' }}</span>
                <span class="divider">|</span>
                <span class="sku">SKU: {{ selectedVariant?.sku || product.slug?.toUpperCase() }}</span>
              </div>

              <div class="price-box">
                <span class="current-price">{{ formatPrice(selectedVariant?.discount_price || product.discount_price || product.base_price) }}đ</span>
                <span v-if="discountPercent > 0" class="old-price">{{ formatPrice(selectedVariant?.base_price || product.base_price) }}đ</span>
                <span v-if="discountPercent > 0" class="discount-badge">-{{ discountPercent }}%</span>
              </div>

              <!-- Variants -->
              <div class="variant-section" v-if="product.variants?.length">
                <h4 class="variant-title">Màu sắc / Biến thể</h4>
                <div class="variant-options">
                  <button
                    v-for="variant in product.variants"
                    :key="variant.variant_id"
                    class="variant-btn"
                    :class="{ active: selectedVariant?.variant_id === variant.variant_id }"
                    @click="selectVariant(variant)"
                  >
                    <span class="color-dot" :style="{ backgroundColor: variant.color || '#000' }"></span>
                    {{ variant.name || variant.color || variant.storage_size || 'Mặc định' }}
                  </button>
                </div>
              </div>

              <!-- Quantity -->
              <div class="quantity-section">
                <h4 class="variant-title">Số lượng</h4>
                <div class="quantity-box">
                  <button class="qty-btn" @click="quantity = Math.max(1, quantity - 1)">−</button>
                  <input type="number" v-model.number="quantity" min="1" :max="maxQuantity" class="qty-input" />
                  <button class="qty-btn" @click="quantity = Math.min(maxQuantity, quantity + 1)">+</button>
                </div>
                <span class="stock-info">Còn {{ selectedVariant?.stock || product.stock_total || 0 }} sản phẩm</span>
              </div>

              <!-- Actions -->
              <div class="action-buttons">
                <button class="btn-add-cart" @click="addToCart">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="8" cy="21" r="1"/>
                      <circle cx="19" cy="21" r="1"/>
                      <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                  </span>
                  Thêm vào giỏ hàng
                </button>
                <button class="btn-buy-now" @click="buyNow">Mua ngay</button>
              </div>

              <!-- Features -->
              <div class="features-list">
                <div class="feature-item">
                  <span class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                  </span>
                  <span>Hỗ trợ MagSafe - Sạc không dây</span>
                </div>
                <!-- Giữ nguyên các feature khác -->
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Tabs Section -->
      <section class="tabs-section">
        <div class="container">
          <div class="tabs-header">
            <button
              class="tab-btn"
              :class="{ active: activeTab === 'description' }"
              @click="activeTab = 'description'"
            >
              Mô tả sản phẩm
            </button>
            <button
              class="tab-btn"
              :class="{ active: activeTab === 'specs' }"
              @click="activeTab = 'specs'"
            >
              Thông số kỹ thuật
            </button>
            <button
              class="tab-btn"
              :class="{ active: activeTab === 'reviews' }"
              @click="activeTab = 'reviews'"
            >
              Đánh giá ({{ product?.comments?.length || 0 }})
            </button>
          </div>

          <div class="tab-content">
            <!-- Mô tả sản phẩm -->
            <div v-if="activeTab === 'description'" class="tab-pane">
              <h3>{{ product?.name }}</h3>
              <p v-html="product?.description || product?.short_desc || 'Không có mô tả chi tiết.'"></p>

              <h4>Đặc điểm nổi bật:</h4>
              <ul>
                <li v-for="(feature, i) in productFeatures" :key="i">{{ feature }}</li>
              </ul>
            </div>

            <!-- Thông số kỹ thuật -->
            <div v-if="activeTab === 'specs'" class="tab-pane">
              <h3>Thông số kỹ thuật</h3>
              <ul>
                <li v-for="(spec, i) in specifications" :key="i">{{ spec }}</li>
              </ul>
              <p v-if="!specifications.length">Chưa có thông số kỹ thuật chi tiết.</p>
            </div>

            <!-- Đánh giá -->
            <div v-if="activeTab === 'reviews'" class="tab-pane">
              <h3>Đánh giá từ khách hàng</h3>

              <div class="reviews-summary">
                <div class="rating-overview">
                  <span class="big-rating">{{ averageRating.toFixed(1) }}</span>
                  <div class="rating-details">
                    <span class="stars-big">★★★★★</span>
                    <span class="total-reviews">{{ product.comments.length }} đánh giá</span>
                  </div>
                </div>
              </div>

              <div class="reviews-list" v-if="product.comments.length">
                <div v-for="comment in paginatedComments" :key="comment.comment_id" class="review-card">
                  <div class="review-header">
                    <img
                      :src="comment.user?.avatar || 'https://via.placeholder.com/50?text=' + (comment.user?.full_name?.charAt(0) || 'K')"
                      alt="Avatar"
                      class="reviewer-avatar"
                    />
                    <div class="reviewer-info">
                      <h4 class="reviewer-name">{{ comment.user?.full_name || 'Khách hàng' }}</h4>
                      <div class="review-meta">
                        <span class="stars">★★★★★</span>
                        <span class="review-date">{{ formatDate(comment.created_at) }}</span>
                      </div>
                    </div>
                  </div>
                  <p class="review-content">{{ comment.content }}</p>
                  <div class="review-images" v-if="comment.images?.length">
                    <img v-for="(img, i) in comment.images" :key="i" :src="img" alt="Hình đánh giá" />
                  </div>
                </div>
              </div>
              <p v-else>Chưa có đánh giá nào.</p>

              <!-- Phân trang bình luận -->
              <div class="comment-pagination" v-if="totalCommentPages > 1">
                <button @click="currentCommentPage = Math.max(1, currentCommentPage - 1)" :disabled="currentCommentPage === 1">Trước</button>
                <span>Trang {{ currentCommentPage }} / {{ totalCommentPages }}</span>
                <button @click="currentCommentPage = Math.min(totalCommentPages, currentCommentPage + 1)" :disabled="currentCommentPage === totalCommentPages">Sau</button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Related Products -->
      <section class="related-section">
        <div class="container">
          <h2 class="section-title">Sản phẩm liên quan</h2>
          <div class="related-grid">
            <router-link
              v-for="related in relatedProducts"
              :key="related.product_id"
              :to="`/products/${related.slug}`"
              class="related-card-link"
            >
              <article class="product-card">
                <span v-if="related.is_featured" class="product-badge badge-new">New</span>
                <div class="product-image-wrapper">
                  <img
                    :src="related.variants?.[0]?.image_urls?.[0] || 'https://via.placeholder.com/400?text=' + encodeURIComponent(related.name)"
                    :alt="related.name"
                    class="product-image"
                  />
                </div>
                <div class="product-card-info">
                  <h3 class="product-name">{{ related.name }}</h3>
                  <div class="product-price">
                    <span class="current-price">{{ formatPrice(related.discount_price || related.base_price) }}đ</span>
                  </div>
                  <div class="product-rating"><span class="stars">★★★★★</span><span class="count">(45)</span></div>
                </div>
              </article>
            </router-link>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api'

const route = useRoute()
const router = useRouter()

const product = ref(null)
const relatedProducts = ref([])
const loading = ref(true)
const error = ref(null)
const selectedVariant = ref(null)
const selectedImage = ref(null)
const quantity = ref(1)

// Tab active
const activeTab = ref('description')

// Pagination bình luận
const currentCommentPage = ref(1)
const commentsPerPage = 5

const paginatedComments = computed(() => {
  if (!product.value?.comments?.length) return []
  const start = (currentCommentPage.value - 1) * commentsPerPage
  return product.value.comments.slice(start, start + commentsPerPage)
})

const totalCommentPages = computed(() => {
  return product.value?.comments ? Math.ceil(product.value.comments.length / commentsPerPage) : 1
})

// Computed
const images = computed(() => selectedVariant.value?.image_urls || product.value?.variants?.[0]?.image_urls || [])

const discountPercent = computed(() => {
  const base = selectedVariant.value?.base_price || product.value?.base_price
  const final = selectedVariant.value?.discount_price || product.value?.discount_price || product.value?.base_price
  return base && final && base > final ? Math.round((base - final) / base * 100) : 0
})

const averageRating = computed(() => {
  if (!product.value?.comments?.length) return 0
  const sum = product.value.comments.reduce((acc, c) => acc + (Number(c.rating) || 5), 0)
  return sum / product.value.comments.length
})

const maxQuantity = computed(() => selectedVariant.value?.stock || product.value?.stock_total || 999)

const productFeatures = computed(() => {
  return product.value?.features?.split('\n').filter(f => f.trim()) || [
    'Chất liệu TPU cao cấp, độ bền cao, chống trầy xước',
    'Thiết kế slim fit, không làm dày máy',
    'Gờ camera nổi bảo vệ cụm camera khỏi trầy xước',
    '4 góc chống sốc, bảo vệ máy khi rơi',
    'Tương thích hoàn toàn với MagSafe và các phụ kiện từ tính',
    'Dễ dàng lắp đặt và tháo gỡ'
  ]
})

// Thông số kỹ thuật (hard-code tạm, bạn có thể thêm trường specifications vào DB)
const specifications = [
  'Chất liệu: TPU cao cấp + Polycarbonate',
  'Tương thích: iPhone 15 Pro Max',
  'Công nghệ: MagSafe tích hợp',
  'Chống sốc: 4 góc chống va đập',
  'Bảo vệ camera: Gờ nổi cao',
  'Trọng lượng: 35g',
  'Độ dày: 1.5mm'
]

// Methods
const selectVariant = (variant) => {
  selectedVariant.value = variant
  selectedImage.value = variant.image_urls?.[0] || images.value[0]
}

const formatPrice = (price) => {
  if (!price) return '0'
  return new Intl.NumberFormat('vi-VN').format(Math.round(price))
}

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const addToCart = async () => {
  if (!selectedVariant.value?.variant_id) return alert('Vui lòng chọn biến thể')
  if (quantity.value > maxQuantity.value) return alert('Số lượng vượt quá tồn kho')

  try {
    await api.post('/cart/add', {
      variant_id: selectedVariant.value.variant_id,
      quantity: quantity.value
    })
    alert('Đã thêm vào giỏ hàng!')
  } catch (err) {
    console.error('Lỗi thêm giỏ hàng:', err)
    alert(err.response?.data?.message || 'Lỗi thêm vào giỏ hàng')
  }
}

const buyNow = async () => {
  await addToCart()
  router.push('/cart') // hoặc trang checkout
}

// Fetch data
onMounted(async () => {
  const slug = route.params.slug
  if (!slug) {
    error.value = 'Không tìm thấy sản phẩm'
    return router.push('/products')
  }

  try {
    loading.value = true
    const res = await api.get(`/products/${slug}`)
    product.value = res.data

    if (!product.value) throw new Error('Sản phẩm không tồn tại')

    if (product.value.variants?.length) {
      selectVariant(product.value.variants[0])
    }

    if (product.value.category_id) {
      const resRelated = await api.get('/products', {
        params: { category_id: product.value.category_id, per_page: 8 }
      })
      relatedProducts.value = (resRelated.data.data || resRelated.data).filter(p => p.product_id !== product.value.product_id)
    }
  } catch (err) {
    console.error('Lỗi tải chi tiết sản phẩm:', err)
    error.value = err.response?.data?.message || 'Không thể tải sản phẩm. Vui lòng thử lại sau.'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
/* Giữ nguyên style cũ của bạn, chỉ thêm class hỗ trợ loading/error */
.loading-container, .error-container {
  text-align: center;
  padding: 100px 20px;
  font-size: 1.2rem;
  color: #64748b;
}
.error-container a {
  margin-top: 20px;
  display: inline-block;
  padding: 10px 20px;
  background: #ff6b35;
  color: white;
  text-decoration: none;
  border-radius: 8px;
}
.tab-btn {
  padding: 12px 24px;
  border: none;
  background: #f1f5f9;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
}

.tab-btn.active {
  background: #0f172a;
  color: white;
  border-radius: 8px 8px 0 0;
}

.tab-content {
  padding: 32px 32px;
  background: white;
  border-radius: 0 0 12px 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}

.tab-pane {
  animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Pagination bình luận */
.comment-pagination {
  margin-top: 20px;
  text-align: center;
}

.comment-pagination button {
  padding: 8px 16px;
  margin: 0 8px;
  border: 1px solid #cbd5e1;
  background: white;
  cursor: pointer;
  border-radius: 6px;
}

.comment-pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>

            