<script setup>
import { AppointmentService } from '~/services/appointmentService';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

onMounted(() => {
  AppointmentService.getAppointments().then((data) => (appointments.value = data));
});

const toast = useToast();
const dt = ref();
const appointments = ref([]);
const appointmentDialog = ref(false);
const deleteAppointmentDialog = ref(false);
const deleteAppointmentsDialog = ref(false);
const appointment = ref({});
const selectedAppointments = ref([]);
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);
const statuses = ref([
  { label: 'Scheduled', value: 'scheduled' },
  { label: 'Completed', value: 'completed' },
  { label: 'Canceled', value: 'canceled' },
  { label: 'No Show', value: 'no_show' },
]);

function openNew() {
  appointment.value = {};
  submitted.value = false;
  appointmentDialog.value = true;
}

function hideDialog() {
  appointmentDialog.value = false;
  submitted.value = false;
}

async function saveAppointment() {
  submitted.value = true;

  if (appointment?.value.appointment_date) {
    if (appointment.value.id) {
      // Update an existing appointment
      await AppointmentService.updateAppointment(appointment.value.id, appointment.value);
      appointments.value[findIndexById(appointment.value.id)] = appointment.value;
      toast.add({ severity: 'success', summary: 'Successful', detail: 'Appointment Updated', life: 3000 });
    } else {
      // Create a new appointment
      const newAppointment = await AppointmentService.createAppointment(appointment.value);
      appointments.value.push(newAppointment);
      toast.add({ severity: 'success', summary: 'Successful', detail: 'Appointment Created', life: 3000 });
    }

    appointmentDialog.value = false;
    appointment.value = {};
  }
}

function editAppointment(appt) {
  appointment.value = { ...appt };
  appointmentDialog.value = true;
}
function showAppointment(appt){
  appointment.value = { ...appt };
  appointmentDialog.value = true;
}

function confirmDeleteAppointment(appt) {
  appointment.value = appt;
  deleteAppointmentDialog.value = true;
}

async function deleteAppointment() {
    
  await AppointmentService.deleteAppointment(appointment.value.id);
  
  appointments.value = appointments.value.filter((val) => val.id !== appointment.value.id);
  deleteAppointmentDialog.value = false;
  appointment.value = {};
  
  toast.add({ severity: 'success', summary: 'Successful', detail: 'Appointment Deleted', life: 3000 });
  
}

function findIndexById(id) {
  return appointments.value.findIndex((item) => item.id === id);
}

function exportCSV() {
  dt.value.exportCSV();
}

function confirmDeleteSelected() {
  deleteAppointmentsDialog.value = true;
}
const blocked = ref(false);
const isLoading = ref(false);
async function deleteSelectedAppointments() {
    blocked.value = true;
    isLoading.value = true;
  const idsToDelete = selectedAppointments.value.map((appt) => appt.id);
  for (const id of idsToDelete) {
    await AppointmentService.deleteAppointment(id);
  }
  blocked.value = false;  
  appointments.value = appointments.value.filter((appt) => !selectedAppointments.value.includes(appt));
  
  deleteAppointmentsDialog.value = false;
  selectedAppointments.value = [];
  
  isLoading.value = false;
  
  toast.add({ severity: 'success', summary: 'Successful', detail: 'Appointments Deleted', life: 3000 });
  
}

function getStatusLabel(status) {
  switch (status) {
    case 'scheduled':
      return 'info';
    case 'completed':
      return 'success';
    case 'canceled':
      return 'danger';
    case 'no_show':
      return 'warn';
    default:
      return null;
  }
}
</script>


<template>
    <div>
      <div class="card">
        <Toolbar class="mb-6">
          <template #start>
            <Button label="New" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
            <Button label="Delete" icon="pi pi-trash" severity="secondary" @click="confirmDeleteSelected" :disabled="!selectedAppointments.length" />
          </template>
  
          <template #end>
            <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
          </template>
        </Toolbar>
  
        <DataTable
          ref="dt"
          v-model:selection="selectedAppointments"
          :value="appointments"
          dataKey="id"
          :paginator="true"
          :rows="5"
          :filters="filters"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 25]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} appointments"
        >
          <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
              <h4 class="m-0">Manage Appointments</h4>
              <IconField>
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText v-model="filters['global'].value" placeholder="Search..." />
              </IconField>
            </div>
          </template>
  
          <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
          <Column field="appointment_date" header="Date" sortable style="min-width: 12rem"></Column>
          <Column field="patient" header="Patient" sortable style="min-width: 16rem">
            <template #body="slotProps">
              {{ slotProps.data.patient?.firstName }} {{ slotProps.data.patient?.lastName }}
            </template>
          </Column>
          <Column field="reason_for_visit" header="Reason for visit" sortable style="min-width: 12rem"></Column>
          <Column field="status" header="Status" sortable style="min-width: 10rem">
            <template #body="slotProps">
              <Tag :value="slotProps.data.status.value || slotProps.data.status" :severity="getStatusLabel(slotProps.data.status.value || slotProps.data.status)" />
            </template>
          </Column>
          <Column :exportable="false" style="min-width: 12rem">
            <template #body="slotProps">
                <Button icon="pi pi-eye" outlined rounded  @click="showAppointment(slotProps.data)" />
              <Button icon="pi pi-pencil" outlined rounded severity="info" class="m-2" @click="editAppointment(slotProps.data)" />
              <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteAppointment(slotProps.data)" />

            </template>
          </Column>
        </DataTable>
      </div>
  
      <Dialog
  v-model:visible="appointmentDialog"
  :style="{ width: '800px' }"
  header="Appointment Details"
  :modal="true"
>
  <div class="flex flex-col gap-6">
    <!-- Appointment Date -->
    <div>
      <label for="appointment_date" class="block font-bold mb-3">Date</label>
      <DatePicker
        id="appointment_date"
        v-model="appointment.date"
        placeholder="Select a date"
        fluid
      />
    </div>

    <!-- Appointment Time -->
    <div>
      <label for="appointment_time" class="block font-bold mb-3">Time</label>
      <DatePicker
        id="appointment_time"
        v-model="appointment.time"
        placeholder="Select a time"
        fluid
        timeOnly
      />
    </div>

    <!-- Status -->
    <div>
      <label for="status" class="block font-bold mb-3">Status</label>
      <Select
        id="status"
        v-model="appointment.status"
        :options="statuses"
        optionLabel="label"
        placeholder="Select a Status"
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
      <Select
        id="patient"
        v-model="appointment.patient_id"
        :options="patients"
        optionLabel="name"
        placeholder="Select a Patient"
        fluid
      />
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
    <Button label="Save" icon="pi pi-check" @click="saveAppointment" />
  </template>
</Dialog>

  
      <Dialog v-model:visible="deleteAppointmentDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
        <div class="flex items-center gap-4">
          <i class="pi pi-exclamation-triangle !text-3xl" />
          <span v-if="appointment">Are you sure you want to delete this appointment?</span>
        </div>
        <template #footer>
          <Button label="No" icon="pi pi-times" text @click="deleteAppointmentDialog = false" />
         
            <!-- Add loading overlay -->
            <div v-if="isLoading" >
                <div class="h-full w-full "></div>
            <ProgressSpinner />
             </div>
        

          <Button label="Yes" icon="pi pi-check" @click="deleteAppointment" />
        </template>
      </Dialog>
      <div>
      <div v-if="isLoading" class="loading-overlay">
      <ProgressSpinner />
    </div>
  
      <Dialog v-model:visible="deleteAppointmentsDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
        <div class="flex items-center gap-4">
          <i class="pi pi-exclamation-triangle !text-3xl" />
          <span v-if="selectedAppointments.length">Are you sure you want to delete the selected appointments?</span>
        </div>
   
        <template #footer>
          <Button label="No" icon="pi pi-times" text @click="deleteAppointmentsDialog = false" />
          <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedAppointments" />
        </template>
      </Dialog>
    </div>
    </div>
   
 
   


  </template>
<style>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}
</style>