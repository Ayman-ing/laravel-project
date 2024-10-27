<template>
  <div>
      <div id="auth-form" class="max-w-[500px]">
          <Form id="login" @submit=""  :validation-schema="schema" v-slot="{errors}" >
              <div >
                  <!-- Card -->
                  <div class="p-4 sm:p-7 flex flex-col bg-white rounded-2xl shadow-lg dark:bg-slate-900">
                    <div class="text-center">
                      <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Connexion</h1>
                      <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Pas encore de compte?
                        <NuxtLink to="/auth/signup" class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" >
                          S'inscrire
                        </NuxtLink>
                      </p>
                      <p  class=" text-xs text-red-600 mt-2" id="global-error">{{ globError }}</p>
                    </div>
                    <div class="mt-5">
                      <div class="py-3 flex items-center text-xs text-gray-400 uppercase before:flex-[1_1_0%] before:border-t before:border-gray-200 before:me-6 after:flex-[1_1_0%] after:border-t after:border-gray-200 after:ms-6 dark:text-gray-500 dark:before:border-gray-700 dark:after:border-gray-700">Info de connexion</div>
                      <!-- Grid -->
                      <div class="grid grid-cols-2 gap-4">
                          <div class="col-span-full">
                              <label for="email"  class="block text-sm mb-2 dark:text-white">Courriel</label>
                              <div class="relative">
                                 <Field type="email" v-model="form.email"   id="email" name="email" tabindex="1" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="email-error"/>
                                  <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                    <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                    </svg>
                                  </div>
                              </div>
                              <p  class=" text-xs text-red-600 mt-2" id="email-error">{{ errors.email }}</p>
                          </div>
                          <div class="col-span-full">
                              <div class="flex justify-between items-center">
                                  <label for="password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                                  <NuxtLink to="/auth/forgetPassword" class="text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="getContent('displayRecoverAccountForm', 'auth-form')">
                                      Mot de passe oublié?
                                  </NuxtLink>
                              </div>
                              <div class="relative w-full">
                                 <div class="relative">
  <Field v-model="form.password"  id="hs-toggle-password" type="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
  <button type="button" data-hs-toggle-password='{
    "target": "#hs-toggle-password"
  }' class="absolute top-0 end-0 p-3.5 rounded-e-md">
    <svg class="flex-shrink-0 size-3.5 text-gray-400 dark:text-neutral-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
      <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
      <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
      <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
      <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
      <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
    </svg>
  </button>
</div>
<p class=" text-xs text-red-600 mt-2" id="password-error">{{ errors.password }}</p>
</div>
                          </div>
                      </div>
                      <!-- End Grid -->
                      <!-- Checkbox -->
                      <div class="mt-5 flex items-center">
                        <div class="flex">
                          <input id="remember-me" name="remember-me" type="checkbox" class="mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        </div>
                        <div class="ms-3">
                          <label for="remember-me" class="text-sm dark:text-white">Se souvenir de moi</label>
                        </div>
                      </div>
                      <!-- End Checkbox -->
                      <div class="mt-5">
                        <button type="submit"  class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Se connecter</button>
                      </div>
                    </div>
                  </div>
                  <!-- End Card -->
                </div>
          </Form>
         <!-- <Loading :short="isLoading"/>         -->
      </div>
  </div>
</template>
<script setup>
import { Form,Field } from 'vee-validate';
import * as Yup from 'yup';


const form = ref({
  password: '',
  email: ''
});
const isLoading = ref(false);
const schema = ref(Yup.object().shape({
email: Yup.string().email("Veuillez fournir une adresse électronique valide.").required("Le nom d'utilisateur est obligatoir."),
password: Yup.string().min(8, 'Must be at least 8 characters').required("Le mot de passe est obligatoir.")
}));
const globError = ref('');
const userId1=ref('');


</script>
<style lang="scss" scoped>
@import '~/assets/custom.css';
@import '~/assets/main1.css';
.field__error {
          color:red;
      }
      .btn-show {
          color: rgb(156 164 177);
          padding-left: 0.75rem;
          padding-right: 0.75rem;
          border-start-end-radius: 0.375rem;
          border-end-end-radius: 0.375rem;
          align-items: center;
          cursor: pointer;
          display: flex;
          z-index: 20;
          inset-inline-end: 0px;
          top: 0px;
          bottom: 0px;
          position: absolute;
      }
      .svg-show {
          width: 0.875rem;
          flex-shrink: 0;
          height: 0.875rem;
          display: block;
          vertical-align: middle;
      }
</style>