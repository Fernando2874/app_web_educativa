/* global bootstrap: false */
(() => {
  'use strict'
  const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.forEach(tooltipTriggerEl => {
    new bootstrap.Tooltip(tooltipTriggerEl)
  })
})()

const togglebtn = document.getElementById("toggle-btn");
const sidebar = document.getElementById("sidebar");

togglebtn.addEventListener("click", ()=>{ /*cuando haga click en el boton se ejecuta la siguiente 
  funcion*/
  sidebar.classList.toggle("collapsed"); /*hace que el ancho del sidebar se reduzca*/
})

const contraseña = document.getElementById("password");
const checkbox = document.getElementById("see-password");

checkbox.addEventListener('click', () => {
  if (contraseña.type === "password") {
    contraseña.type = "text";
  } else {
    contraseña.type = "password";
  }
});