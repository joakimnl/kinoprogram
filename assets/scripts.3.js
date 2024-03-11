const body = document.querySelector("body");
body.classList.remove("no-js");

const locationPicker = document.querySelector("#select-location");
const datePicker = document.querySelector("#select-date");

locationPicker.addEventListener('change', (event) => {
  location = "/" + event.target.value + "/" + datePicker.value;
});

datePicker.addEventListener('change', (event) => {
  location = "/" + locationPicker.value + "/" + event.target.value;
});

datePicker.value = datePicker.getAttribute("data-current-date");