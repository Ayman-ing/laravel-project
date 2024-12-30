const API_URL = 'http://localhost:8000/api';

export const PatientService = {
  // Fetch all patients
  async getPatientsNames() {
    try {
    const response = await $fetch(`${API_URL}/patientsNames`);
   
    return response;
    } catch (error) {
      console.error('Error fetching patients names:', error)
    }
  
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
   


    const flattenedPatient = {
      firstName: patient.firstName, 
      lastName: patient.lastName, 
      birthDate: patient.birthDate,
      sex :  patient.sex,
      phone: patient.phone,
      email: patient.email,
      address: patient.address,
      civilization: patient.civilization,
      groupS : patient.groupS,
      

      
    };

    

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
