<template>
  <footer class="app-footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>İletişim</h3>
          <p>
            <i class="fas fa-map-marker-alt"></i> Adres: İstanbul, Türkiye
          </p>
          <p>
            <i class="fas fa-phone"></i> Telefon: +90 (555) 123 45 67
          </p>
          <p>
            <i class="fas fa-envelope"></i> E-posta: info@example.com
          </p>
        </div>

        <div class="footer-section">
          <h3>Sosyal Medya</h3>
          <div class="social-links">
            <a href="#" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-facebook"></i>
            </a>
            <a href="#" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-linkedin"></i>
            </a>
          </div>
        </div>

        <div class="footer-section">
          <h3>Hızlı Bağlantılar</h3>
          <ul class="quick-links">
            <li><router-link to="/">Ana Sayfa</router-link></li>
            <li><router-link to="/contact">İletişim</router-link></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; {{ currentYear }} Tüm hakları saklıdır.</p>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
  computed: {
    currentYear() {
      return new Date().getFullYear()
    }
  }
}
</script>

<style scoped>
.app-footer {
  background-color: #2c3e50;
  color: white;
  padding: 3rem 0 1rem;
  margin-top: 3rem;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section h3 {
  color: #42b983;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.footer-section p {
  margin: 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.social-links {
  display: flex;
  gap: 1rem;
}

.social-links a {
  color: white;
  font-size: 1.5rem;
  transition: color 0.3s;
}

.social-links a:hover {
  color: #42b983;
}

.quick-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.quick-links li {
  margin: 0.5rem 0;
}

.quick-links a {
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}

.quick-links a:hover {
  color: #42b983;
}

.footer-bottom {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid rgba(255,255,255,0.1);
}

.footer-bottom p {
  margin: 0;
  font-size: 0.9rem;
  color: rgba(255,255,255,0.7);
}

@media (max-width: 768px) {
  .footer-content {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .footer-section p {
    justify-content: center;
  }

  .social-links {
    justify-content: center;
  }
}
</style>