<template>
  <header class="bg-white border-b shadow-sm h-16 flex items-center px-6">

  </header>
</template>

<script setup>
const logout = () => {
  localStorage.removeItem('token')
  window.location.href = '/'
}
</script>
