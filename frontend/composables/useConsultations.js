import { ConsultationService } from '~/services/consultationService';
const consultations = ref([]);
const totalRecords = ref(0);
const isFetchingConsultations = ref(false);
export function useConsultations() {
  

  // Fetch consultations with pagination
  const fetchConsultations = async (page = 1, rows = 5, sortField = '', sortOrder = '') => {
    isFetchingConsultations.value = true;
    try {
      const response = await ConsultationService.getConsultationsPaginated(page, rows, sortField, sortOrder);
      consultations.value = response.data.map((consultation) => ({
        ...consultation,
        time: consultation.time.slice(0, 5),
      }));
      totalRecords.value = response.total;
    } catch (error) {
      console.error('Error fetching consultations:', error);
    } finally {
      isFetchingConsultations.value = false;
    }
  };

  // Add a new consultation
  const addConsultation = (newConsultation) => {
    consultations.value.push(newConsultation);
  };

  // Update an existing consultation
  const updateConsultation = (updatedConsultation) => {
    const index = consultations.value.findIndex((appt) => appt.id === updatedConsultation.id);
    if (index !== -1) consultations.value[index] = updatedConsultation;
  };

  // Remove an consultation
  const removeConsultation = (id) => {
    consultations.value = consultations.value.filter((appt) => appt.id !== id);
  };

  return {
    consultations,
    totalRecords,
    isFetchingConsultations,
    fetchConsultations,
    addConsultation,
    updateConsultation,
    removeConsultation,
  };
}
