<template>
  <div class="hero-slider">
    <div 
      v-for="(slide, index) in slides" 
      :key="index" 
      class="slide" 
      :class="{ active: currentSlide === index }"
    >
      <img :src="slide.image" :alt="slide.title" class="slide-image">
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <h2 class="slide-title">{{ slide.title }}</h2>
        <p class="slide-description">{{ slide.description }}</p>
        <button v-if="slide.buttonText" class="slide-button" @click="handleSlideAction(slide)">
          {{ slide.buttonText }}
        </button>
      </div>
    </div>

    <button class="slider-btn prev" @click="prevSlide" :disabled="isTransitioning">
      <span>❮</span>
    </button>
    <button class="slider-btn next" @click="nextSlide" :disabled="isTransitioning">
      <span>❯</span>
    </button>

    <div class="slider-dots">
      <button 
        v-for="(slide, index) in slides" 
        :key="index"
        class="dot" 
        :class="{ active: currentSlide === index }"
        @click="goToSlide(index)"
      ></button>
    </div>

    <div class="slider-progress">
      <div class="progress-bar" :style="{ width: progressWidth + '%' }"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HeroSlider',
  props: {
    slides: {
      type: Array,
      default: () => [
        {
          title: 'Hoş Geldiniz',
          description: 'En kaliteli ürünlerimizi keşfedin',
          image: '/images/products/gorsel-14.jpg',
          buttonText: 'Keşfet',
          action: 'explore'
        },
        {
          title: 'Özel Fırsatlar',
          description: 'Sınırlı süre için özel indirimler',
          image: '/images/products/gorsel-13.jpg',
          buttonText: 'Fırsatları Gör',
          action: 'offers'
        },
        {
          title: 'Yeni Ürünler',
          description: 'Yeni sezon ürünlerimiz sizleri bekliyor',
          image: '/images/products/gorsel-16.jpg',
          buttonText: 'Yenilikleri İncele',
          action: 'new-products'
        },
        {
          title: 'Premium Koleksiyon',
          description: 'Seçkin ürünlerimizle farkı yaşayın',
          image: '/images/products/gorsel-4.jpg',
          buttonText: 'Koleksiyonu Görüntüle',
          action: 'premium'
        }
      ]
    },
    autoplay: {
      type: Boolean,
      default: true
    },
    autoplayDelay: {
      type: Number,
      default: 5000
    }
  },
  data() {
    return {
      currentSlide: 0,
      isTransitioning: false,
      autoplayTimer: null,
      progressTimer: null,
      progressWidth: 0
    }
  },
  methods: {
    nextSlide() {
      if (this.isTransitioning) return
      this.goToSlide((this.currentSlide + 1) % this.slides.length)
    },
    prevSlide() {
      if (this.isTransitioning) return
      this.goToSlide((this.currentSlide - 1 + this.slides.length) % this.slides.length)
    },
    goToSlide(index) {
      if (this.isTransitioning || index === this.currentSlide) return
      
      this.isTransitioning = true
      this.currentSlide = index
      
      setTimeout(() => {
        this.isTransitioning = false
      }, 500)
      
      this.resetAutoplay()
      this.resetProgress()
    },
    handleSlideAction(slide) {
      this.$emit('slide-action', slide.action, slide)
    },
    startAutoplay() {
      if (!this.autoplay) return
      
      this.autoplayTimer = setInterval(() => {
        this.nextSlide()
      }, this.autoplayDelay)
      
      this.startProgress()
    },
    stopAutoplay() {
      if (this.autoplayTimer) {
        clearInterval(this.autoplayTimer)
        this.autoplayTimer = null
      }
      this.stopProgress()
    },
    resetAutoplay() {
      this.stopAutoplay()
      this.startAutoplay()
    },
    startProgress() {
      this.progressWidth = 0
      const increment = 100 / (this.autoplayDelay / 50)
      
      this.progressTimer = setInterval(() => {
        this.progressWidth += increment
        if (this.progressWidth >= 100) {
          this.progressWidth = 0
        }
      }, 50)
    },
    stopProgress() {
      if (this.progressTimer) {
        clearInterval(this.progressTimer)
        this.progressTimer = null
      }
    },
    resetProgress() {
      this.stopProgress()
      this.startProgress()
    }
  },
  mounted() {
    this.startAutoplay()
  },
  beforeUnmount() {
    this.stopAutoplay()
  }
}
</script>

<style scoped>
.hero-slider {
  position: relative;
  height: 500px;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.5s ease-in-out;
}

.slide.active {
  opacity: 1;
}

.slide-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.slide-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 100%);
}

.slide-content {
  position: absolute;
  bottom: 60px;
  left: 40px;
  right: 40px;
  color: white;
  z-index: 2;
}

.slide-title {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 15px;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
  animation: slideInLeft 0.8s ease-out;
}

.slide-description {
  font-size: 1.2rem;
  margin-bottom: 25px;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
  animation: slideInLeft 0.8s ease-out 0.2s both;
}

.slide-button {
  background: #42b983;
  color: white;
  border: none;
  padding: 12px 30px;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
  animation: slideInLeft 0.8s ease-out 0.4s both;
}

.slide-button:hover {
  background: #369970;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(66, 185, 131, 0.4);
}

.slider-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.9);
  color: #2c3e50;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.2rem;
  transition: all 0.3s ease;
  z-index: 3;
  display: flex;
  align-items: center;
  justify-content: center;
}

.slider-btn:hover:not(:disabled) {
  background: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  transform: translateY(-50%) scale(1.1);
}

.slider-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.prev {
  left: 20px;
}

.next {
  right: 20px;
}

.slider-dots {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 3;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 2px solid white;
  background: transparent;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dot.active {
  background: white;
}

.dot:hover {
  transform: scale(1.2);
}

.slider-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: rgba(255,255,255,0.3);
  z-index: 3;
}

.progress-bar {
  height: 100%;
  background: #42b983;
  transition: width 0.05s linear;
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@media (max-width: 768px) {
  .hero-slider {
    height: 400px;
  }

  .slide-content {
    bottom: 40px;
    left: 20px;
    right: 20px;
  }

  .slide-title {
    font-size: 2rem;
  }

  .slide-description {
    font-size: 1rem;
  }

  .slider-btn {
    width: 40px;
    height: 40px;
    font-size: 1rem;
  }

  .prev {
    left: 15px;
  }

  .next {
    right: 15px;
  }
}

@media (max-width: 480px) {
  .hero-slider {
    height: 300px;
    border-radius: 8px;
  }

  .slide-title {
    font-size: 1.5rem;
  }

  .slide-description {
    font-size: 0.9rem;
  }

  .slide-button {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
}
</style>