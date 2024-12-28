const API_URL = 'http://localhost:8000/api';

export const HomeService = {
  // Fetch today's appointments
  async fetchTodayAppointments() {
    try {
      const response = await $fetch(`${API_URL}`);
      console.log(response);
      return response; // Return the data fetched from the API
    } catch (error) {
      console.error('Error fetching today\'s appointments:', error);
      throw error; // Throw the error for handling in the component
    }
  },
};
