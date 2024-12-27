
export function useDarkMode() {
  const isDarkTheme = ref(false);

  function toggleDarkMode() {
    isDarkTheme.value = !isDarkTheme.value;

    if (typeof document !== 'undefined') {
      // Toggle both classes for Tailwind (dark) and PrimeVue (my-app-dark)
      const rootElement = document.documentElement;
      rootElement.classList.toggle('dark', isDarkTheme.value);
      rootElement.classList.toggle('my-app-dark', isDarkTheme.value);

      // Save the preference in localStorage
      localStorage.setItem('darkMode', isDarkTheme.value ? 'true' : 'false');
    }
  }

  function loadDarkModePreference() {
    if (typeof document !== 'undefined') {
      const savedPreference = localStorage.getItem('darkMode') === 'true';
      isDarkTheme.value = savedPreference;

      // Apply classes based on saved preference
      const rootElement = document.documentElement;
      rootElement.classList.toggle('dark', savedPreference);
      rootElement.classList.toggle('my-app-dark', savedPreference);
    }
  }

  return {
    isDarkTheme,
    toggleDarkMode,
    loadDarkModePreference,
  };
}
