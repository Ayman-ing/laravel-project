<template>
  <div class="flex bg-[url('https://preline.co/assets/svg/examples/polygon-bg-element.svg')] h-screen items-center py-16 relative from-blue-600 via-transparent dark:from-blue-950 dark:via-transparent">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center justify-center">
      <div class="text-center md:text-left max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <p class="inline-block text-sm font-medium bg-clip-text bg-gradient-to-l from-blue-600 to-violet-500 text-transparent dark:from-blue-400 dark:to-violet-400">
          Next, notre vision 2024 pour
        </p>

        <!-- Title -->
        <div class="mt-4 md:mb-12 max-w-2xl">
          <h1 class="mb-4 font-semibold text-gray-800 text-4xl lg:text-5xl dark:text-gray-200">
            La mairie connectée 
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Votre partenaire pour une gestion citoyenne efficace.
          </p>
        </div>
        <!-- End Title -->

       
      </div>

      <!-- Slot for form -->
      <div class="w-full  justify-center px-4">
        <div class="max-w-full">
          <slot />
        </div>
      </div>
      <div class="mt-6 md:mt-12 py-3 flex items-center text-sm text-gray-800 gap-x-1.5 after:flex-[1_1_0%] after:border-t after:border-gray-200 after:ms-6 dark:text-white dark:after:border-gray-700">
          <span class="font-semibold bg-clip-text bg-gradient-to-l from-blue-600 to-violet-500 text-transparent dark:from-blue-400 dark:to-violet-400">@</span>
          nvotec
        </div>
        <div>
         <!--                         <ReCaptcha v-if="!submitted" @verify="onVerify" />                         -->
        </div>
    </div>
  </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>
