const API_URL = 'http://localhost:8000/api';

export const PatientService = {
  // Fetch all patients
  async getPatients() {
    return await $fetch(`${API_URL}/patients`);
  },

  // Fetch all patients paginated
  async getPatientsPaginated(page = 1, rows = 5, sortField = '', sortOrder = '') {
    const query = new URLSearchParams({
      page,
      rows,
      sortField,
      sortOrder,
    }).toString();

    return await $fetch(`${API_URL}/patients?${query}`);
  },
  async getPatients() {
    return await $fetch(`${API_URL}/patients`);
  },


  // Fetch a single patient
  async getPatient(id) {
    return await $fetch(`${API_URL}/patients/${id}`);
  },

  // Create a new patient
  async createPatient(patient) {
    return await $fetch(`${API_URL}/patients`, {
      method: 'POST',
      body: patient,
    });
  },

  // Update an existing patient
  async updatePatient(id, patient) {
    // Flatten the payload if necessary
    const flattenedPatient = {
      first_name: patient.first_name,
      last_name: patient.last_name,
      date_of_birth: patient.date_of_birth,
      phone: patient.phone,
      email: patient.email,
      address: patient.address,
      notes: patient.notes,
    };

    console.log('Flattened payload:', flattenedPatient);

    try {
      return await $fetch(`${API_URL}/patients/${id}`, {
        method: 'PUT',
        body: flattenedPatient,
        headers: {
          'Content-Type': 'application/json',
        },
      });
    } catch (error) {
      console.error('Error updating patient:', error);
      throw error;
    }
  },

  // Delete a patient
  async deletePatient(id) {
    return await $fetch(`${API_URL}/patients/${id}`, {
      method: 'DELETE',
    });
  },
};
