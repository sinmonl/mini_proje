<template>
  <div v-if="show" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <button class="close-btn" @click="closeModal">×</button>
      
      <div class="modal-body" v-if="product">
        <div class="product-image">
          <img :src="product.image" :alt="product.name" @error="handleImageError">
        </div>
        
        <div class="product-details">
          <h2 class="product-name">{{ product.name }}</h2>
          
          <div class="product-rating" v-if="product.rating">
            <div class="stars">
              <span 
                v-for="star in 5" 
                :key="star" 
                class="star"
                :class="{ filled: star <= Math.floor(product.rating) }"
              >★</span>
            </div>
            <span class="rating-text">({{ product.rating }})</span>
          </div>
          
          <div class="product-price">
            <span v-if="product.oldPrice" class="old-price">{{ formatPrice(product.oldPrice) }}</span>
            <span class="current-price">{{ formatPrice(product.price) }}</span>
          </div>
          
          <p class="product-description">{{ product.description }}</p>
          
          <div class="stock-status" :class="{ 'in-stock': product.inStock }">
            {{ product.inStock ? 'Stokta Var' : 'Stokta Yok' }}
          </div>
          
          <div class="action-buttons">
            <button 
              class="add-to-cart-btn" 
              @click="addToCart"
              :disabled="!product.inStock"
            >
              {{ product.inStock ? 'Sepete Ekle' : 'Stokta Yok' }}
            </button>
            
            <button 
              class="favorite-btn" 
              @click="toggleFavorite"
              :class="{ active: isFavorite }"
            >
              {{ isFavorite ? '❤️' : '🤍' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QuickViewModal',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    product: {
      type: Object,
      default: null
    },
    favorites: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    isFavorite() {
      return this.product && this.favorites.includes(this.product.id)
    }
  },
  methods: {
    closeModal() {
      this.$emit('close')
    },
    addToCart() {
      if (this.product && this.product.inStock) {
        this.$emit('add-to-cart', this.product)
      }
    },
    toggleFavorite() {
      if (this.product) {
        this.$emit('toggle-favorite', this.product.id)
      }
    },
    formatPrice(price) {
      return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
      }).format(price)
    },
    handleImageError(event) {
      event.target.src = 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Görsel+Yok'
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 12px;
  max-width: 900px;
  width: 100%;
  position: relative;
  max-height: 90vh;
  overflow: hidden;
}

.close-btn {
  position: absolute;
  top: 15px;
  right: 15px;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
  z-index: 1;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.close-btn:hover {
  background: #f1f1f1;
  color: #333;
}

.modal-body {
  display: flex;
  gap: 30px;
  padding: 30px;
  max-height: 90vh;
  overflow-y: auto;
}

@media (max-width: 768px) {
  .modal-body {
    flex-direction: column;
    gap: 20px;
    padding: 20px;
  }
}

.product-image {
  flex: 1;
  max-width: 400px;
  border-radius: 8px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.product-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.product-name {
  font-size: 1.8rem;
  color: #2c3e50;
  margin: 0;
}

.product-rating {
  display: flex;
  align-items: center;
  gap: 8px;
}

.stars {
  display: flex;
  gap: 2px;
}

.star {
  color: #ddd;
  font-size: 1.2rem;
}

.star.filled {
  color: #f39c12;
}

.rating-text {
  color: #7f8c8d;
  font-size: 0.9rem;
}

.product-price {
  display: flex;
  align-items: center;
  gap: 10px;
}

.old-price {
  color: #95a5a6;
  text-decoration: line-through;
  font-size: 1.1rem;
}

.current-price {
  color: #2c3e50;
  font-size: 1.8rem;
  font-weight: bold;
}

.product-description {
  color: #34495e;
  line-height: 1.6;
  font-size: 1rem;
}

.stock-status {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
  background: #e74c3c;
  color: white;
}

.stock-status.in-stock {
  background: #42b983;
}

.action-buttons {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}

.add-to-cart-btn {
  flex: 1;
  background: #42b983;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.add-to-cart-btn:hover:not(:disabled) {
  background: #369970;
  transform: translateY(-2px);
}

.add-to-cart-btn:disabled {
  background: #bdc3c7;
  cursor: not-allowed;
}

.favorite-btn {
  background: none;
  border: 2px solid #e1e5e9;
  width: 48px;
  height: 48px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
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
</style> 