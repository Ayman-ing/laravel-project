<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
<script setup>
const {fetchPatientsNames} = usePatients();
onBeforeMount(() => {
  fetchPatientsNames();
});
 



</script>

