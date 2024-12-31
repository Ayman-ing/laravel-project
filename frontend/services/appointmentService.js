const API_URL = 'http://localhost:8000/api';

export const AppointmentService = {
  // Fetch all appointments
  async getAppointments() {
    return await $fetch(`${API_URL}/appointments`);
  },
  // Fetch all appointments paginated
  async getAppointmentsPaginated(page = 1, rows = 5, sortField = '', sortOrder = '',filterName='') {
    const query = new URLSearchParams({
      page,
      rows,
      sortField,
      sortOrder,
      filterName,
    }).toString();

    return await $fetch(`${API_URL}/appointments?${query}`);
  },

  // Fetch a single appointment
  async getAppointment(id) {
    return await $fetch(`${API_URL}/appointments/${id}`);
  },

  // Create a new appointment
  async createAppointment(appointment) {
    const [hour,minute] = appointment.time.split(':')
    appointment.time = hour + ':' + minute
    const flattenedAppointment = {
      patient_id: appointment.patient_id, // Use the ID directly
      date: appointment.date,
      time: appointment.time,
      status: appointment.status.value,
      reason_for_visit: appointment.reason_for_visit,
      notes: appointment.notes,
      total_fee: appointment.total_fee,
      canceled_at: appointment.canceled_at,
      cancellation_reason: appointment.cancellation_reason,
    };
    return await $fetch(`${API_URL}/appointments`, {
      method: 'POST',
      body: flattenedAppointment,
    });
  },

  async updateAppointment(id, appointment) {
    const [hour,minute] = appointment.time.split(':')
    appointment.time = hour + ':' + minute
   
 
    // Flatten the payload
    const flattenedAppointment = {
      patient_id: appointment.patient_id, // Use the ID directly
      date: appointment.date,
      time: appointment.time,
      status: appointment.status.value, 
      reason_for_visit: appointment.reason_for_visit,
      notes: appointment.notes,
      total_fee: appointment.total_fee,
      canceled_at: appointment.canceled_at,
      cancellation_reason: appointment.cancellation_reason,
    };
  
    
  
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
  async deleteAppointment(id) {
    return await $fetch(`${API_URL}/appointments/${id}`, {
      method: 'DELETE',
    });
  },
};
