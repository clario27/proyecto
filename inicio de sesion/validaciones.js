document.addEventListener("DOMContentLoaded", () => {
  // --- LÓGICA DE LOGIN ---
  const loginForm = document.getElementById("loginForm");
  const emailLogin = document.getElementById("email");
  const passLogin = document.getElementById("password");

  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(emailLogin.value) || passLogin.value.trim().length < 8) {
        Swal.fire({
          icon: "error",
          title: "Datos incorrectos",
          text: "El correo o la contraseña no son válidos. Inténtalo de nuevo.",
          confirmButtonColor: "#3498db",
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "¡Bienvenido!",
          text: "Iniciando sesión...",
          showConfirmButton: false,
          timer: 1500,
        }).then(() => {
          loginForm.submit();
        });
      }
    });
  }

  // --- LÓGICA DE REGISTRO ---
  const registroForm = document.querySelector(".sing-up");

  if (registroForm) {
    const nombreInput = registroForm.querySelector('input[type="text"]');
    const emailInput = registroForm.querySelector('input[placeholder="Correo Electronico"]');
    const passwordInput = document.getElementById("contraseña");
    const confirmPasswordInput = document.getElementById("verificar-contraseña");
    const roleInput = document.getElementById("user-role-input"); // El input oculto

    // Escuchar clics en las opciones del dropdown para asignar el valor
    document.addEventListener("click", function (e) {
      if (e.target.classList.contains("role-option")) {
        e.preventDefault();
        const selectedRole = e.target.getAttribute("data-role");
        roleInput.value = selectedRole;
        
        // Opcional: Feedback visual en consola para debug
        console.log("Rol seleccionado:", selectedRole);
      }
    });

    registroForm.addEventListener("submit", (e) => {
      e.preventDefault();

      let valid = true;
      let tituloError = "";
      let mensajeError = "";

      const nombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      // VALIDACIONES
      if (!nombreRegex.test(nombreInput.value.trim())) {
        valid = false;
        tituloError = "Nombre inválido";
        mensajeError = "El nombre solo debe contener letras y espacios.";
      } else if (!emailRegex.test(emailInput.value.trim())) {
        valid = false;
        tituloError = "Correo inválido";
        mensajeError = "Por favor, ingresa un formato de correo electrónico válido.";
      } 
      // NUEVA VALIDACIÓN DE ROL
      else if (!roleInput.value || roleInput.value === "") {
        valid = false;
        tituloError = "Selecciona un Rol";
        mensajeError = "Debes elegir si eres Doctor o Secretaria para registrarte.";
      } 
      else if (passwordInput.value.length < 8) {
        valid = false;
        tituloError = "Contraseña corta";
        mensajeError = "La contraseña debe tener al menos 8 caracteres.";
      } else if (passwordInput.value !== confirmPasswordInput.value) {
        valid = false;
        tituloError = "Contraseñas diferentes";
        mensajeError = "Asegúrate de que ambas contraseñas coincidan.";
      }

      if (!valid) {
        Swal.fire({
          icon: "error",
          title: tituloError,
          text: mensajeError,
          confirmButtonText: "Corregir",
          confirmButtonColor: "#3498db",
          background: "#ffffff",
          customClass: { popup: "animated fadeInDown" },
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "¡Registro Exitoso!",
          text: "Bienvenido a la Asociación Cooperativa La Salle.",
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true,
          iconColor: "#2ecc71",
        }).then(() => {
          registroForm.submit();
        });
      }
    });
  }
});