<script setup>
import { AppointmentService } from '~/services/appointmentService';
import { isEqual } from 'lodash';

const appointmentDialog = ref(false);
const submitted = ref(false);
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
const {patients} = usePatients();
const { appointments,fetchAppointments} = useAppointments();


const props = defineProps({
  appointment: {
    type: Object,
    required: true, // Make it required if the appointment is expected
  },
});

// Create a reactive reference for the appointment prop
const appointment = toRef(props, 'appointment');

async function saveAppointment() {
  
  submitted.value = true;
  disabled.value = true;
  if (appointment?.value.date) {
    if (appointment.value.id) {
      const originalAppointment = appointments.value.find((item) => item.id === appointment.value.id);
      if (isEqual(originalAppointment, appointment.value)) {
        toast.add({ severity: 'info', summary: 'No Changes', detail: 'No updates made to the appointment.', life: 3000 });
        emit('hideDialog');
        disabled.value = false;
        return;
      }
      // Update an existing appointment
      let updatedAppointment=null;
      try {
      updatedAppointment = await AppointmentService.updateAppointment(appointment.value.id, appointment.value);
    } catch (error) {
      if (error.response && error.response.data && error.response.data.errors) {
        toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.errors[0].msg, life: 3000 });
      } else {
        console.error('Error updating appointment:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
      }
      
    }
    finally {
      disabled.value = false;
    }
      updatedAppointment.appointment.time = updatedAppointment.appointment.time.slice(0, 5);
      appointments.value[findIndexById(appointment.value.id)] = updatedAppointment.appointment;

      toast.add({ severity: 'success', summary: 'Successful', detail: 'Appointment Updated', life: 3000 });
    } 

    emit('hideDialog');
  }
 
}

const emit = defineEmits(['hideDialog']);
function hideDialog() {
  
  submitted.value = false;
  emit('hideDialog');
}



function findIndexById(id) {
  return appointments.value.findIndex((item) => item.id === id);
}



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
    :options="patients"
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
