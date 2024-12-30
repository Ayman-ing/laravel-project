const API_URL = 'http://localhost:8000/api';

export const ConsultationService = {
  // Fetch all appointments
  async getConsultations() {
    return await $fetch(`${API_URL}/consultations`);
  },

  // Fetch all appointments paginated
  async getConsultationsPaginated(page = 1, rows = 5, sortField = '', sortOrder = '') {
    const query = new URLSearchParams({
      page,
      rows,
      sortField,
      sortOrder,
    }).toString();

    return await $fetch(`${API_URL}/consultations?${query}`);
  },

  // Fetch a single appointment
  async getConsultation(id) {
    return await $fetch(`${API_URL}/consultations/${id}`);
  },

  // Create a new appointment
  async createConsultation(consultation) {
    return await $fetch(`${API_URL}/consultations`, {
      method: 'POST',
      body: consultation,
    });
  },

  async updateConsultation(id, consultation) {
    // Flatten the payload
    const flattenedConsultation = {
      appointment_id:consultation.appointment_id,
      patient_id:consultation.patient_id,
      date:consultation.date,
      time:consultation.time,
      duration:consultation.duration,
      symptoms:consultation.symptoms,
      diagnosis:consultation.diagnosis,
      treatment_plan:consultation.treatment_plan,
      prescription:consultation.prescription,
      test_results:consultation.test_results,
      referrals:consultation.referrals,
      consultation_notes:consultation.consultation_notes,
    };
  
    
  
    try {
      return await $fetch(`${API_URL}/consultations/${id}`, {
        method: 'PUT',
        body: flattenedConsultation,
        headers: {
          'Content-Type': 'application/json',
        },
      });
    } catch (error) {
      console.error('Error updating consultation:', error);
      throw error;
    }
  },
  
  // Delete an appointment
  async deleteConsultation(id) {
    return await $fetch(`${API_URL}/consultations/${id}`, {
      method: 'DELETE',
    });
  },
};
