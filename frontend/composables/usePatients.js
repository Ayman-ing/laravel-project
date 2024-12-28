import { PatientService } from '~/services/patientService';
const patients = ref([]);
const isLoadingPatients = ref(false);
export function usePatients() {
  
 

  // Fetch all patients
  const fetchPatients = async () => {
    isLoadingPatients.value = true;
    try {
      const fetchedPatients = await PatientService.getPatients();
      patients.value = fetchedPatients.map((patient) => ({
        ...patient,
        name: `${patient.firstName} ${patient.lastName}`,
      }));
    } catch (error) {
      console.error('Error fetching patients:', error);
    } finally {
      isLoadingPatients.value = false;
    }
  };

  return {
    patients,
    isLoadingPatients,
    fetchPatients,
  };
}
