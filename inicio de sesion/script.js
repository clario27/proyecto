const container = document.querySelector(".container");
const btnSingIn = document.getElementById("btn-sign-in");
const btnSingUp = document.getElementById("btn-sign-up");

btnSingIn.addEventListener("click", () => {
  container.classList.remove("toggle");
});

btnSingUp.addEventListener("click", () => {
  container.classList.add("toggle");
});

const checkbox = document.getElementById("check1");
const passInput = document.getElementById("password");
checkbox.addEventListener("change", function () {
  if (this.checked) {
    passInput.type = "text";
  } else {
    passInput.type = "password";
  }
});

const checkbox2 = document.getElementById("check2");
const passInput2 = document.getElementById("contraseña");
const passInput3 = document.getElementById("verificar-contraseña");

checkbox2.addEventListener("change", function () {
  if (this.checked) {
    passInput2.type = "text";
    passInput3.type = "text";
  } else {
    passInput2.type = "password";
    passInput3.type = "password";
  }
});

const btnOpciones = document.getElementById("btnOpciones");
const panelOpciones = document.getElementById("panelOpciones");

btnOpciones.addEventListener("click", function () {
  this.classList.toggle("active");
  panelOpciones.classList.toggle("show");
});

window.addEventListener("click", function (e) {
  if (!btnOpciones.contains(e.target) && !panelOpciones.contains(e.target)) {
    btnOpciones.classList.remove("active");
    panelOpciones.classList.remove("show");
  }
});

/* menu de roles */

document.addEventListener("DOMContentLoaded", function () {
  const roleSelector = document.querySelector(".role-selector-container");
  const selectedText = document.getElementById("selected-role-text");
  const hiddenInput = document.getElementById("user-role-input");
  const options = document.querySelectorAll(".role-option");

  // 1. Alternar la apertura del menú al hacer clic en el selector
  roleSelector.addEventListener("click", function (e) {
    // Prevenir que el clic en una opción también cierre el menú prematuramente
    if (e.target.closest(".role-option")) return;
    this.classList.toggle("open");
  });

  // 2. Manejar la selección de una opción
  options.forEach((option) => {
    option.addEventListener("click", function (e) {
      e.preventDefault(); // Evitar que el enlace navegue

      const selectedRoleName = this.textContent;
      const selectedRoleValue = this.getAttribute("data-role");

      // Actualizar el texto visible
      selectedText.textContent = selectedRoleName;
      // Actualizar el input oculto para enviarlo con el formulario
      hiddenInput.value = selectedRoleValue;

      // Añadir una clase al contenedor para indicar que hay una selección
      roleSelector.classList.add("selected");

      // Cerrar el menú
      roleSelector.classList.remove("open");
    });
  });

  // 3. Cerrar el menú si se hace clic fuera de él
  document.addEventListener("click", function (e) {
    if (!roleSelector.contains(e.target)) {
      roleSelector.classList.remove("open");
    }
  });
});
