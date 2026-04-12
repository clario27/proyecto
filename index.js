const btn = document.getElementById("btnOpciones");
const menu = document.getElementById("panelOpciones");

btn.addEventListener("click", function (e) {
  e.stopPropagation();
  menu.classList.toggle("show");
});

document.addEventListener("click", function () {
  if (menu.classList.contains("show")) {
    menu.classList.remove("show");
  }
});
