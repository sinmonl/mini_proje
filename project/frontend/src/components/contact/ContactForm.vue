<template>
  <div class="contact-form-wrapper">
    <div class="container">
      <div class="contact-header">
        <h2>İletişim Formu</h2>
        <p>Size nasıl yardımcı olabiliriz? Formu doldurarak bize ulaşabilirsiniz.</p>
      </div>

      <div class="contact-content">
        <div class="contact-info">
          <div class="info-item">
            <div class="icon">📍</div>
            <div>
              <h4>Adres</h4>
              <p>Eyüp Sultan caddesi. Ortancı sok.<br>Ümraniye/İstanbul</p>
            </div>
          </div>
          
          <div class="info-item">
            <div class="icon">📞</div>
            <div>
              <h4>Telefon</h4>
              <p>+90 332 111 111 </p>
            </div>
          </div>
          
          <div class="info-item">
            <div class="icon">✉️</div>
            <div>
              <h4>E-posta</h4>
              <p>info@example.com</p>
            </div>
          </div>
        </div>

        <form @submit.prevent="submitForm" class="contact-form" novalidate>
          <div class="form-row">
            <div class="form-group">
              <label for="name">Ad Soyad *</label>
              <input 
                type="text" 
                id="name" 
                v-model="form.name" 
                :class="{ 'error': errors.name }"
                @blur="validateField('name')"
                required
              >
              <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
            </div>

            <div class="form-group">
              <label for="phone">Telefon *</label>
              <input 
                type="tel" 
                id="phone" 
                v-model="form.phone"
                :class="{ 'error': errors.phone }"
                @blur="validateField('phone')"
                placeholder="0532 123 45 67"
                required
              >
              <span v-if="errors.phone" class="error-message">{{ errors.phone }}</span>
            </div>
          </div>

          <div class="form-group">
            <label for="email">E-posta *</label>
            <input 
              type="email" 
              id="email" 
              v-model="form.email"
              :class="{ 'error': errors.email }"
              @blur="validateField('email')"
              placeholder="ornek@email.com"
              required
            >
            <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="city">İl *</label>
              <select 
                id="city" 
                v-model="form.city" 
                @change="fetchDistricts"
                :class="{ 'error': errors.city }"
                required
              >
                <option value="">İl Seçiniz</option>
                <option v-for="city in cities" :key="city.id" :value="city.name">
                  {{ city.name }}
                </option>
              </select>
              <span v-if="errors.city" class="error-message">{{ errors.city }}</span>
            </div>

            <div class="form-group">
              <label for="district">İlçe *</label>
              <select 
                id="district" 
                v-model="form.district" 
                :disabled="!form.city"
                :class="{ 'error': errors.district }"
                required
              >
                <option value="">İlçe Seçiniz</option>
                <option v-for="district in districts" :key="district.id" :value="district.name">
                  {{ district.name }}
                </option>
              </select>
              <span v-if="errors.district" class="error-message">{{ errors.district }}</span>
            </div>
          </div>

          <div class="form-group">
            <label for="message">Mesaj *</label>
            <textarea 
              id="message" 
              v-model="form.message" 
              rows="5"
              :class="{ 'error': errors.message }"
              @blur="validateField('message')"
              placeholder="Mesajınızı buraya yazabilirsiniz..."
              required
            ></textarea>
            <span v-if="errors.message" class="error-message">{{ errors.message }}</span>
          </div>

          <div v-if="successMessage" class="success-message">
            {{ successMessage }}
          </div>
          
          <div v-if="errorMessage" class="error-message-global">
            {{ errorMessage }}
          </div>

          <button type="submit" :disabled="loading || !isFormValid" class="submit-btn">
            <span v-if="loading" class="spinner"></span>
            {{ loading ? 'Gönderiliyor...' : 'Mesaj Gönder' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactForm',
  data() {
    return {
      form: {
        name: '',
        phone: '',
        email: '',
        city: '',
        district: '',
        message: ''
      },
      cities: [],
      districts: [],
      loading: false,
      errors: {},
      successMessage: '',
      errorMessage: ''
    }
  },
  computed: {
    isFormValid() {
      return Object.keys(this.errors).length === 0 && 
             this.form.name && 
             this.form.phone && 
             this.form.email && 
             this.form.city && 
             this.form.district && 
             this.form.message
    }
  },
  methods: {
    validateField(field) {
      this.errors = { ...this.errors }
      delete this.errors[field]

      switch (field) {
        case 'name': {
          if (!this.form.name.trim()) {
            this.errors.name = 'Ad soyad gereklidir'
          } else if (this.form.name.trim().length < 2) {
            this.errors.name = 'Ad soyad en az 2 karakter olmalıdır'
          }
          break
        }
        
        case 'phone': {
          if (!this.form.phone.trim()) {
            this.errors.phone = 'Telefon numarası gereklidir'
          }
          break
        }
        
        case 'email': {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
          if (!this.form.email.trim()) {
            this.errors.email = 'E-posta adresi gereklidir'
          } else if (!emailRegex.test(this.form.email)) {
            this.errors.email = 'Geçerli bir e-posta adresi giriniz'
          }
          break
        }
        
        case 'message': {
          if (!this.form.message.trim()) {
            this.errors.message = 'Mesaj gereklidir'
          } else if (this.form.message.trim().length < 10) {
            this.errors.message = 'Mesaj en az 10 karakter olmalıdır'
          }
          break
        }
      }
    },

    validateAllFields() {
      ['name', 'phone', 'email', 'message'].forEach(field => {
        this.validateField(field)
      })
      
      if (!this.form.city) {
        this.errors.city = 'İl seçimi gereklidir'
      }
      
      if (!this.form.district) {
        this.errors.district = 'İlçe seçimi gereklidir'
      }
    },

    async fetchCities() {
      try {
        const response = await this.$axios.get('/api/contact/cities')
        console.log('Cities response:', response.data)
        
        if (response.data.success) {
          this.cities = response.data.data
        } else {
          console.error('Cities API error:', response.data.message)
          await this.fetchCitiesDirectly()
        }
      } catch (error) {
        console.error('İller yüklenirken hata oluştu:', error)
        await this.fetchCitiesDirectly()
      }
    },

    async fetchCitiesDirectly() {
      try {
        const response = await fetch('https://turkiyeapi.dev/api/v1/provinces')
        const data = await response.json()
        
        if (data.status === 'OK') {
          this.cities = data.data
        }
      } catch (error) {
        console.error('Doğrudan API çağrısında hata:', error)
      }
    },

    async fetchDistricts() {
      if (!this.form.city) return
      
      this.districts = []
      this.form.district = ''
      
      try {
        const response = await this.$axios.get(`/api/contact/districts/${this.form.city}`)
        console.log('Districts response:', response.data)
        
        if (response.data.success && response.data.data) {
          this.districts = response.data.data
        } else {
          console.error('Districts API error:', response.data.message)
          await this.fetchDistrictsDirectly()
        }
      } catch (error) {
        console.error('İlçeler yüklenirken hata oluştu:', error)
        await this.fetchDistrictsDirectly()
      }
    },

    async fetchDistrictsDirectly() {
      if (!this.form.city) return
      
      try {
        const selectedCity = this.cities.find(city => city.name === this.form.city)
        if (!selectedCity) {
          console.error('Seçilen şehir bulunamadı:', this.form.city)
          return
        }
        
        if (selectedCity.districts && selectedCity.districts.length > 0) {
          this.districts = selectedCity.districts
          return
        }
        
        const response = await fetch(`https://turkiyeapi.dev/api/v1/provinces/${selectedCity.id}/districts`)
        const data = await response.json()
        
        if (data.status === 'OK') {
          this.districts = data.data
        }
      } catch (error) {
        console.error('Doğrudan ilçe API çağrısında hata:', error)
      }
    },

    async submitForm() {
      this.validateAllFields()
      this.successMessage = ''
      this.errorMessage = ''
      
      if (!this.isFormValid) {
        this.errorMessage = 'Lütfen tüm alanları doğru şekilde doldurunuz'
        return
      }

      this.loading = true
      
      try {
        const response = await this.$axios.post('/api/contact', this.form)
        console.log('Form response:', response.data)
        
        if (response.data.success) {
          this.successMessage = 'Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.'
          this.resetForm()
        } else {
          this.errorMessage = response.data.message || 'Bir hata oluştu'
        }
      } catch (error) {
        console.error('Form gönderilirken hata oluştu:', error)
        
        if (error.response && error.response.data) {
          this.errorMessage = error.response.data.message || 'Bir hata oluştu'
          
          if (error.response.data.errors) {
            console.log('Validation errors:', error.response.data.errors)
            this.errors = { ...this.errors, ...error.response.data.errors }
          }
        } else {
          this.errorMessage = 'Bağlantı hatası. Lütfen tekrar deneyin.'
        }
      } finally {
        this.loading = false
      }
    },

    resetForm() {
      this.form = {
        name: '',
        phone: '',
        email: '',
        city: '',
        district: '',
        message: ''
      }
      this.districts = []
      this.errors = {}
    }
  },

  mounted() {
    this.fetchCities()
  }
}
</script>

<style scoped>
:root {
  --primary-color: #42b983;
  --primary-dark: #3aa876;
  --secondary-color: #2c3e50;
  --light-gray: #f8f9fa;
  --shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
}

.contact-form-wrapper {
  background: var(--light-gray);
  padding: 60px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.contact-header {
  text-align: center;
  margin-bottom: 50px;
}

.contact-header h2 {
  font-size: 2.5rem;
  color: var(--secondary-color);
  margin-bottom: 1rem;
}

.contact-header p {
  font-size: 1.1rem;
  color: #666;
  max-width: 600px;
  margin: 0 auto;
}

.contact-content {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 50px;
  align-items: start;
}

.contact-info {
  background: white;
  padding: 40px;
  border-radius: 15px;
  box-shadow: var(--shadow);
  height: fit-content;
}

.info-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  margin-bottom: 30px;
}

.info-item:last-child {
  margin-bottom: 0;
}

.icon {
  font-size: 1.5rem;
  width: 40px;
  height: 40px;
  background: var(--primary-color);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.info-item h4 {
  color: var(--secondary-color);
  margin-bottom: 5px;
  font-size: 1.1rem;
}

.info-item p {
  color: #666;
  line-height: 1.5;
}

.contact-form {
  background: white;
  padding: 40px;
  border-radius: 15px;
  box-shadow: var(--shadow);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: var(--secondary-color);
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #e1e5e9;
  border-radius: 8px;
  font-size: 1rem;
  transition: var(--transition);
  background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(66, 185, 131, 0.1);
}

.form-group input.error,
.form-group select.error,
.form-group textarea.error {
  border-color: #e74c3c;
}

.form-group select:disabled {
  background-color: #f8f9fa;
  color: #6c757d;
  cursor: not-allowed;
}

.form-group textarea {
  resize: vertical;
  min-height: 120px;
}

.error-message {
  color: #e74c3c;
  font-size: 0.875rem;
  margin-top: 5px;
  display: block;
}

.success-message {
  background: #d4edda;
  color: #155724;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  border-left: 4px solid #28a745;
}

.error-message-global {
  background: #f8d7da;
  color: #721c24;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  border-left: 4px solid #dc3545;
}

.submit-btn {
  background: #42b983;
  color: white;
  padding: 15px 40px;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  font-size: 1.1rem;
  font-weight: 600;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  min-width: 200px;
}

.submit-btn:hover:not(:disabled) {
  background: #3aa876;
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(66, 185, 131, 0.3);
}

.submit-btn:disabled {
  background: #a8d5c2;
  cursor: not-allowed;
  transform: none;
}

.spinner {
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Mobile Responsive */
@media (max-width: 968px) {
  .contact-content {
    grid-template-columns: 1fr;
    gap: 30px;
  }
}

@media (max-width: 768px) {
  .contact-form-wrapper {
    padding: 40px 0;
  }

  .contact-header h2 {
    font-size: 2rem;
  }

  .contact-info,
  .contact-form {
    padding: 25px;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .info-item {
    margin-bottom: 25px;
  }

  .submit-btn {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .contact-header h2 {
    font-size: 1.75rem;
  }

  .contact-info,
  .contact-form {
    padding: 20px;
  }
}
</style>