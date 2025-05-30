# Vue.js & Laravel Full Stack Proje

Bu proje, Vue.js frontend ve Laravel backend kullanılarak geliştirilmiş full stack bir web uygulamasıdır. Proje, iller/ilçeler API entegrasyonu ve ürün yönetimi özelliklerini içermektedir.

## Proje Özellikleri

### Frontend (Vue.js)
- **Modern Vue.js** framework'ü ile geliştirilmiş responsive tasarım
- **Vue Router** ile sayfa yönetimi
- **Component tabanlı** yapı
- **Axios** ile API entegrasyonu
- **İl/İlçe seçimi** için dinamik dropdown'lar
- **Ürün listesi** ve filtreleme özellikleri
- **Favori ürünler** sistemi
- **Quick View Modal** ile hızlı ürün görüntüleme

### Backend (Laravel)
- **Laravel** framework'ü ile RESTful API
- **Türkiye API** entegrasyonu ile güncel il/ilçe verileri
- **Database migrations** ile veritabanı yönetimi
- **Exception handling** ile hata yönetimi
- **Validation** ile veri doğrulama
- **Contact** ve **Product** modülleri

##  Proje Yapısı

```
project/
├── frontend/          # Vue.js Frontend
│   ├── src/
│   │   ├── components/
│   │   │   ├── contact/
│   │   │   │   └── ContactForm.vue
│   │   │   └── home/
│   │   │       ├── HeroSlider.vue
│   │   │       ├── ProductCard.vue
│   │   │       └── QuickViewModal.vue
│   │   ├── views/
│   │   └── router/
│   └── package.json
│
└── backend/           # Laravel Backend
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── ContactController.php
    │   │   └── ProductController.php
    │   └── Models/
    ├── routes/api.php
    └── composer.json
```

## 🛠️ Kurulum

### Gereksinimler

**Frontend için:**
- Node.js (v14 veya üzeri)
- npm veya yarn

**Backend için:**
- PHP 8.0 veya üzeri
- Composer
- MySQL/PostgreSQL
- Laravel CLI

### Frontend Kurulumu

```bash
cd project/frontend
npm install
npm run serve
```

Frontend çalışma adresi: `http://localhost:8080`

### Backend Kurulumu

```bash
cd project/backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --port=8001
```

Backend çalışma adresi: `http://localhost:8001`

## 🔗 API Endpoints

### İletişim API'leri
```
GET  /api/contact/cities                    # İlleri getir
GET  /api/contact/districts/{cityName}      # İlçeleri getir
POST /api/contact                           # İletişim formu gönder
```

### Ürün API'leri
```
GET  /api/products                          # Ürünleri getir
```

### Test API'si
```
GET  /api/test                              # API bağlantı testi
```

##  API Entegrasyonları

### Türkiye API
Proje, güncel il ve ilçe verilerini almak için **Türkiye API** (https://turkiyeapi.dev) kullanmaktadır:

- **İller**: `https://turkiyeapi.dev/api/v1/provinces`
- **İlçeler**: `https://turkiyeapi.dev/api/v1/provinces/{id}/districts`

### Frontend API Çağrıları
Frontend tarafında, API'lerin ulaşılamaz olma durumuna karşı JavaScript kodu ile fallback mekanizması bulunmaktadır:

```javascript
// Ürünleri çekme
async fetchProducts() {
  try {
    const response = await axios.get('/api/products')
    this.products = response.data.data
  } catch (error) {
    console.error('API Hatası:', error)
    // Fallback: Manuel ürün listesi kullanılır
  }
}
```

##  Özellik Detayları

### İl/İlçe Seçimi
- **Dinamik yükleme**: İller API'den çekilir
- **Cascade dropdown**: İl seçimine göre ilçeler yüklenir
- **Hata yönetimi**: API erişim hatalarında kullanıcı bilgilendirilir
- **URL encoding**: Türkçe karakter desteği

### Ürün Yönetimi
- **Backend'de manuel tanımlama**: Ürünler Laravel backend'inde statik olarak tanımlanmıştır
- **API route entegrasyonu**: Frontend, ürünleri API üzerinden çeker
- **Fallback sistemi**: API erişim hatalarında manuel ürün listesi kullanılır
- **Favori sistemi**: Kullanıcılar ürünleri favorilere ekleyebilir
- **Quick View**: Ürünleri popup'ta hızlı görüntüleme

### İletişim Formu
- **Validation**: Hem frontend hem backend tarafında doğrulama
- **Database kayıt**: Gelen mesajlar veritabanına kaydedilir
- **Error handling**: Kapsamlı hata yönetimi

##  Geliştirme Ortamı

Bu proje **Docker** ortamında geliştirilmiştir. Docker ile çalıştırmak için:

```bash
# Docker container'ları başlat
docker-compose up -d

# Frontend container'ına bağlan
docker exec -it frontend_container npm run serve

# Backend container'ına bağlan
docker exec -it backend_container php artisan serve
```

##  Konfigürasyon

### Backend (.env) Ayarları
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# API URL'leri
TURKIYE_API_URL=https://turkiyeapi.dev/api/v1
```

### Frontend Axios Konfigürasyonu
```javascript
// Frontend'de API base URL ayarı
axios.defaults.baseURL = 'http://localhost:8001'
```

##  Önemli Notlar

- **Port Ayarları**: Frontend (8080) ve Backend (8001) farklı portlarda çalışır
- **CORS**: Backend'de CORS ayarları yapılmalıdır
- **API Fallback**: Dış API'lerin erişilemez olma durumuna karşı fallback mekanizmaları mevcuttur
- **Veritabanı**: `.env` dosyasında veritabanı bağlantı ayarları yapılmalıdır
- **Error Logging**: Tüm API hataları Laravel log sistemine kaydedilir

##  Test Etme

### API Testleri
```bash
# Test endpoint'ini kontrol et
curl http://localhost:8001/api/test

# İlleri getir
curl http://localhost:8001/api/contact/cities

# İstanbul'un ilçelerini getir
curl http://localhost:8001/api/contact/districts/İstanbul
```

### Frontend Testleri
```bash
cd project/frontend
npm run test
```


![07330c45-3a2d-453c-a2e9-88920f3714e8](https://github.com/user-attachments/assets/927b2901-34c3-4564-8167-f1b1c068081e)
![8a3c716a-8ebd-45bc-81c1-00b36449a799](https://github.com/user-attachments/assets/3c43cd8e-f476-4295-baf9-a07f645863a4)
![57d7e230-6896-49d4-9264-1c00ce906fdc](https://github.com/user-attachments/assets/a0784a2f-c170-4c47-a5c8-d538773597eb)
