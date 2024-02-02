const body = document.querySelector("body");
body.classList.remove("no-js");

const locationPicker = document.querySelector("#select-location");
const datePicker = document.querySelector("#select-date");

locationPicker.addEventListener('change', (event) => {
  location.href = "/" + event.target.value + "/" + datePicker.value;
});

datePicker.addEventListener('change', (event) => {
  location.href = "/" + locationPicker.value + "/" + event.target.value;
});