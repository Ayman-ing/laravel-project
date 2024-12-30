<script setup>
import { ConsultationService } from '~/services/consultationService';
import { isEqual } from 'lodash';

const consultationDialog = ref(false);
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
const { consultations,fetchConsultations} = useConsultations();


const props = defineProps({
  consultation: {
    type: Object,
    required: true, // Make it required if the consultation is expected
  },
});

// Create a reactive reference for the consultation prop
const consultation = toRef(props, 'consultation');

async function saveConsultation() {
  
  submitted.value = true;
  disabled.value = true;
  if (consultation?.value.date) {
    if (consultation.value.id) {
      const originalConsultation = consultations.value.find((item) => item.id === consultation.value.id);
      if (isEqual(originalConsultation, consultation.value)) {
        toast.add({ severity: 'info', summary: 'No Changes', detail: 'No updates made to the consultation.', life: 3000 });
        emit('hideDialog');
        disabled.value = false;
        return;
      }
      // Update an existing consultation
      let updatedConsultation=null;
      try {
      updatedConsultation = await ConsultationService.updateConsultation(consultation.value.id, consultation.value);
    } catch (error) {
      if (error.response && error.response.data && error.response.data.errors) {
        toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.errors[0].msg, life: 3000 });
      } else {
        console.error('Error updating consultation:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
      }
      
    }
    finally {
      disabled.value = false;
    }
      updatedConsultation.consultation.time = updatedConsultation.consultation.time.slice(0, 5);
      consultations.value[findIndexById(consultation.value.id)] = updatedConsultation.consultation;

      toast.add({ severity: 'success', summary: 'Successful', detail: 'Consultation Updated', life: 3000 });
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
  return consultations.value.findIndex((item) => item.id === id);
}



</script>

<template>
  <Dialog
    :style="{ width: '800px' }"
    header="Consultation Details"
    :modal="true"
  >
    <div class="flex flex-col gap-6">
      <!-- Appointment ID -->
      <div>
        <label for="appointment_id" class="block font-bold mb-3">Appointment ID</label>
        <InputText
          id="appointment_id"
          v-model="consultation.appointment_id"
          placeholder="Enter appointment ID"
          fluid
        />
      </div>

      <!-- Patient Selection -->
      <div>
        <label for="patient" class="block font-bold mb-3">Patient</label>
        <Dropdown
          id="patient"
          v-model="consultation.patient_id"
          :options="patients"
          optionValue="id"
          optionLabel="name"
          placeholder="Select a patient"
          filter
          fluid
        />
      </div>

      <!-- Consultation Date -->
      <div>
        <label for="date" class="block font-bold mb-3">Date</label>
        <input
          type="date"
          id="date"
          v-model="consultation.date"
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
        />
      </div>

      <!-- Consultation Time -->
      <div>
        <label for="time" class="block font-bold mb-3">Time</label>
        <input
          type="time"
          id="time"
          v-model="consultation.time"
          class="px-3 py-2 border border-gray-300 rounded-lg"
        />
      </div>

      <!-- Duration -->
      <div>
        <label for="duration" class="block font-bold mb-3">Duration (minutes)</label>
        <InputNumber
          id="duration"
          v-model="consultation.duration"
          :min="0"
          placeholder="Enter duration"
          fluid
        />
      </div>

      <!-- Symptoms -->
      <div>
        <label for="symptoms" class="block font-bold mb-3">Symptoms</label>
        <Textarea
          id="symptoms"
          v-model="consultation.symptoms"
          rows="3"
          placeholder="Enter symptoms"
          fluid
        />
      </div>

      <!-- Diagnosis -->
      <div>
        <label for="diagnosis" class="block font-bold mb-3">Diagnosis</label>
        <Textarea
          id="diagnosis"
          v-model="consultation.diagnosis"
          rows="3"
          placeholder="Enter diagnosis"
          fluid
        />
      </div>

      <!-- Treatment Plan -->
      <div>
        <label for="treatment_plan" class="block font-bold mb-3">Treatment Plan</label>
        <Textarea
          id="treatment_plan"
          v-model="consultation.treatment_plan"
          rows="3"
          placeholder="Enter treatment plan"
          fluid
        />
      </div>

      <!-- Prescription -->
      <div>
        <label for="prescription" class="block font-bold mb-3">Prescription</label>
        <Textarea
          id="prescription"
          v-model="consultation.prescription"
          rows="3"
          placeholder="Enter prescription details"
          fluid
        />
      </div>

      <!-- Test Results -->
      <div>
        <label for="test_results" class="block font-bold mb-3">Test Results</label>
        <Textarea
          id="test_results"
          v-model="consultation.test_results"
          rows="3"
          placeholder="Enter test results"
          fluid
        />
      </div>

      <!-- Referrals -->
      <div>
        <label for="referrals" class="block font-bold mb-3">Referrals</label>
        <Textarea
          id="referrals"
          v-model="consultation.referrals"
          rows="3"
          placeholder="Enter referrals"
          fluid
        />
      </div>

      <!-- Consultation Notes -->
      <div>
        <label for="consultation_notes" class="block font-bold mb-3">Consultation Notes</label>
        <Textarea
          id="consultation_notes"
          v-model="consultation.consultation_notes"
          rows="3"
          placeholder="Enter additional notes"
          fluid
        />
      </div>
    </div>

    <!-- Footer Buttons -->
    <template #footer>
      <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
      <Button label="Save" icon="pi pi-check" @click="saveConsultation" />
    </template>
  </Dialog>
</template>
