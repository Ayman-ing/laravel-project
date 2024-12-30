<script setup>
  import { PatientService } from '~/services/patientService';
  
  const route = useRoute();
  const patient = ref(null);

  onMounted(async () => {
   
    try {
      const id = route.params.id;
     
      const data = await PatientService.getPatient(id);
      patient.value = data;
      
    } catch (error) {
      console.error('Error fetching appointment:', error);
    }
    
  });
const calculateAge = (birthDate) => {
  const today = new Date();
  const birth = new Date(birthDate);
  let age = today.getFullYear() - birth.getFullYear();
  const monthDiff = today.getMonth() - birth.getMonth();
  
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
    age--;
  }
  return age;
};

</script>

<template>
   
      
  <div v-if="patient" class="max-w-4xl mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Patient Details</h1>

        <NuxtLink 
        class="flex items-center gap-2 text-primary-600 hover:text-primary-700 transition-colors border 
        border-primary-600 px-4 py-1 rounded-full text-sm hover:bg-blue-100 bg-opacity-25 transition-colors"
        to="/patients"
      >
      
      Back
      <i class="pi pi-arrow-right p-2"></i>


        
      </NuxtLink>  
      </div>
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
   
      
      <!-- Header with basic info -->
      <div class="bg-primary-50 p-6 border-b">
   
        <div class="flex justify-between items-start">

          <div>
            <h1 class="text-2xl font-bold text-gray-900">
             {{ patient.firstName }} {{ patient.lastName }}
            </h1>
            <div class="flex gap-4 mt-2">
              <span class="flex items-center gap-1 text-gray-600">
                <i class="pi pi-user text-primary-600"></i>
                ID #{{ patient.id }}
              </span>
              <span class="flex items-center gap-1 text-gray-600">
                <i class="pi pi-calendar text-primary-600"></i>
               {{ calculateAge(patient.birthDate) }} years old
              </span>
            </div>
          </div>
      
       
        </div>
      </div>

      <!-- Main content -->
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Personal Information -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
            <div class="space-y-3">
              <div class="flex items-center gap-2">
                <i class="pi pi-venus-mars text-primary-600"></i>
                <span class="font-medium">Sex:</span>
               <span>{{ patient.sex }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-heart text-primary-600"></i>
                <span class="font-medium">Blood Group:</span>
               <span>{{ patient.groupS }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-user text-primary-600"></i>
                <span class="font-medium">Civil Status:</span>
                    <span>{{ patient.civilization }}</span>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Contact Information</h2>
            <div class="space-y-3">
              <div class="flex items-center gap-2">
                <i class="pi pi-phone text-primary-600"></i>
                <span class="font-medium">Phone:</span>
                    <span>{{ patient.phone }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-envelope text-primary-600"></i>
                <span class="font-medium">Email:</span>
                <span>{{ patient.email }}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="pi pi-map-marker text-primary-600"></i>
                <span class="font-medium">Address:</span>
                <span>{{ patient.address }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Appointments History -->
        <div class="mt-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Appointments History</h2>
            <button class="text-primary-600 hover:text-primary-700 flex items-center gap-1">
              View All
              <i class="pi pi-arrow-right"></i>
            </button>
          </div>
          <!-- Add appointments table or list here -->
        </div>
      </div>
    </div>
  </div>


</template>



    <style scoped>
.text-primary-600 { color: #2563eb; }
.bg-primary-600 { background-color: #2563eb; }
.bg-primary-50 { background-color: #eff6ff; }
.hover\:bg-primary-700:hover { background-color: #1d4ed8; }
</style>