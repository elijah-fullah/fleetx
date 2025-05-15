



<script>
  
  document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector("#phone");
    const button = document.querySelector("#btn");
    const errorMsg = document.querySelector("#error-msg");
    const validMsg = document.querySelector("#valid-msg");

    // Error messages based on validation error codes
    const errorMap = [
        "Invalid number", 
        "Invalid country code", 
        "Too short", 
        "Too long", 
        "Invalid number"
    ];

    // Initialize intl-tel-input
    const iti = window.intlTelInput(input, {
        initialCountry: "sl",
        allowDropdown: true,  // Enables country dropdown
        autoPlaceholder: "aggressive",  // Auto-fill example format
        placeholderNumberType: "MOBILE",
        separateDialCode: false,
    });

    // Update max length when country is changed
    input.addEventListener("countrychange", function () {
        const countryData = iti.getSelectedCountryData();
        if (countryData) {
            // Get example number format
            const exampleNumber = iti.getNumberPlaceholder();
            input.placeholder = exampleNumber;

            // Set max length based on country format
            const numberLength = exampleNumber.replace(/\D/g, "").length; // Extract only digits
            input.setAttribute("maxlength", numberLength);
        }
    });

    // Reset validation messages
    const reset = () => {
        input.classList.remove("error");
        errorMsg.innerHTML = "";
        errorMsg.classList.add("hide");
        validMsg.classList.add("hide");
    };

    const showError = (msg) => {
        input.classList.add("error");
        errorMsg.innerHTML = msg;
        errorMsg.classList.remove("hide");
    };

    // Validate phone number on button click
    button.addEventListener('click', () => {
        reset();
        if (!input.value.trim()) {
            showError("Required");
        } else if (iti.isValidNumber()) {
            validMsg.classList.remove("hide");
        } else {
            const errorCode = iti.getValidationError();
            const msg = errorMap[errorCode] || "Invalid number";
            showError(msg);
        }
    });

    // Reset error messages on input change
    input.addEventListener('change', reset);
    input.addEventListener('keyup', reset);
});

  const setup = () => {
    const getTheme = () => {
      if (window.localStorage.getItem('dark')) {
        return JSON.parse(window.localStorage.getItem('dark'))
      }

      return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    const setTheme = (value) => {
      window.localStorage.setItem('dark', value)
    }

    const getColor = () => {
      if (window.localStorage.getItem('color')) {
        return window.localStorage.getItem('color')
      }
      return 'cyan'
    }

    const setColors = (color) => {
      const root = document.documentElement
      root.style.setProperty('--color-primary', `var(--color-${color})`)
      root.style.setProperty('--color-primary-50', `var(--color-${color}-50)`)
      root.style.setProperty('--color-primary-100', `var(--color-${color}-100)`)
      root.style.setProperty('--color-primary-light', `var(--color-${color}-light)`)
      root.style.setProperty('--color-primary-lighter', `var(--color-${color}-lighter)`)
      root.style.setProperty('--color-primary-dark', `var(--color-${color}-dark)`)
      root.style.setProperty('--color-primary-darker', `var(--color-${color}-darker)`)
      this.selectedColor = color
      window.localStorage.setItem('color', color)
      //
    }

    const updateBarChart = (on) => {
      const data = {
        data: randomData(),
        backgroundColor: 'rgb(207, 250, 254)',
      }
      if (on) {
        barChart.data.datasets.push(data)
        barChart.update()
      } else {
        barChart.data.datasets.splice(1)
        barChart.update()
      }
    }

    const updateDoughnutChart = (on) => {
      const data = random()
      const color = 'rgb(207, 250, 254)'
      if (on) {
        doughnutChart.data.labels.unshift('Seb')
        doughnutChart.data.datasets[0].data.unshift(data)
        doughnutChart.data.datasets[0].backgroundColor.unshift(color)
        doughnutChart.update()
      } else {
        doughnutChart.data.labels.splice(0, 1)
        doughnutChart.data.datasets[0].data.splice(0, 1)
        doughnutChart.data.datasets[0].backgroundColor.splice(0, 1)
        doughnutChart.update()
      }
    }

    const updateLineChart = () => {
      lineChart.data.datasets[0].data.reverse()
      lineChart.update()
    }

    return {
      loading: true,
      isDark: getTheme(),
      toggleTheme() {
        this.isDark = !this.isDark
        setTheme(this.isDark)
      },
      setLightTheme() {
        this.isDark = false
        setTheme(this.isDark)
      },
      setDarkTheme() {
        this.isDark = true
        setTheme(this.isDark)
      },
      color: getColor(),
      selectedColor: 'cyan',
      setColors,
      toggleSidbarMenu() {
        this.isSidebarOpen = !this.isSidebarOpen
      },
      isSettingsPanelOpen: false,
      openSettingsPanel() {
        this.isSettingsPanelOpen = true
        this.$nextTick(() => {
          this.$refs.settingsPanel.focus()
        })
      },
      isNotificationsPanelOpen: false,
      openNotificationsPanel() {
        this.isNotificationsPanelOpen = true
        this.$nextTick(() => {
          this.$refs.notificationsPanel.focus()
        })
      },
      isSearchPanelOpen: false,
      openSearchPanel() {
        this.isSearchPanelOpen = true
        this.$nextTick(() => {
          this.$refs.searchInput.focus()
        })
      },
      isMobileSubMenuOpen: false,
      openMobileSubMenu() {
        this.isMobileSubMenuOpen = true
        this.$nextTick(() => {
          this.$refs.mobileSubMenu.focus()
        })
      },
      isMobileMainMenuOpen: false,
      openMobileMainMenu() {
        this.isMobileMainMenuOpen = true
        this.$nextTick(() => {
          this.$refs.mobileMainMenu.focus()
        })
      },
      updateBarChart,
      updateDoughnutChart,
      updateLineChart,
    }
  }
</script>

<!-- Boxicons  Starts-->
<script src="https://kit.fontawesome.com/4dc50d1656.js" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 
</body>

</html>