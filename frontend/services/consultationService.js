const API_URL = 'http://localhost:8000/api';

export const ConsultationService = {
  // Fetch all appointments
  async getConsultations() {
    return await $fetch(`${API_URL}/appointments`);
  },

  // Fetch a single appointment
  async getConsultation(id) {
    return await $fetch(`${API_URL}/appointments/${id}`);
  },

  // Create a new appointment
  async createConsultation(appointment) {
    return await $fetch(`${API_URL}/appointments`, {
      method: 'POST',
      body: appointment,
    });
  },

  async updateConsultation(id, appointment) {
    // Flatten the payload
    const flattenedAppointment = {
      patient_id: appointment.patient_id, // Use the ID directly
      appointment_date: appointment.appointment_date,
      status: appointment.status.value, 
      reason_for_visit: appointment.reason_for_visit,
      notes: appointment.notes,
      total_fee: appointment.total_fee,
      cancelation_date: appointment.cancelation_date,
      cancellation_reason: appointment.cancellation_reason,
    };
  
    console.log('Flattened payload:', flattenedAppointment);
  
    try {
      return await $fetch(`${API_URL}/appointments/${id}`, {
        method: 'PUT',
        body: flattenedAppointment,
        headers: {
          'Content-Type': 'application/json',
        },
      });
    } catch (error) {
      console.error('Error updating appointment:', error);
      throw error;
    }
  },
  
  // Delete an appointment
  async deleteConsultation(id) {
    return await $fetch(`${API_URL}/appointments/${id}`, {
      method: 'DELETE',
    });
  },
};
