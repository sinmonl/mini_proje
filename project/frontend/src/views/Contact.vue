<template>
  <div class="contact-page">
    <ContactForm />
  </div>
</template>

<script>
import ContactForm from '@/components/contact/ContactForm.vue'

export default {
  name: 'ContactPage',
  components: {
    ContactForm
  }
}
</script>

<style scoped>
.contact-page {
  min-height: calc(100vh - 60px); 
  background: #f8f9fa;
}
</style>