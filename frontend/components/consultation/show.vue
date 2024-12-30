<template>
  <div class="max-w-3xl mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Consultation Details</h1>

      <NuxtLink
        class="flex items-center gap-2 text-primary-600 hover:text-primary-700 transition-colors border 
        border-primary-600 px-4 py-1 rounded-full text-sm hover:bg-blue-100 bg-opacity-25 transition-colors"
        to="/consultations"
      >
        Back
        <i class="pi pi-arrow-right p-2"></i>
      </NuxtLink>
    </div>

    <div v-if="consultation" class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h5 class="text-xl font-semibold text-primary-600">
            Consultation #{{ consultation.id }}
          </h5>
        </div>

        <div class="space-y-6">
          <!-- Date & Time Section -->
          <div class="flex flex-row justify-around bg-gray-50 rounded-lg p-4">
            <div class="flex items-center gap-2">
              <i class="pi pi-calendar text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Date:</span>
              <span>{{ consultation.date }}</span>
            </div>

            <div class="flex items-center gap-2">
              <i class="pi pi-clock text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Time:</span>
              <span>{{ consultation.time.slice(0, 5) }}</span>
            </div>

            <div class="flex items-center gap-2">
              <i class="pi pi-clock text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Duration:</span>
              <span>{{ consultation.duration || 'N/A' }}</span>
            </div>
          </div>

          <!-- Patient Info Section -->
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center gap-2">
              <i class="pi pi-user text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Patient:</span>
              <span>{{ consultation.patient?.firstName + " " + consultation.patient?.lastName || 'N/A' }}</span>
            </div>

            <div class="flex items-center gap-2">
              <i class="pi pi-phone text-primary-600 text-lg"></i>
              <span class="font-medium mr-2">Phone:</span>
              <span>{{ consultation.patient?.phone || 'N/A' }}</span>
            </div>
          </div>

          <!-- Consultation Details -->
          <div class="border-t border-gray-100 pt-4">
            <h6 class="font-medium mb-3">Consultation Details</h6>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="space-y-3">
                <div class="flex items-start gap-2">
                  <i class="pi pi-pencil text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Symptoms:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.symptoms || 'Not specified' }}</p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <i class="pi pi-notebook text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Diagnosis:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.diagnosis || 'Not specified' }}</p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <i class="pi pi-list text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Treatment Plan:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.treatment_plan || 'Not specified' }}</p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <i class="pi pi-prescription text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Prescription:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.prescription || 'Not specified' }}</p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <i class="pi pi-folder text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Test Results:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.test_results || 'Not specified' }}</p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <i class="pi pi-users text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Referrals:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.referrals || 'Not specified' }}</p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <i class="pi pi-comment text-primary-600 text-lg mt-1"></i>
                  <div>
                    <span class="font-medium">Consultation Notes:</span>
                    <p class="mt-1 text-gray-700">{{ consultation.consultation_notes || 'Not specified' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Cancellation Reason -->
          <div v-if="consultation.cancellation_reason" class="bg-red-50 rounded-lg p-4 border border-red-100">
            <div class="flex items-start gap-2">
              <div>
                <span class="font-medium text-red-800">Cancellation Reason:</span>
                <p class="mt-1 text-red-700">{{ consultation.cancellation_reason }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="flex flex-col justify-center py-8">
      <Skeleton height="4rem" class="mb-2" borderRadius="16px"></Skeleton>
      <Skeleton width="6rem" height="4rem" borderRadius="16px" class="mb-2"></Skeleton>
      <Skeleton height="10rem" class="mb-2" borderRadius="16px"></Skeleton>
      <Skeleton class="mb-2" borderRadius="16px"></Skeleton>
    </div>
  </div>
</template>
  
  <script setup>
  import { ConsultationService } from '~/services/consultationService';
  
  const route = useRoute();
  const consultation = ref(null);
  
  onMounted(async () => {
    try {
      const id = route.params.id;
      const data = await ConsultationService.getConsultation(id);
      consultation.value = data;
      console.log('Consultation:', data);
    } catch (error) {
      console.error('Error fetching consultation:', error);
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
