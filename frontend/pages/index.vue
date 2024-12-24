<template>
  <head>
    <title>Laravel Project</title>
  </head>
  <div>
    <h1>Nuxt.js and Laravel Connection Test</h1>
    <button @click="fetchData">Fetch Data from Laravel API !</button>
    <p v-if="apiResponse">{{ apiResponse }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const apiResponse = ref(null);
definePageMeta({
  layout: "default",
});
const fetchData = async () => {
  try {
    const response = await $fetch('http://localhost:8000/api/test');
    apiResponse.value = response.message;
  } catch (error) {
    console.error('Error fetching data from Laravel API:', error);
    apiResponse.value = 'Error fetching data from Laravel API';
  }
};
</script>
