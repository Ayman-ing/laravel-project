<script setup>
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';



const {patients,
  fetchPatients,
} = usePatients();

const isLoading = ref(false);
const blocked = ref(false);
const isFetching = ref(false);
const rowsPerPage = ref(5);
const currentPage = ref(1);
const sortField = ref('');
const sortOrder = ref('');
const patientDialog = ref(false);
const newPatientDialog = ref(false);

onMounted(async () => {
   await fetchPatients() // Trigger patient fetch after patients
});
const onPageChange = (event) => {
  currentPage.value = event.page + 1; // PrimeVue uses zero-based indexing
  rowsPerPage.value = event.rows;
  fetchPatients(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
};

const onSortChange = (event) => {
  sortField.value = event.sortField;
  sortOrder.value = event.sortOrder > 0 ? 'asc' : 'desc'; // PrimeVue uses 1 for ascending and -1 for descending
    fetchPatients(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
};

const toast = useToast();
const dt = ref();

const deletePatientDialog = ref(false);
const deletePatientsDialog = ref(false);
const patient = ref({});
const selectedPatients = ref([]);

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);


function confirmDeletePatient(appt) {
  patient.value = appt;
  deletePatientDialog.value = true;
}

async function deletePatient() {
    
  await patientService.deletepatient(patient.value.id);
  
  patients.value = patients.value.filter((val) => val.id !== patient.value.id);
  deletePatientDialog.value = false;
  patient.value = {};
  
  toast.add({ severity: 'success', summary: 'Successful', detail: 'patient Deleted', life: 3000 });
  
}



function exportCSV() {
  dt.value.exportCSV();
}

function confirmDeleteSelected() {
  deletePatientsDialog.value = true;
}

async function deleteSelectedPatients() {
    blocked.value = true;
    isLoading.value = true;
  const idsToDelete = selectedPatients.value.map((appt) => appt.id);
  for (const id of idsToDelete) {
    await patientService.deletePatient(id);
  }
  blocked.value = false;  
  patients.value = patients.value.filter((appt) => !selectedPatients.value.includes(appt));
  
  deletePatientsDialog.value = false;
  selectedPatients.value = [];
  
  isLoading.value = false;
  
  toast.add({ severity: 'success', summary: 'Successful', detail: 'patients Deleted', life: 3000 });
  
}



function editPatient(appt) {
  patient.value = { ...appt };
  patientDialog.value = true;
}
function openNew() {
  patient.value = {};
  submitted.value = false;
  newPatientDialog.value = true;
}

function hideDialog() {
  patientDialog.value = false;
  newPatientDialog.value = false;
  submitted.value = false;
 
}
</script>


<template>
    <div>
      <div class="card">
        <Toolbar class="mb-2">
          <template #start>
            <Button label="New" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
            <Button label="Delete" icon="pi pi-trash" severity="secondary" @click="confirmDeleteSelected" :disabled="!selectedPatients.length" />
          </template>
  
          <template #end>
            <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
          </template>
        </Toolbar>
        <div v-if="!isFetching">
        <DataTable 
          size="small"
          ref="dt"
          v-model:selection="selectedPatients"
          :value="patients"
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
          
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} patients"
        >
          <template #header>
            
            <div class="flex flex-wrap gap-2 pb-2 items-center justify-between">
              <h4 class="m-0">Manage patients</h4>
              <IconField>
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText v-model="filters['global'].value" placeholder="Search..." />
              </IconField>
            </div>
          </template>
  
          <Column selectionMode="multiple" style="width: 3rem"  :exportable="false"></Column>
        
          <Column field="name" header="Patient Name" sortable style="min-width: 12rem">
            <template #body="slotProps">
           

              {{ slotProps.data.name }} 
            </template>
          </Column>
          <Column field="sex" header="Sex " sortable style="min-width: 6rem"></Column>
          <Column field="phone" header="Phone Number" sortable style="min-width: 12rem">
            <template #body="slotProps">
              {{ slotProps.data.phone }} 
            </template>
          </Column>
          <Column field="address" header="Address" sortable style="min-width: 16rem"></Column>

          <Column field="email" header="Email" sortable style="min-width: 14rem">
          </Column>

          <Column :exportable="false" style="min-width: 12rem">
            <template #body="slotProps">
            

              <nuxt-link :to="`/patients/${slotProps.data.id}`" >
                <Button icon="pi pi-eye" outlined rounded  />
            </nuxt-link>
              <Button icon="pi pi-pencil" outlined rounded severity="info" class="m-2" @click="editPatient(slotProps.data)" />
              <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeletePatient(slotProps.data)" />

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
      <patientCreate v-model:visible="newPatientDialog" v-on:hide-dialog="hideDialog" />
      <patientEdit v-model:visible="patientDialog" v-on:hide-dialog="hideDialog" :patient="patient" />

      <Dialog v-model:visible="deletePatientDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
        <div class="flex items-center gap-4">
          <i class="pi pi-exclamation-triangle !text-3xl" />
          <span v-if="patient">Are you sure you want to delete this patient?</span>
        </div>
        <template #footer>
          <Button label="No" icon="pi pi-times" text @click="deletePatientDialog = false" />
         
            <!-- Add loading overlay -->
            <div v-if="isLoading" >
                <div class="h-full w-full "></div>
            <ProgressSpinner />
             </div>
        

          <Button label="Yes" icon="pi pi-check" @click="deletePatient" />
        </template>
      </Dialog>
      <div>
      <div v-if="isLoading" class="loading-overlay">
      <ProgressSpinner />
    </div>
  
      <Dialog v-model:visible="deletePatientsDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
        <div class="flex items-center gap-4">
          <i class="pi pi-exclamation-triangle !text-3xl" />
          <span v-if="selectedPatients.length">Are you sure you want to delete the selected patients?</span>
        </div>
   
        <template #footer>
          <Button label="No" icon="pi pi-times" text @click="deletePatientsDialog = false" />
          <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedPatients" />
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