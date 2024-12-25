<template>
  <Home/>
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
