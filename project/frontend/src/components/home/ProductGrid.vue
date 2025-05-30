<template>
  <div class="products-section">
    <div class="section-header">
      <h2 class="section-title">{{ title }}</h2>
      <p v-if="subtitle" class="section-subtitle">{{ subtitle }}</p>
      
      <div class="section-controls">
        <div class="view-controls">
          <button 
            class="view-btn" 
            :class="{ active: viewMode === 'grid' }"
            @click="setViewMode('grid')"
          >
            <span class="grid-icon">⊞</span>
          </button>
          <button 
            class="view-btn" 
            :class="{ active: viewMode === 'list' }"
            @click="setViewMode('list')"
          >
            <span class="list-icon">☰</span>
          </button>
        </div>

        <div class="sort-controls">
          <select v-model="sortBy" @change="sortProducts" class="sort-select">
            <option value="default">Varsayılan Sıralama</option>
            <option value="name-asc">İsme Göre (A-Z)</option>
            <option value="name-desc">İsme Göre (Z-A)</option>
            <option value="price-asc">Fiyata Göre (Düşük-Yüksek)</option>
            <option value="price-desc">Fiyata Göre (Yüksek-Düşük)</option>
            <option value="rating-desc">Değerlendirmeye Göre</option>
          </select>
        </div>
      </div>
    </div>

    <div class="filter-bar" v-if="showFilters">
      <div class="active-filters">
        <span v-if="activeFilters.length > 0" class="filter-label">Aktif Filtreler:</span>
        <span 
          v-for="filter in activeFilters" 
          :key="filter.key"
          class="filter-tag"
          @click="removeFilter(filter.key)"
        >
          {{ filter.label }} ×
        </span>
        <button 
          v-if="activeFilters.length > 0" 
          class="clear-filters-btn"
          @click="clearAllFilters"
        >
          Tümünü Temizle
        </button>
      </div>
    </div>

    <div class="products-container">
      <div v-if="loading" class="loading-state">
        <div class="loading-spinner"></div>
        <p>Ürünler yükleniyor...</p>
      </div>

      <div v-else-if="filteredProducts.length === 0" class="empty-state">
        <div class="empty-icon">📦</div>
        <h3>Ürün bulunamadı</h3>
        <p>Aradığınız kriterlere uygun ürün bulunmuyor.</p>
        <button class="reset-btn" @click="resetFilters">Filtreleri Sıfırla</button>
      </div>

      <div 
        v-else 
        class="product-grid" 
        :class="{ 
          'grid-view': viewMode === 'grid',
          'list-view': viewMode === 'list'
        }"
      >
        <ProductCard
          v-for="product in paginatedProducts"
          :key="product.id"
          :product="product"
          :favorites="favorites"
          @product-click="handleProductClick"
          @add-to-cart="handleAddToCart"
          @toggle-favorite="handleToggleFavorite"
          @quick-view="handleQuickView"
        />
      </div>

      <div v-if="totalPages > 1" class="pagination">
        <button 
          class="pagination-btn"
          :disabled="currentPage === 1"
          @click="goToPage(currentPage - 1)"
        >
          ← Önceki
        </button>
        
        <div class="pagination-numbers">
          <button
            v-for="page in visiblePages"
            :key="page"
            class="pagination-number"
            :class="{ active: page === currentPage }"
            @click="goToPage(page)"
          >
            {{ page }}
          </button>
        </div>

        <button 
          class="pagination-btn"
          :disabled="currentPage === totalPages"
          @click="goToPage(currentPage + 1)"
        >
          Sonraki →
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import ProductCard from './ProductCard.vue'

export default {
  name: 'ProductGrid',
  components: {
    ProductCard
  },
  props: {
    products: {
      type: Array,
      default: () => []
    },
    title: {
      type: String,
      default: 'Ürünlerimiz'
    },
    subtitle: {
      type: String,
      default: ''
    },
    loading: {
      type: Boolean,
      default: false
    },
    showFilters: {
      type: Boolean,
      default: true
    },
    itemsPerPage: {
      type: Number,
      default: 12
    },
    favorites: {
      type: Array,
      default: () => []
    }
  },
  emits: ['product-click', 'add-to-cart', 'toggle-favorite', 'quick-view'],
  data() {
    return {
      viewMode: 'grid',
      sortBy: 'default',
      currentPage: 1,
      activeFilters: [],
      sortedProducts: [...this.products]
    }
  },
  computed: {
    filteredProducts() {
      return this.sortedProducts
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredProducts.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.itemsPerPage)
    },
    visiblePages() {
      const pages = []
      const total = this.totalPages
      const current = this.currentPage
      
      if (total <= 7) {
        for (let i = 1; i <= total; i++) {
          pages.push(i)
        }
      } else {
        if (current <= 4) {
          for (let i = 1; i <= 5; i++) {
            pages.push(i)
          }
          pages.push('...')
          pages.push(total)
        } else if (current >= total - 3) {
          pages.push(1)
          pages.push('...')
          for (let i = total - 4; i <= total; i++) {
            pages.push(i)
          }
        } else {
          pages.push(1)
          pages.push('...')
          for (let i = current - 1; i <= current + 1; i++) {
            pages.push(i)
          }
          pages.push('...')
          pages.push(total)
        }
      }
      
      return pages
    }
  },
  methods: {
    setViewMode(mode) {
      this.viewMode = mode
    },
    sortProducts() {
      const products = [...this.products]
      
      switch (this.sortBy) {
        case 'name-asc':
          products.sort((a, b) => a.name.localeCompare(b.name))
          break
        case 'name-desc':
          products.sort((a, b) => b.name.localeCompare(a.name))
          break
        case 'price-asc':
          products.sort((a, b) => a.price - b.price)
          break
        case 'price-desc':
          products.sort((a, b) => b.price - a.price)
          break
        case 'rating-desc':
          products.sort((a, b) => (b.rating || 0) - (a.rating || 0))
          break
        default:
          
          break
      }
      
      this.sortedProducts = products
      this.currentPage = 1
    },
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page
        this.scrollToTop()
      }
    },
    scrollToTop() {
      this.$el.scrollIntoView({ behavior: 'smooth' })
    },
    removeFilter(filterKey) {
      this.activeFilters = this.activeFilters.filter(f => f.key !== filterKey)
    },
    clearAllFilters() {
      this.activeFilters = []
    },
    resetFilters() {
      this.activeFilters = []
      this.sortBy = 'default'
      this.currentPage = 1
      this.sortProducts()
    },
    handleProductClick(product) {
      this.$emit('product-click', product)
    },
    handleAddToCart(product) {
      this.$emit('add-to-cart', product)
    },
    handleToggleFavorite(productId) {
      this.$emit('toggle-favorite', productId)
    },
    handleQuickView(product) {
      this.$emit('quick-view', product)
    }
  },
  watch: {
    products: {
      handler() {
        this.sortProducts()
      },
      immediate: true
    }
  }
}
</script>

<style scoped>
.products-section {
  padding: 40px 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin-bottom: 40px;
}

.section-title {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 10px;
  font-weight: 700;
}

.section-subtitle {
  font-size: 1.1rem;
  color: #7f8c8d;
  margin-bottom: 30px;
}

.section-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.view-controls {
  display: flex;
  gap: 5px;
  border: 1px solid #e1e5e9;
  border-radius: 8px;
  overflow: hidden;
}

.view-btn {
  background: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1.2rem;
}

.view-btn:hover {
  background: #f8f9fa;
}

.view-btn.active {
  background: #42b983;
  color: white;
}

.sort-select {
  padding: 8px 12px;
  border: 1px solid #e1e5e9;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  font-size: 0.9rem;
}

.filter-bar {
  margin-bottom: 30px;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
}

.active-filters {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.filter-label {
  font-weight: 600;
  color: #2c3e50;
}

.filter-tag {
  background: #42b983;
  color: white;
  padding: 4px 8px;
  border-radius: 15px;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.filter-tag:hover {
  background: #369970;
}

.clear-filters-btn {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.clear-filters-btn:hover {
  background: #c0392b;
}

.loading-state {
  text-align: center;
  padding: 60px 20px;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #42b983;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.empty-state h3 {
  color: #2c3e50;
  margin-bottom: 10px;
}

.empty-state p {
  color: #7f8c8d;
  margin-bottom: 20px;
}

.reset-btn {
  background: #42b983;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.reset-btn:hover {
  background: #369970;
}

.product-grid.grid-view {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
}

.product-grid.list-view {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-top: 40px;
}

.pagination-btn {
  background: white;
  border: 1px solid #e1e5e9;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.pagination-btn:hover:not(:disabled) {
  background: #42b983;
  color: white;
  border-color: #42b983;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-numbers {
  display: flex;
  gap: 5px;
}

.pagination-number {
  background: white;
  border: 1px solid #e1e5e9;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pagination-number:hover {
  background: #f8f9fa;
}

.pagination-number.active {
  background: #42b983;
  color: white;
  border-color: #42b983;
}

@media (max-width: 768px) {
  .section-title {
    font-size: 2rem;
  }

  .section-controls {
    justify-content: center;
    flex-direction: column;
  }

  .product-grid.grid-view {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
  }

  .pagination {
    flex-wrap: wrap;
  }
}

@media (max-width: 480px) {
  .products-section {
    padding: 20px 10px;
  }

  .product-grid.grid-view {
    grid-template-columns: 1fr;
  }
}
</style>