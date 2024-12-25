<template>
    <div class="home">
        
        <div class="summary-cards grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <!-- Total Appointments -->
    <div class="card bg-blue-100  dark:border p-4 rounded shadow">
      <h2 class="text-lg font-semibold text-blue-700">Total Appointments</h2>
      <p class="text-2xl font-bold text-blue-800">{{ appointments.length }}</p>
    </div>

    <!-- Next Appointment -->
    <div class="overflow-hidden w-full   dark:border sm:col-span-2 p-4 p-4 rounded shadow" :class="[nextAppointment ? getStatusColor : 'bg-gray-100']">
      <h2 class="text-lg font-semibold text-gray-900 ">Next Appointment</h2>
      
      <template v-if="nextAppointment">
        <div class="mt-3 space-y-2 ">
          <div class="flex items-center gap-2">
            <span class="text-xl font-bold text-gray-900 ">
              {{ nextAppointment.patient?.firstName }} {{ nextAppointment.patient?.lastName }}
            </span>
            <span :class="[
              'px-2 py-0.5 text-sm rounded-full',
              timeRemaining <= 15 ? 'bg-red-100 text-red-700' :
              timeRemaining <= 30 ? 'bg-yellow-100 text-yellow-700' :
              'bg-green-100 text-green-700'
            ]">
              {{ getStatusText }}
            </span>
          </div>
          <p class="text-gray-600 ">{{ nextAppointment.reason_for_visit }}</p>
          <div class="flex items-center gap-4 text-sm text-gray-600 ">
            <span>{{ formatTime(nextAppointment.time) }}</span>
            <span v-if="timeRemaining">{{ formatTimeAway(timeRemaining) }} </span>
          </div>
        </div>
      </template>
      <template v-else>
        <p class="mt-2 text-gray-600">No upcoming appointments</p>
      </template>
    </div>
  </div>
  

  <h1 class="text-2xl font-bold mb-4">Appointments for Today</h1>

      <!-- Appointments Table -->
      <div v-if="isLoading" class="text-gray-500">Loading appointments...</div>
      <div v-else-if="appointments.length === 0" class="text-gray-500">
        No appointments for today.
      </div>
      <div v-else>
        <DataTable
          :value="appointments"
          :rows="5"
          :paginator="true"
          :rowsPerPageOptions="[5, 10, 20]"
          responsiveLayout="scroll"
          width="100%"
          class="bg-white shadow rounded-lg border border-gray-200"> 
     
                  <!-- Patient -->
                  <Column field="patient.firstName" header="Patient" sortable style="min-width: 12rem">
            <template #body="slotProps">
              {{ slotProps.data.patient?.firstName }} {{ slotProps.data.patient?.lastName }}
            </template>
          </Column>
          <!-- Reason for Visit -->
          <Column field="reason_for_visit" header="Reason for Visit" sortable />
  
          <!-- Time -->
          <Column field="time" header="Time"  sortable >
            <template #body="slotProps">
               {{ slotProps.data.time.split(":").slice(0, 2).join(":") }}

            </template>
            </Column>
  

  
          <!-- Phone -->
          <Column field="patient.phone" header="Phone Number" sortable />
  
          <!-- Total Fee -->
          <Column field="total_fee" header="Total Fee" sortable />
        </DataTable>
      </div>
    </div>
   
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from "vue";
  import { HomeService } from "~/services/homeService";
  
  const appointments = ref([]);
  const isLoading = ref(true);
  const nextAppointment = ref(null);
  const timeRemaining = ref(null);
  
  // Fetch today's appointments
  const fetchTodayAppointments = async () => {
    try {
      const data = await HomeService.fetchTodayAppointments();
      appointments.value = data || [];
      calculateNextAppointment();
    } catch (error) {
      console.error("Error fetching today's appointments:", error);
    } finally {
      isLoading.value = false;
    }
  };
  

  onMounted(() => {
    fetchTodayAppointments();
  });


const getStatusColor = computed(() => {
  if (!timeRemaining.value) return 'bg-gray-100';
  if (timeRemaining.value <= 15) return 'bg-red-50';
  if (timeRemaining.value <= 30) return 'bg-yellow-50';
  return 'bg-green-50';
});

const getStatusText = computed(() => {
  if (!timeRemaining.value) return 'No appointments';
  if (timeRemaining.value <= 15) return 'Starting soon';
  if (timeRemaining.value <= 30) return 'Coming up';
  return 'confirmed';
});
const formatTimeAway = (minutes) => {
  if (!minutes) return '';
  const hours = Math.floor(minutes / 60);
  const remainingMinutes = minutes % 60;
  
  if (hours > 0) {
    return `${hours}h ${remainingMinutes}m away`;
  }
  return `${remainingMinutes}m away`;
};
const formatTime = (time) => {
  return time.split(':').slice(0, 2).join(':');
};



const calculateNextAppointment = () => {
  const now = new Date();
  const sortedAppointments = appointments.value.sort((a, b) => {
    const timeA = new Date(`${a.date}T${a.time}`);
    const timeB = new Date(`${b.date}T${b.time}`);
    return timeA - timeB;
  });

  nextAppointment.value = sortedAppointments.find((appointment) => {
    const appointmentTime = new Date(`${appointment.date}T${appointment.time}`);
    return appointmentTime > now;
  });

  if (nextAppointment.value) {
    const appointmentTime = new Date(`${nextAppointment.value.date}T${nextAppointment.value.time}`);
    const diff = (appointmentTime - now) / (1000 * 60);
    timeRemaining.value = Math.ceil(diff);
  } else {
    timeRemaining.value = null;
  }
};

onMounted(() => {
  fetchTodayAppointments();
});
  </script>
  
  <style scoped>
  .home {
    max-width: 80%;
    margin: 0 auto;
    padding: 1rem;
  }
  
  .summary-cards {
    display: flex;
    gap: 1rem;
  }
  </style>
  