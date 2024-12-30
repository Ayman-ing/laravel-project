<script setup>
import { AppointmentService } from '~/services/appointmentService';
const appointmentDialog = ref(false);
const disabled = ref(false);
const toast = useToast();
const status = ref([
  { label: 'pending', value:  'pending' },
  { label: 'confirmed', value: 'confirmed' },
  { label: 'in_progress', value: 'in_progress' },
  { label: 'completed', value: 'completed' },
  { label: 'canceled', value: 'canceled' },
  { label: 'no_show', value: 'no_show' },
]);
const {patientsNames} = usePatients();
const { fetchAppointments} = useAppointments();

const appointment = ref({
  date: '',
  time: '',
  status: {label : 'pending', value: 'pending'},
  reason_for_visit: '',
  patient_id: '',
  total_fee: 0,
  canceled_at: null,
  cancellation_reason: '',
  notes: '',
});


async function saveAppointment() {
    
    disabled.value = true;
    let newAppointment = null;
      // Create a new appointment
    if (!appointment.value.date) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a date for the appointment.', life: 3000 });
        disabled.value = false;
        return;
        }
    if (!appointment.value.time) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a time for the appointment.', life: 3000 });
        disabled.value = false;
        return;
        }
    if (!appointment.value.reason_for_visit) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please enter a reason for the visit.', life: 3000 });
        disabled.value = false;
        return;
        }
    if (!appointment.value.patient_id) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a patient for the appointment.', life: 3000 });
        disabled.value = false;
        return;
        }
    
    
      try {
        
      newAppointment = await AppointmentService.createAppointment(appointment.value);
        
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Appointment Created', life: 3000 });


      }
      catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.errors[0].msg, life: 3000 });
        } else {
          console.error('Error creating appointment:', error);
          toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
        }
      }
      finally {
        disabled.value = false;
        emit('hideDialog');
      }
    
      fetchAppointments();
      
 
}

const emit = defineEmits(['hideDialog']);
const hideDialog = () => {
  emit('hideDialog');
};


</script>

<template>
<Dialog
    
 
  :style="{ width: '800px' }"
  header="Appointment Details"
  :modal="true"
>
  <div class="flex flex-col gap-6">
    <!-- Appointment Date -->
    
      <div class="date-picker flex flex-col  gap-2 relative">
        <label for="appointment_date" class="block font-bold mb-3">Date</label>

    <input
      type="date"
      v-model="appointment.date"
      class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
   

    />
  </div>

    <!-- Appointment Time -->
    <div>
      <label for="appointment_time" class="block font-bold mb-3">Time</label>
      <div class="time-picker flex items-center gap-2">
    <input
      type="time"
      v-model="appointment.time"
      class="px-3 py-2 border border-gray-300 rounded-lg   "
    />
  </div>
    </div>

    <!-- Status -->
    <div>
      <label for="status" class="block font-bold mb-3">Status</label>
      <Select
        id="status"
        v-model="appointment.status"
        :options="status"
        optionLabel="label"
        :placeholder="appointment.status?.value || appointment.status"
        fluid
      />
    </div>

    <!-- Reason for Visit -->
    <div>
      <label for="reason_for_visit" class="block font-bold mb-3">Reason for Visit</label>
      <InputText
        id="reason_for_visit"
        v-model.trim="appointment.reason_for_visit"
        required="true"
        placeholder="Enter reason"
        fluid
      />
    </div>

    <!-- Patient Selection -->
    <div>
  <label for="patient" class="block font-bold mb-3">Patient</label>
  <Dropdown
    id="patient"
    v-model="appointment.patient_id"
    :options="patientsNames"
    optionValue="id"
    optionLabel="name"  
    placeholder="Search and Select a Patient"
    filter
    fluid
  />
</div>


    <!-- Total Fee -->
    <div>
      <label for="total_fee" class="block font-bold mb-3">Total Fee</label>
      <InputNumber
        id="total_fee"
        v-model="appointment.total_fee"
        :min="0"
        placeholder="Enter total fee"
        fluid
      />
    </div>

    <!-- Cancellation Date & Reason (Conditional) -->
    <div v-if="appointment.status === 'canceled' || appointment.status?.value === 'canceled'">
      <!-- Cancellation Date -->
      <div>
        <label for="cancellation_date" class="block font-bold mb-3">Cancellation Date</label>
        <DatePicker
          id="cancellation_date"
          v-model="appointment.canceled_at"
          placeholder="Select a cancellation date"
          fluid
        />
      </div>

      <!-- Cancellation Reason -->
      <div>
        <label for="cancellation_reason" class="block font-bold mb-3">Cancellation Reason</label>
        <Textarea
          id="cancellation_reason"
          v-model="appointment.cancellation_reason"
          rows="3"
          cols="20"
          placeholder="Enter cancellation reason"
          fluid
        />
      </div>
    </div>

    <!-- Notes -->
    <div>
      <label for="notes" class="block font-bold mb-3">Notes</label>
      <Textarea
        id="notes"
        v-model="appointment.notes"
        rows="3"
        cols="20"
        placeholder="Add any additional notes"
        fluid
      />
    </div>
  </div>

  <!-- Footer Buttons -->
  <template #footer>
    <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
    <Button label="Save" icon="pi pi-check" @click="saveAppointment" :disabled="disabled"/>
  </template>
</Dialog>
</template>
