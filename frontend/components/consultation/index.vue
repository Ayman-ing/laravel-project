<script setup>
import { ConsultationService } from '~/services/consultationService';
import { PatientService } from '~/services/patientService';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const {
  consultations,
    totalRecords,
    fetchConsultations,

} = useConsultations();
const {
  fetchPatientsNames,
  getPatientNameById
} = usePatients();

const isLoading = ref(false);
const blocked = ref(false);
const isFetching = ref(false);
const rowsPerPage = ref(5);
const currentPage = ref(1);
const sortField = ref('');
const sortOrder = ref('');
const consultationDialog = ref(false);
const newConsultationDialog = ref(false);

onMounted(async () => {
  await fetchConsultations(currentPage.value, rowsPerPage.value);
  fetchPatientsNames();
   // Trigger patient fetch after consultations
});
const onPageChange = (event) => {
  currentPage.value = event.page + 1; // PrimeVue uses zero-based indexing
  rowsPerPage.value = event.rows;
  fetchConsultations(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
};

const onSortChange = (event) => {
  sortField.value = event.sortField;
  sortOrder.value = event.sortOrder > 0 ? 'asc' : 'desc'; // PrimeVue uses 1 for ascending and -1 for descending
  fetchConsultations(currentPage.value, rowsPerPage.value, sortField.value, sortOrder.value);
};

const toast = useToast();
const dt = ref();

const deleteConsultationDialog = ref(false);
const deleteConsultationsDialog = ref(false);
const consultation = ref({});
const selectedConsultations = ref([]);

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);


function confirmDeleteConsultation(appt) {
  consultation.value = appt;
  deleteConsultationDialog.value = true;
}

async function deleteConsultation() {
    
  await ConsultationService.deleteConsultation(consultation.value.id);
  
  consultations.value = consultations.value.filter((val) => val.id !== consultation.value.id);
  deleteConsultationDialog.value = false;
  consultation.value = {};
  
  toast.add({ severity: 'success', summary: 'Successful', detail: 'Consultation Deleted', life: 3000 });
  
}



function exportCSV() {
  dt.value.exportCSV();
}

function confirmDeleteSelected() {
  deleteConsultationsDialog.value = true;
}

async function deleteSelectedConsultations() {
    blocked.value = true;
    isLoading.value = true;
  const idsToDelete = selectedConsultations.value.map((appt) => appt.id);
  for (const id of idsToDelete) {
    await ConsultationService.deleteConsultation(id);
  }
  blocked.value = false;  
  consultations.value = consultations.value.filter((appt) => !selectedConsultations.value.includes(appt));
  
  deleteConsultationsDialog.value = false;
  selectedConsultations.value = [];
  
  isLoading.value = false;
  
  toast.add({ severity: 'success', summary: 'Successful', detail: 'Consultations Deleted', life: 3000 });
  
}


  
function editConsultation(appt) {
  consultation.value = { ...appt };
  consultationDialog.value = true;
}
function openNew() {
  consultation.value = {};
  submitted.value = false;
  newConsultationDialog.value = true;
}

function hideDialog() {
  consultationDialog.value = false;
  newConsultationDialog.value = false;
  submitted.value = false;
 
}
</script>


<template>
  <div>
    <div class="card">
      <Toolbar class="mb-2">
        <template #start>
          <Button label="New" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
          <Button label="Delete" icon="pi pi-trash" severity="secondary" @click="confirmDeleteSelected" :disabled="!selectedConsultations.length" />
        </template>

        <template #end>
          <Button label="Refresh" icon="pi pi-refresh" severity="secondary" class="mr-2" @click="fetchConsultations"/>

          <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
        </template>
      </Toolbar>

      <div v-if="!isFetching">
        <DataTable
          size="small"
          ref="dt"
          v-model:selection="selectedConsultations"
          :value="consultations"
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
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} consultations"
        >
          <template #header>
            <div class="flex flex-wrap gap-2 pb-2 items-center justify-between">
              <h4 class="m-0">Manage Consultations</h4>
              <IconField>
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText v-model="filters['global'].value" placeholder="Search..." />
              </IconField>
            </div>
          </template>

          <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
          <Column field="patient" header="Patient Name" sortable style="min-width: 10rem">
            <template #body="slotProps">
              <span>{{ slotProps.data.patient.firstName }} {{ slotProps.data.patient.lastName }}</span>
            </template>
          
          </Column>
          <Column field="date" header="Date" sortable style="min-width: 8rem"></Column>
          <Column field="time" header="Time" sortable style="min-width: 8rem">
        </Column>
          <Column field="duration" header="Duration" sortable style="min-width: 8rem">
            <template #body="slotProps">
              <span>{{ slotProps.data.duration }} min</span>
            </template></Column>
          <Column field="symptoms" header="Symptoms" sortable style="min-width: 16rem"></Column>
          <Column field="diagnosis" header="Diagnosis" sortable style="min-width: 12rem"></Column>

          <Column :exportable="false" style="min-width: 12rem">
            <template #body="slotProps">
              <nuxt-link :to="`/consultations/${slotProps.data.id}`">
                <Button icon="pi pi-eye" outlined rounded />
              </nuxt-link>
              <Button icon="pi pi-pencil" outlined rounded severity="info" class="m-2" @click="editConsultation(slotProps.data)" />
              <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteConsultation(slotProps.data)" />
            </template>
          </Column>
        </DataTable>
      </div>

      <div v-else class="flex flex-col gap-6 opacity-12 pt-28 opacity-50">
        <Skeleton height="3rem" borderRadius="16px" />
        <Skeleton height="3rem" borderRadius="16px" />
        <Skeleton height="3rem" borderRadius="16px" />
        <Skeleton height="3rem" borderRadius="16px" />
        <Skeleton height="3rem" borderRadius="16px" />
      </div>
    </div>

    <!-- Dialogues pour gérer les consultations -->
    <ConsultationCreate v-model:visible="newConsultationDialog" v-on:hide-dialog="hideDialog" />
    <ConsultationEdit v-model:visible="consultationDialog" v-on:hide-dialog="hideDialog" :consultation="consultation" />

    <Dialog v-model:visible="deleteConsultationDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
      <div class="flex items-center gap-4">
        <i class="pi pi-exclamation-triangle !text-3xl" />
        <span v-if="consultation">Are you sure you want to delete this consultation?</span>
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteConsultationDialog = false" />
        <Button label="Yes" icon="pi pi-check" @click="deleteConsultation" />
      </template>
    </Dialog>

    <Dialog v-model:visible="deleteConsultationsDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
      <div class="flex items-center gap-4">
        <i class="pi pi-exclamation-triangle !text-3xl" />
        <span v-if="selectedConsultations.length">Are you sure you want to delete the selected consultations?</span>
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteConsultationsDialog = false" />
        <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedConsultations" />
      </template>
    </Dialog>
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