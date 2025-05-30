<template>
  <div class="home">
    <!-- Slider -->
    <HeroSlider />

    <!-- Products -->
    <div class="products container">
      <h2 class="section-title">Ürünlerimiz</h2>
      <div class="product-grid">
        <ProductCard
          v-for="product in products"
          :key="product.id"
          :product="product"
          :favorites="favorites"
          @product-click="handleProductClick"
          @add-to-cart="handleAddToCart"
          @toggle-favorite="handleToggleFavorite"
          @quick-view="handleQuickView"
        />
      </div>
    </div>

    <!-- Quick View Modal -->
    <QuickViewModal
      :show="showQuickView"
      :product="selectedProduct"
      :favorites="favorites"
      @close="closeQuickView"
      @add-to-cart="handleAddToCart"
      @toggle-favorite="handleToggleFavorite"
    />
  </div>
</template>

<script>
import axios from 'axios'
import HeroSlider from '@/components/home/HeroSlider.vue'
import ProductCard from '@/components/home/ProductCard.vue'
import QuickViewModal from '@/components/home/QuickViewModal.vue'

export default {
  name: 'HomePage',
  components: {
    HeroSlider,
    ProductCard,
    QuickViewModal
  },
  data() {
    return {
      products: [
        {
          id: 1,
          name: 'Ürün 1',
          description: 'Kaliteli ürün açıklaması',
          price: 299,
          image: '/images/products/gorsel-13.jpg',
          rating: 4.5,
          inStock: true,
          badge: { type: 'new', text: 'Yeni' }
        },
        {
          id: 2,
          name: 'Ürün 2',
          description: 'Özel tasarım ürün',
          price: 399,
          oldPrice: 499,
          image: '/images/products/gorsel-14.jpg',
          rating: 4.0,
          inStock: true,
          badge: { type: 'sale', text: 'İndirim' }
        },
        {
          id: 3,
          name: 'Ürün 3',
          description: 'Premium kalite',
          price: 499,
          image: '/images/products/gorsel-15.jpg',
          rating: 5.0,
          inStock: true,
          badge: { type: 'featured', text: 'Öne Çıkan' }
        }
      ],
      favorites: [], 
      showQuickView: false,
      selectedProduct: null
    }
  },
  methods: {
    async fetchProducts() {
      console.log('API çağrısı başlatılıyor...')
      try {
        const response = await axios.get('/api/products')
        console.log('API yanıtı başarılı:', response.data)
        this.products = response.data.data 
        console.log('Güncel ürün listesi:', this.products)
      } catch (error) {
        console.error('API Hatası:', {
          message: error.message,
          status: error.response?.status,
          statusText: error.response?.statusText,
          data: error.response?.data
        })
      }
    },
    handleProductClick(product) {
      console.log(product)
     
    },
    handleAddToCart(product) {
      console.log('Sepete eklendi:', product)
    },
    handleToggleFavorite(productId) {
      const index = this.favorites.indexOf(productId)
      if (index === -1) {
        this.favorites.push(productId)
      } else {
        this.favorites.splice(index, 1)
      }
      console.log('Favoriler güncellendi:', this.favorites)
    },
    handleQuickView(product) {
      this.selectedProduct = product
      this.showQuickView = true
    },
    closeQuickView() {
      this.showQuickView = false
      this.selectedProduct = null
    }
  },
  mounted() {
    this.fetchProducts()
  }
}
</script>

<style scoped>
.home {
  padding-bottom: 3rem;
}

.section-title {
  text-align: center;
  margin: 2rem 0;
  color: #2c3e50;
  font-size: 2rem;
  font-weight: 600;
}

.products {
  margin-top: 3rem;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  padding: 1rem;
}

@media (max-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
  }
}
</style>