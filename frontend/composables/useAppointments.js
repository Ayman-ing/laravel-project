import { AppointmentService } from '~/services/appointmentService';
const appointments = ref([]);
const totalRecords = ref(0);
const isFetchingAppointments = ref(false);
export function useAppointments() {
  

  // Fetch appointments with pagination
  const fetchAppointments = async (page = 1, rows = 5, sortField = '', sortOrder = '') => {
    isFetchingAppointments.value = true;
    try {
      const response = await AppointmentService.getAppointmentsPaginated(page, rows, sortField, sortOrder);
      appointments.value = response.data.map((appointment) => ({
        ...appointment,
        time: appointment.time.slice(0, 5),
      }));
      totalRecords.value = response.total;
    } catch (error) {
      console.error('Error fetching appointments:', error);
    } finally {
      isFetchingAppointments.value = false;
    }
  };

  // Add a new appointment
  const addAppointment = (newAppointment) => {
    appointments.value.push(newAppointment);
  };

  // Update an existing appointment
  const updateAppointment = (updatedAppointment) => {
    const index = appointments.value.findIndex((appt) => appt.id === updatedAppointment.id);
    if (index !== -1) appointments.value[index] = updatedAppointment;
  };

  // Remove an appointment
  const removeAppointment = (id) => {
    appointments.value = appointments.value.filter((appt) => appt.id !== id);
  };

  return {
    appointments,
    totalRecords,
    isFetchingAppointments,
    fetchAppointments,
    addAppointment,
    updateAppointment,
    removeAppointment,
  };
}
