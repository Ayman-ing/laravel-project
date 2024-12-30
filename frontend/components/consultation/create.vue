<script setup>
import { ConsultationService } from '~/services/consultationService';
const consultationDialog = ref(false);
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
const { fetchConsultations} = useConsultations();

const consultation = ref({
    date: '',
    time: '',
    duration: '',
    symptoms: '',
    diagnosis: '',
    treatment_plan: '',
    prescription: '',
    test_results: '',
    referrals: '',
    consultation_notes: '',
});


async function saveConsultation() {
    console.log("we are here first");
    disabled.value = true;
    let newConsultation = null;
      // Create a new consultation
    if (!consultation.value.date) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a date for the consultation.', life: 3000 });
        disabled.value = false;
        return;
        }
    if (!consultation.value.time) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a time for the consultation.', life: 3000 });
        disabled.value = false;
        return;
        }
    if (!consultation.value.reason_for_visit) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please enter a reason for the visit.', life: 3000 });
        disabled.value = false;
        return;
        }
    if (!consultation.value.patient_id) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a patient for the consultation.', life: 3000 });
        disabled.value = false;
        return;
        }
    
    
      try {
        console.log(consultation.value);
      newConsultation = await ConsultationService.createConsultation(consultation.value);
        console.log(newConsultation);
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Consultation Created', life: 3000 });


      }
      catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.errors[0].msg, life: 3000 });
        } else {
          console.error('Error creating consultation:', error);
          toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
        }
      }
      finally {
        disabled.value = false;
        emit('hideDialog');
      }
      console.log("we are here");   
      fetchConsultations();
      
 
}

const emit = defineEmits(['hideDialog']);
const hideDialog = () => {
  emit('hideDialog');
};


</script>

<template>
    <Dialog
      :visible="visible"
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
            placeholder="Search and Select a Patient"
            filter
            fluid
          />
        </div>
  
        <!-- Date & Time -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="date" class="block font-bold mb-3">Date</label>
            <input
              type="date"
              id="date"
              v-model="consultation.date"
              class="px-3 py-2 border border-gray-300 rounded-lg"
            />
          </div>
          <div>
            <label for="time" class="block font-bold mb-3">Time</label>
            <input
              type="time"
              id="time"
              v-model="consultation.time"
              class="px-3 py-2 border border-gray-300 rounded-lg"
            />
          </div>
        </div>
  
        <!-- Duration -->
        <div>
          <label for="duration" class="block font-bold mb-3">Duration</label>
          <InputText
            id="duration"
            v-model="consultation.duration"
            placeholder="Enter consultation duration"
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
            placeholder="Enter prescription"
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
  
        <!-- Notes -->
        <div>
          <label for="consultation_notes" class="block font-bold mb-3">Notes</label>
          <Textarea
            id="consultation_notes"
            v-model="consultation.consultation_notes"
            rows="3"
            placeholder="Enter consultation notes"
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
