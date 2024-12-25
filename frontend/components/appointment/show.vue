<template>
    <div class="max-w-3xl mx-auto p-4">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Appointment Details</h1>

        <NuxtLink 
        class="flex items-center gap-2 text-primary-600 hover:text-primary-700 transition-colors border 
        border-primary-600 px-4 py-1 rounded-full text-sm hover:bg-blue-100 bg-opacity-25 transition-colors"
        to="/appointments"
      >
      
      Back
      <i class="pi pi-arrow-right p-2"></i>


        
      </NuxtLink>  
      </div>
      
     
  <div v-if="appointment" class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h5 class="text-xl font-semibold text-primary-600">
          Appointment #{{ appointment.id }}
        </h5>
         
      </div>
      
      <div class="space-y-6">
        <!-- Date & Time Section -->
        <div class="flex flex-row justify-around bg-gray-50 rounded-lg p-4">
          <div class="flex items-center gap-2">
            <i class="pi pi-calendar text-primary-600 text-lg"></i>
            <span class="font-medium mr-2">Date:</span>
            <span>{{ appointment.date }}</span>
          </div>
          
          <div class="flex items-center gap-2">
            <i class="pi pi-clock text-primary-600 text-lg"></i>
            <span class="font-medium mr-2">Time:</span>
            <span>{{ appointment.time.slice(0,5) }}</span>
          </div>
        </div>

        <!-- Patient Info Section -->
        <div class="bg-gray-50 rounded-lg p-4">
          <div class="flex flex-row justify-between items-center">
            <div class="flex items-center gap-2">
              <i class="pi pi-user text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Patient:</span>
              <span>{{ appointment.patient?.firstName + " " + appointment.patient?.lastName || 'N/A' }}</span>
            </div>
            
            <div class="flex items-center gap-2">
              <i class="pi pi-phone text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Phone:</span>
              <span>{{ appointment.patient?.phone }}</span>
            </div>
            
            <NuxtLink :to="{ name: 'patients-id', params: { id: appointment.patient_id } }"><button class="flex items-center gap-2 text-primary-600 hover:text-primary-700 transition-colors">
              <span>View more details</span>
              <i class="pi pi-arrow-right"></i>
            </button>
            </NuxtLink>
          </div>
        </div>
        <div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
      <h5 class="text-xl font-semibold text-primary-600">
        Appointment #{{ appointment.id }}
      </h5>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-sm font-medium" 
      :class="{

        'border': appointment.status === 'completed',
        'bg-green-100 text-green-800': appointment.status === 'in_progress',
        'bg-red-100 text-red-800': appointment.status === 'canceled',
        'bg-yellow-100 text-yellow-800': appointment.status === 'pending',
        'bg-blue-100 text-blue-800': appointment.status === 'confirmed',
        'bg-orange-100 text-orange-800': appointment.status === 'no_show',
        
      }">
  {{ appointment.status }}
      </span>
        
        <button v-if="appointment.status === 'in_progress'"
                class="px-4 py-1 bg-primary-600 text-white rounded-full text-sm hover:bg-primary-700 transition-colors">
          Create Consultation
        </button>
        
        <button v-if="appointment.status === 'completed'"
                class="px-4 py-1 border border-primary-600 text-primary-600 rounded-full text-sm hover:bg-primary-50 transition-colors">
          View Consultation
        </button>
      </div>
    </div>
  </div>
        <!-- Visit Details -->
        <div class="border-t border-gray-100 pt-4">
          <h6 class="font-medium mb-3">Visit Details</h6>
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="space-y-3">
              <div class="flex items-start gap-2">
                <i class="pi pi-file-edit text-primary-600 text-lg mt-1"></i>
                <div>
                  <span class="font-medium">Reason for Visit:</span>
                  <p class="mt-1 text-gray-700">{{ appointment.reason_for_visit }}</p>
                </div>
              </div>

              <div class="flex items-center gap-2">
                <i class="pi pi-dollar text-primary-600 text-lg"></i>
                <span class="font-medium mr-2">Total Fee:</span>
                <span>${{ appointment.total_fee || 'Not specified' }}</span>
              </div>

              <div v-if="appointment.notes" class="flex items-start gap-2">
                <i class="pi pi-comment text-primary-600 text-lg mt-1"></i>
                <div>
                  <span class="font-medium">Notes:</span>
                  <p class="mt-1 text-gray-700">{{ appointment.notes }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="appointment.cancellation_reason" 
             class="bg-red-50 rounded-lg p-4 border border-red-100">
          <div class="flex items-start gap-2">
            <div>
              <span class="font-medium text-red-800">Cancellation Reason:</span>
              <p class="mt-1 text-red-700">{{ appointment.cancellation_reason }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



      
      <div v-else class="flex flex-col justify-center py-8">
    
        <Skeleton height="4rem" class="mb-2" borderRadius="16px"></Skeleton>
        <Skeleton width="6rem" height="4rem" borderRadius="16px" class="mb-2"></Skeleton>

        <Skeleton height="10rem"  class="mb-2 " borderRadius="16px"></Skeleton>
        <Skeleton  class="mb-2" borderRadius="16px"></Skeleton>
  
      </div>
  
      
    </div>
  </template>
  
  <script setup>
  import { AppointmentService } from '~/services/appointmentService';
  
  const route = useRoute();
  const appointment = ref(null);
  
  onMounted(async () => {
    try {
      const id = route.params.id;
      const data = await AppointmentService.getAppointment(id);
      appointment.value = data;
      console.log('Appointment:', data);
    } catch (error) {
      console.error('Error fetching appointment:', error);
    }
  });
  </script>
  
  <style scoped>
  .text-primary-600 {
    color: #2563eb;
  }
  
  .bg-primary-600 {
    background-color: #2563eb;
  }
  
  .bg-primary-700 {
    background-color: #1d4ed8;
  }
  
  .border-primary-600 {
    border-color: #2563eb;
  }
  </style>
