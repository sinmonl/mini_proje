<template>
  <div class="product-card" @click="handleClick">
    <div class="product-image-container">
      <img 
        :src="product.image" 
        :alt="product.name" 
        class="product-image"
        @error="handleImageError"
      >
      <div class="product-overlay">
        <button class="quick-view-btn" @click.stop="$emit('quick-view', product)">
          👁️ Hızlı Bakış
        </button>
      </div>
      <div v-if="product.badge" class="product-badge" :class="product.badge.type">
        {{ product.badge.text }}
      </div>
    </div>

    <div class="product-info">
      <h3 class="product-name">{{ product.name }}</h3>
      <p class="product-description">{{ truncatedDescription }}</p>
      
      <div class="product-rating" v-if="product.rating">
        <div class="stars">
          <span 
            v-for="star in 5" 
            :key="star" 
            class="star"
            :class="{ filled: star <= Math.floor(product.rating) }"
          >
            ★
          </span>
        </div>
        <span class="rating-text">({{ product.rating }})</span>
      </div>

      <div class="product-price">
        <span v-if="product.oldPrice" class="old-price">{{ formatPrice(product.oldPrice) }}</span>
        <span class="current-price">{{ formatPrice(product.price) }}</span>
      </div>

      <div class="product-actions">
        <button 
          class="add-to-cart-btn" 
          @click.stop="handleAddToCart"
          :disabled="!product.inStock"
        >
          {{ product.inStock ? 'Sepete Ekle' : 'Stokta Yok' }}
        </button>
        <button class="favorite-btn" @click.stop="toggleFavorite" :class="{ active: isFavorite }">
          {{ isFavorite ? '❤️' : '🤍' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCard',
  props: {
    product: {
      type: Object,
      required: true,
      validator(product) {
        return product.id && product.name && product.price
      }
    },
    favorites: {
      type: Array,
      default: () => []
    }
  },
  emits: ['product-click', 'add-to-cart', 'toggle-favorite', 'quick-view'],
  computed: {
    truncatedDescription() {
      if (!this.product.description) return ''
      return this.product.description.length > 100 
        ? this.product.description.substring(0, 100) + '...'
        : this.product.description
    },
    isFavorite() {
      return this.favorites.includes(this.product.id)
    }
  },
  methods: {
    formatPrice(price) {
      return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
      }).format(price)
    },
    handleClick() {
      this.$emit('product-click', this.product)
    },
    handleAddToCart() {
      if (this.product.inStock) {
        this.$emit('add-to-cart', this.product)
      }
    },
    toggleFavorite() {
      this.$emit('toggle-favorite', this.product.id)
    },
    handleImageError(event) {
      event.target.src = 'https://via.placeholder.com/300x200/f8f9fa/6c757d?text=Görsel+Yok'
    }
  }
}
</script>

<style scoped>
.product-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  cursor: pointer;
  overflow: hidden;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image-container {
  position: relative;
  overflow: hidden;
  height: 200px;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

.product-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
  opacity: 1;
}

.quick-view-btn {
  background: #42b983;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.quick-view-btn:hover {
  background: #369970;
  transform: scale(1.05);
}

.product-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: bold;
  color: white;
}

.product-badge.new {
  background: #42b983;
}

.product-badge.sale {
  background: #e74c3c;
}

.product-badge.featured {
  background: #f39c12;
}

.product-info {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-name {
  font-size: 1.2rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 10px;
  line-height: 1.3;
}

.product-description {
  color: #7f8c8d;
  font-size: 0.9rem;
  line-height: 1.4;
  margin-bottom: 15px;
  flex: 1;
}

.product-rating {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 15px;
}

.stars {
  display: flex;
  gap: 2px;
}

.star {
  color: #ddd;
  font-size: 1rem;
}

.star.filled {
  color: #f39c12;
}

.rating-text {
  font-size: 0.8rem;
  color: #7f8c8d;
}

.product-price {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.old-price {
  color: #95a5a6;
  text-decoration: line-through;
  font-size: 0.9rem;
}

.current-price {
  color: #2c3e50;
  font-size: 1.3rem;
  font-weight: bold;
}

.product-actions {
  display: flex;
  gap: 10px;
  align-items: center;
}

.add-to-cart-btn {
  flex: 1;
  background: #42b983;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.add-to-cart-btn:hover:not(:disabled) {
  background: #369970;
  transform: translateY(-1px);
}

.add-to-cart-btn:disabled {
  background: #bdc3c7;
  cursor: not-allowed;
}

.favorite-btn {
  background: none;
  border: 2px solid #e1e5e9;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: all 0.3s ease;
}

.favorite-btn:hover {
  border-color: #42b983;
  transform: scale(1.1);
}

.favorite-btn.active {
  border-color: #e74c3c;
  background: #ffe6e6;
}

@media (max-width: 768px) {
  .product-info {
    padding: 15px;
  }

  .product-name {
    font-size: 1.1rem;
  }

  .current-price {
    font-size: 1.2rem;
  }

  .add-to-cart-btn {
    padding: 8px 12px;
    font-size: 0.9rem;
  }

  .favorite-btn {
    width: 35px;
    height: 35px;
  }
}
</style>