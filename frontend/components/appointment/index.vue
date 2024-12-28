<script setup>
import { AppointmentService } from '~/services/appointmentService';
import { PatientService } from '~/services/patientService';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const {
  appointments,
    totalRecords,
    fetchAppointments,

} = useAppointments();
const {
  fetchPatients,
} = usePatients();

const isLoading = ref(false);
const blocked = ref(false);
const isFetching = ref(false);
const rowsPerPage = ref(5);
const currentPage = ref(1);
const sortField = ref('');
const sortOrder = ref('');
const appointmentDialog = ref(false);
const newAppointmentDialog = ref(false);

onMounted(async () => {
  await fetchAppointments(currentPage.value, rowsPerPage.value);
  fetchPatients() // Trigger patient fetch after appointments
});
const onPageChange = (event) => {
  currentPage.value = event.page + 1; // PrimeVue uses zero-based indexing
  rowsPerPage.value = event.rows;
  fetchAppointments(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
};

const onSortChange = (event) => {
  sortField.value = event.sortField;
  sortOrder.value = event.sortOrder > 0 ? 'asc' : 'desc'; // PrimeVue uses 1 for ascending and -1 for descending
  fetchAppointments(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
};

const toast = useToast();
const dt = ref();

const deleteAppointmentDialog = ref(false);
const deleteAppointmentsDialog = ref(false);
const appointment = ref({});
const selectedAppointments = ref([]);

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);


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



function exportCSV() {
  dt.value.exportCSV();
}

function confirmDeleteSelected() {
  deleteAppointmentsDialog.value = true;
}

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
    case 'pending':
      return 'secondary'; // Grey color for pending
    case 'confirmed':
      return 'info'; // Blue color for confirmed
    case 'in_progress':
      return 'success'; // Dark blue for in-progress
    case 'completed':
      return 'contrast'; // Green for completed
    case 'canceled':
      return 'danger'; // Red for canceled
    case 'no_show':
      return 'warn'; // Yellow for no-show
    default:
      return 'secondary'; // Default grey for unknown statuses
  }
}
function editAppointment(appt) {
  appointment.value = { ...appt };
  appointmentDialog.value = true;
}
function openNew() {
  appointment.value = {};
  submitted.value = false;
  newAppointmentDialog.value = true;
}

function hideDialog() {
  appointmentDialog.value = false;
  newAppointmentDialog.value = false;
  submitted.value = false;
 
}
</script>


<template>
    <div>
      <div class="card">
        <Toolbar class="mb-2">
          <template #start>
            <Button label="New" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
            <Button label="Delete" icon="pi pi-trash" severity="secondary" @click="confirmDeleteSelected" :disabled="!selectedAppointments.length" />
          </template>
  
          <template #end>
            <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
          </template>
        </Toolbar>
        <div v-if="!isFetching">
        <DataTable 
          size="small"
          ref="dt"
          v-model:selection="selectedAppointments"
          :value="appointments"
          dataKey="id"
          :paginator="true"
          :lazy="true"
          :rows="rowsPerPage"
          :totalRecords="totalRecords"
          :first="(currentPage - 1) * rowsPerPage"
          :sortField="sortField"
          :sortOrder="sortOrder === 'asc' ? 1 : -1"
          @page="onPageChange"
          @sort="onSortChange"
          :filters="filters"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} appointments"
        >
          <template #header>
            
            <div class="flex flex-wrap gap-2 pb-2 items-center justify-between">
              <h4 class="m-0">Manage Appointments</h4>
              <IconField>
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText v-model="filters['global'].value" placeholder="Search..." />
              </IconField>
            </div>
          </template>
  
          <Column selectionMode="multiple" style="width: 3rem"  :exportable="false"></Column>
          <Column field="date" header="date" sortable style="min-width: 8rem"></Column>
          <Column field="time" header="time" sortable style="min-width: 7rem"></Column>
          <Column field="patient.firstName" header="Patient" sortable style="min-width: 12rem">
            <template #body="slotProps">
           

              {{ slotProps.data.patient?.firstName }} {{ slotProps.data.patient?.lastName }}
            </template>
          </Column>
          <Column field="patient.phone" header="Phone Number" sortable style="min-width: 12rem">
            <template #body="slotProps">
              {{ slotProps.data.patient?.phone }} 
            </template>
          </Column>
          <Column field="reason_for_visit" header="Reason for visit" sortable style="min-width: 14rem">
          </Column>
          <Column field="status" header="Status" sortable style="min-width: 10rem">
            <template #body="slotProps">
              
              <Tag :value="slotProps.data.status.value || slotProps.data.status" :severity="getStatusLabel(slotProps.data.status.value || slotProps.data.status)" />
            </template>
          </Column>
          <Column :exportable="false" style="min-width: 12rem">
            <template #body="slotProps">
            

              <nuxt-link :to="`/appointments/${slotProps.data.id}`" >
                <Button icon="pi pi-eye" outlined rounded  />
            </nuxt-link>
              <Button icon="pi pi-pencil" outlined rounded severity="info" class="m-2" @click="editAppointment(slotProps.data)" />
              <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteAppointment(slotProps.data)" />

            </template>
          </Column>
        </DataTable>
      </div>
      <div v-else class="flex flex-col gap-6 opacity-12 pt-28 opacity-50">
        <Skeleton height="3rem"  borderRadius="16px" />
        <Skeleton height="3rem"  borderRadius="16px"/>
        <Skeleton height="3rem"  borderRadius="16px"/>
        <Skeleton height="3rem"  borderRadius="16px"/>
        <Skeleton height="3rem"  borderRadius="16px"/>
     
      </div>
      
      
      </div>
      <AppointmentCreate v-model:visible="newAppointmentDialog" v-on:hide-dialog="hideDialog" />
      <AppointmentEdit v-model:visible="appointmentDialog" v-on:hide-dialog="hideDialog" :appointment="appointment" />

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