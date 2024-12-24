
    
<template>
    <div class="card p-2   ">
        <Toolbar style="border-radius: 3rem; padding: 1rem 1rem 1rem 1.5rem">
            <template #start>
                <div class="flex items-center gap-2">
                    <NuxtLink to="/"><img src="../assets/logo.png" alt="logo" style="width: 38px" class="rounded-full" /></NuxtLink>
                    <NuxtLink to="/appointments"><Button label="Rendez-vous" text plain /></NuxtLink>
                    <Button label="Edit" text plain />
                    <Button label="View" text plain />
                </div>
            </template>

            <template #end>
                <div class="flex items-center gap-3">
                    <button type="button" class="border rounded w-8 h-8 flex justify-center items-center  hover:border-black dark:border-gray-700 dark:hover:border-white" @click="toggleDarkMode" >
                    <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
                </button>
                    <Button label="Share" severity="contrast" size="small" />
                    <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" style="width: 32px; height: 32px" />
                </div>
            </template>
        </Toolbar>
        <div class="p-4">
            

        <slot/>
    </div>
    <Toast />
    </div>
</template>

<script setup>
const isDarkTheme = ref(false);
function toggleDarkMode() {
    isDarkTheme.value = !isDarkTheme.value;
    document.documentElement.classList.toggle('my-app-dark');
}

</script>

