<template>
  <div class="p-0 m-0 w-screen h-screen">
    <Toast position="top-center" />
    <router-view />
  </div>
</template>
<script setup>
import Toast from 'primevue/toast';
</script>