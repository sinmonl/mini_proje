<template>
  <header class="app-header">
    <div class="container">
      <div class="logo">
        <router-link to="/">
          <h1>Logo</h1>
        </router-link>
      </div>

      <button class="menu-toggle" @click="toggleMenu" :class="{ active: isMenuOpen }">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav class="main-nav" :class="{ active: isMenuOpen }">
        <router-link to="/" @click="closeMenu">Ana Sayfa</router-link>
        <router-link to="/contact" @click="closeMenu">İletişim</router-link>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  data() {
    return {
      isMenuOpen: false
    }
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen
    },
    closeMenu() {
      this.isMenuOpen = false
    }
  }
}
</script>

<style scoped>
.app-header {
  background: white;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  z-index: 2;
}

.logo a {
  text-decoration: none;
  color: #42b983;
}

.logo h1 {
  margin: 0;
  font-size: 1.5rem;
}

.main-nav {
  display: flex;
  gap: 2rem;
}

.main-nav a {
  text-decoration: none;
  color: #2c3e50;
  font-weight: 500;
  transition: color 0.3s;
}

.main-nav a:hover,
.main-nav a.router-link-active {
  color: #42b983;
}

.menu-toggle {
  display: none;
  flex-direction: column;
  justify-content: space-between;
  width: 30px;
  height: 21px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  z-index: 2;
}

.menu-toggle span {
  width: 100%;
  height: 3px;
  background-color: #2c3e50;
  transition: all 0.3s;
}

@media (max-width: 768px) {
  .menu-toggle {
    display: flex;
  }

  .main-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transform: translateX(100%);
    transition: transform 0.3s;
  }

  .main-nav.active {
    transform: translateX(0);
  }

  .menu-toggle.active span:first-child {
    transform: translateY(9px) rotate(45deg);
  }

  .menu-toggle.active span:nth-child(2) {
    opacity: 0;
  }

  .menu-toggle.active span:last-child {
    transform: translateY(-9px) rotate(-45deg);
  }
}
</style>