<script setup>
import { AppointmentService } from '~/services/appointmentService';
import { PatientService } from '~/services/patientService';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';



const appointments = ref([]);
const totalRecords = ref(0);
const isLoading = ref(false);
const blocked = ref(false);
const disabled = ref(false);
const isFetching = ref(false);
const rowsPerPage = ref(5);
const currentPage = ref(1);
const sortField = ref('');
const sortOrder = ref('');
const isLoadingPatients = ref(false);
const fetchAppointments = async (page, rows, field = '', order = '') => {
  
  try {
    isFetching.value = true;
    const response = await AppointmentService.getAppointmentsPaginated(page, rows, field, order);
    appointments.value = response.data.map((appointment) => {
      return { ...appointment, time: appointment.time.slice(0, 5) };
    });
    totalRecords.value = response.total;
  } catch (error) {
    console.error('Error fetching paginated appointments:', error);
  } finally {
    isFetching.value = false;
  }
};
// Fetch the first page on mount
const fetchPatientsAsync = async () => {
  isLoadingPatients.value = true;
  try {

    const fetchedPatients = await PatientService.getPatients();
    patients.value = fetchedPatients.map((patient) => ({
    ...patient,
    name: `${patient.firstName} ${patient.lastName}`, // Combine firstName and lastName
  }));
  } catch (error) {
    console.error('Error loading patients:', error);
  } finally {
    isLoadingPatients.value = false;
  }
};
onMounted(async () => {
  await fetchAppointments(currentPage.value, rowsPerPage.value);
  fetchPatientsAsync() // Trigger patient fetch after appointments
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

console.log(appointments.value);
const appointmentDialog = ref(false);
const deleteAppointmentDialog = ref(false);
const deleteAppointmentsDialog = ref(false);
const appointment = ref({});
const selectedAppointments = ref([]);
const patients = ref([]);
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);
const status = ref([
  { label: 'pending', value:  'pending' },
  { label: 'confirmed', value: 'confirmed' },
  { label: 'in_progress', value: 'in_progress' },
  { label: 'completed', value: 'completed' },
  { label: 'canceled', value: 'canceled' },
  { label: 'no_show', value: 'no_show' },
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
  disabled.value = true;
  if (appointment?.value.date) {
    if (appointment.value.id) {
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
    } else {
      // Create a new appointment
      let newAppointment = null;
      try {
      newAppointment = await AppointmentService.createAppointment(appointment.value);
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
      }
      appointments.value.push(newAppointment);
      fetchAppointments(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
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
                <Button icon="pi pi-eye" outlined rounded  @click="showAppointment(slotProps.data)" />
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
  
      <Dialog
  v-model:visible="appointmentDialog"
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