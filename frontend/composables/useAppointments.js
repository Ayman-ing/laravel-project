import { AppointmentService } from '~/services/appointmentService';
const appointments = ref([]);
const totalRecords = ref(0);
const isFetchingAppointments = ref(false);
export function useAppointments() {
  

  // Fetch appointments with pagination
  const fetchAppointments = async (page = 1, rows = 5, sortField = '', sortOrder = '',filterName='') => {
    isFetchingAppointments.value = true;
    try {
      const response = await AppointmentService.getAppointmentsPaginated(page, rows, sortField, sortOrder,filterName);
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



  return {
    appointments,
    totalRecords,
    isFetchingAppointments,
    fetchAppointments,

  };
}
