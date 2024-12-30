<script setup>
import { PatientService } from '~/services/patientService';
import { isEqual } from 'lodash';
const patientDialog = ref(false);
const submitted = ref(false);
const disabled = ref(false);
const toast = useToast();
const { fetchPatients ,patients} = usePatients();

const props = defineProps({
  patient: {
    type: Object,
    required: true,
  }
});
const patient = toRef(props, 'patient');
console.log(patient.value,"patient");


// Options for dropdowns
const sexOptions = ref([
  { label: 'Male', value: 'Male' },
  { label: 'Female', value: 'Female' },
]);

const groupOptions = ref([
  { label: 'A+', value: 'A+' },
  { label: 'A-', value: 'A-' },
  { label: 'B+', value: 'B+' },
  { label: 'B-', value: 'B-' },
  { label: 'AB+', value: 'AB+' },
  { label: 'AB-', value: 'AB-' },
  { label: 'O+', value: 'O+' },
  { label: 'O-', value: 'O-' },
]);
const civilizationOptions = ref([
  { label: 'Single', value: 'Single' },
  { label: 'Married', value: 'Married' },
  { label: 'Divorced', value: 'Divorced' },
  { label: 'Widowed', value: 'Widowed' },
]);
async function savePatient() {
  submitted.value = true;
  disabled.value = true;

  // Basic validation before proceeding
  if (!patient.value.firstName || !patient.value.lastName || !patient.value.email) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Please fill in all required fields.', life: 3000 });
    disabled.value = false;
    return;
  }

  if (patient.value.id) {
    // Find original patient for comparison
    const originalPatient = patients.value.find((item) => item.id === patient.value.id);
    
    // Check if any changes were made
    if (isEqual(originalPatient, patient.value)) {
      toast.add({ severity: 'info', summary: 'No Changes', detail: 'No updates made to the patient.', life: 3000 });
      emit('hideDialog');
      disabled.value = false;
      return;
    }

    // Update existing patient
    let updatedPatient = null;
    try {
      updatedPatient = await PatientService.updatePatient(patient.value.id, patient.value);
      
      // Update the local patients array
      //patients.value[findIndexById(patient.value.id)] = updatedPatient.patient;
      
      toast.add({ severity: 'success', summary: 'Successful', detail: 'Patient Updated', life: 3000 });
      fetchPatients();
      emit('hideDialog');
    } catch (error) {
      if (error.response?.data?.errors) {
        toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.errors[0].msg, life: 3000 });
      } else {
        console.error('Error updating patient:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error updating patient', life: 3000 });
      }
    } finally {
      disabled.value = false;
    }
  } 
}

const emit = defineEmits(['hideDialog']);
const hideDialog = () => {
  emit('hideDialog');
};
function findIndexById(id) {
  return patients.value.findIndex((item) => item.id === id);
}


</script>

<template>
<Dialog
  :style="{ width: '700px' }"
  header="Patient Details"
  :modal="true"
>
  <div class="flex flex-col gap-6">
    <!-- Name Fields -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="firstName" class="block font-bold mb-2">First Name</label>
        <InputText
          id="firstName"
          v-model.trim="patient.firstName"
          required="true"
          placeholder="Enter first name"
          class="w-full"
        />
      </div>
      <div>
        <label for="lastName" class="block font-bold mb-2">Last Name</label>
        <InputText
          id="lastName"
          v-model.trim="patient.lastName"
          required="true"
          placeholder="Enter last name"
          class="w-full"
        />
      </div>
    </div>

    <!-- Sex and Blood Group -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="sex" class="block font-bold mb-2">Sex</label>
        <Dropdown
          id="sex"
          v-model="patient.sex"
          :options="sexOptions"
          optionLabel="label"
          optionValue="value"
          placeholder="Select sex"
          class="w-full"
        />
      </div>
      <div>
        <label for="groupS" class="block font-bold mb-2">Blood Group</label>
        <Dropdown
          id="groupS"
          v-model="patient.groupS"
          :options="groupOptions"
          optionLabel="label"
          optionValue="value"
          placeholder="Select blood group"
          class="w-full"
        />
      </div>
    </div>

    <!-- Birth Date -->
    <div>
      <label for="birthDate" class="block font-bold mb-2">Birth Date</label>
      <input
        type="date"
        v-model="patient.birthDate"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
      />
    </div>

    <!-- Address -->
    <div>
      <label for="address" class="block font-bold mb-2">Address</label>
      <Textarea
        id="address"
        v-model="patient.address"
        rows="2"
        placeholder="Enter address"
        class="w-full"
      />
    </div>

    <!-- Civilization -->
    <div>
      <label for="civilization" class="block font-bold mb-2">Civilization</label>
      <Dropdown
          id="civilization"
          v-model="patient.civilization"
          :options="civilizationOptions"
          optionLabel="label"
          optionValue="value"
          placeholder="Select civilization"
          class="w-full"
        />
    </div>

    <!-- Contact Information -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="email" class="block font-bold mb-2">Email</label>
        <InputText
          id="email"
          v-model.trim="patient.email"
          type="email"
          required="true"
          placeholder="Enter email"
          class="w-full"
        />
      </div>
      <div>
        <label for="phone" class="block font-bold mb-2">Phone</label>
        <InputText
          type="tel"
          id="phone"
          v-model.trim="patient.phone"
          placeholder="Enter phone number"
          class="w-full"
        />
      </div>
    </div>
  </div>

  <!-- Footer Buttons -->
  <template #footer>
    <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
    <Button label="Save" icon="pi pi-check" @click="savePatient" :disabled="disabled"/>
  </template>
</Dialog>
</template>