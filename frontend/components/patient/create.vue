<script setup>
import { PatientService } from '~/services/patientService';
const patientDialog = ref(false);
const disabled = ref(false);
const toast = useToast();
const { fetchPatients } = usePatients();

const patient = ref({
  firstName: '',
  lastName: '',
  sex: '',
  groupS: '',
  address: '',
  civilization: '',
  birthDate: '',
  email: '',
  phone: '',
});

// Options for dropdowns
const sexOptions = ref([
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
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
  { label: 'Single', value: 'single' },
  { label: 'Married', value: 'married' },
  { label: 'Divorced', value: 'divorced' },
  { label: 'Widowed', value: 'widowed' },
]);
async function savePatient() {
  disabled.value = true;
  
  // Validation
  if (!patient.value.firstName) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'First name is required', life: 3000 });
    disabled.value = false;
    return;
  }
  if (!patient.value.lastName) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Last name is required', life: 3000 });
    disabled.value = false;
    return;
  }
  if (!patient.value.email) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Email is required', life: 3000 });
    disabled.value = false;
    return;
  }
  if (!patient.value.birthDate) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Birth date is required', life: 3000 });
    disabled.value = false;
    return;
  }

  try {
    const newPatient = await PatientService.createPatient(patient.value);
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Patient Created', life: 3000 });
    fetchPatients();
  } catch (error) {
    if (error.response?.data?.errors) {
      toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.errors[0].msg, life: 3000 });
    } else {
      console.error('Error creating patient:', error);
      toast.add({ severity: 'error', summary: 'Error', detail: 'Error creating patient', life: 3000 });
    }
  } finally {
    disabled.value = false;
    emit('hideDialog');
  }
}

const emit = defineEmits(['hideDialog']);
const hideDialog = () => {
  emit('hideDialog');
};
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