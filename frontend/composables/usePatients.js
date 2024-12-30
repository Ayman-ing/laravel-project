import { PatientService } from '~/services/patientService';
const patients = ref([]);
const totalRecords = ref(0);

const isLoadingPatients = ref(false);
export function usePatients() {
  
 

  // Fetch all patients
  const fetchPatients = async (page = 1, rows = 5, sortField = '', sortOrder = '') => {
    isLoadingPatients.value = true;
    if (sortField === 'name') {
      sortField = 'firstName';
    }
    try {
      const fetchedPatients = await PatientService.getPatientsPaginated(page, rows, sortField, sortOrder);
      patients.value = fetchedPatients.data.map((patient) => ({
        ...patient,
        name: `${patient.firstName} ${patient.lastName}`,
      }));
      totalRecords.value = fetchedPatients.total;
    } catch (error) {
      console.error('Error fetching patients:', error);
    } finally {
      isLoadingPatients.value = false;
    }
  };

  return {
    patients,
    totalRecords,
    isLoadingPatients,
    fetchPatients,
  };
}
