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
document.addEventListener("DOMContentLoaded", function () {
  const allSelectors = document.querySelectorAll(
    ".role-selector-container, .role-selector-container-register",
  );

  allSelectors.forEach((roleSelector) => {
    const selectedText = roleSelector.querySelector(
      "#selected-role-text-register, #selected-role-text",
    );
    const hiddenInput = roleSelector.querySelector(
      "#user-role-input-register, #user-role-input",
    );
    const options = roleSelector.querySelectorAll(
      ".role-option-register, .role-option",
    );

    // 1. Abrir/cerrar menú
    roleSelector.addEventListener("click", function (e) {
      if (e.target.closest(".role-option-register, .role-option")) return;
      this.classList.toggle("open");
    });

    // 2. Seleccionar opción
    options.forEach((option) => {
      option.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const selectedRoleName = this.textContent.trim();
        const selectedRoleValue =
          this.getAttribute("data-role-register") ||
          this.getAttribute("data-role");

        selectedText.textContent = selectedRoleName;
        hiddenInput.value = selectedRoleValue;

        roleSelector.classList.add("selected");
        roleSelector.classList.remove("open");
      });
    });
  });

  // 3. Cerrar al clic fuera
  document.addEventListener("click", function (e) {
    const allSelectors = document.querySelectorAll(
      ".role-selector-container, .role-selector-container-register",
    );
    allSelectors.forEach((selector) => {
      if (!selector.contains(e.target)) {
        selector.classList.remove("open");
      }
    });
  });
});
