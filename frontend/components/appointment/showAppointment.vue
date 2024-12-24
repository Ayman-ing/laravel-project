<template>
    <div class="max-w-3xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-6 text-gray-800">Appointment Details</h1>
      
      <div v-if="appointment">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-6">
            <h5 class="text-xl font-semibold mb-4 text-primary-600">
              Appointment #{{ appointment.id }}
            </h5>
            
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                  <i class="i-carbon-calendar text-gray-600"></i>
                  <span class="font-medium">Date:</span>
                  <span>{{ appointment.date }}</span>
                </div>
                
                <div class="flex items-center space-x-3">
                  <i class="i-carbon-time text-gray-600"></i>
                  <span class="font-medium">Time:</span>
                  <span>{{ appointment.time }}</span>
                </div>
              </div>
  
              <div class="flex items-center space-x-3">
                <i class="i-carbon-user text-gray-600"></i>
                <span class="font-medium">Patient:</span>
                <span>{{ appointment.patient?.firstName + " " +  appointment.patient?.lastName || 'N/A' }}</span>
              </div>
  
          
  
              <div class="flex items-center space-x-3">
                <i class="i-carbon-document text-gray-600"></i>
                <span class="font-medium">Status:</span>
                <span :class="{
                  'text-green-600': appointment.status === 'Confirmed',
                  'text-red-600': appointment.status === 'Cancelled',
                  'text-yellow-600': appointment.status === 'Pending'
                }">{{ appointment.status }}</span>
              </div>
  
              <div class="border-t pt-4">
                <div class="flex items-start space-x-3">
                  <i class="i-carbon-notebook mt-1 text-gray-600"></i>
                  <div>
                    <span class="font-medium">Reason for Visit:</span>
                    <p class="mt-1 text-gray-700">{{ appointment.reason_for_visit }}</p>
                  </div>
                </div>
              </div>
  
              <div class="flex items-center space-x-3">
                <i class="i-carbon-currency text-gray-600"></i>
                <span class="font-medium">Total Fee:</span>
                <span>${{ appointment.total_fee || 'Not specified' }}</span>
              </div>
  
              <div v-if="appointment.notes" class="border-t pt-4">
                <div class="flex items-start space-x-3">
                  <i class="i-carbon-note mt-1 text-gray-600"></i>
                  <div>
                    <span class="font-medium">Notes:</span>
                    <p class="mt-1 text-gray-700">{{ appointment.notes }}</p>
                  </div>
                </div>
              </div>
  
              <div v-if="appointment.cancellation_reason" class="border-t pt-4">
                <div class="flex items-start space-x-3">
                  <i class="i-carbon-close-filled mt-1 text-red-600"></i>
                  <div>
                    <span class="font-medium">Cancellation Reason:</span>
                    <p class="mt-1 text-red-600">{{ appointment.cancellation_reason }}</p>
                  </div>
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
  
      <NuxtLink 
        to="/appointments"
        class="inline-flex items-center px-4 py-2 mt-6 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors"
      >
        <i class="i-carbon-arrow-left mr-2"></i>
        Back to Appointments
      </NuxtLink>
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
      const [date, time] = data.appointment_date.split(' ');
      appointment.value = {
        ...data,
        date,
        time: time ? time.slice(0, 5) : '',
      };
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