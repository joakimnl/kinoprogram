const body = document.querySelector("body");
body.classList.remove("no-js");

const cityPicker = document.querySelector("#select-city");
const datePicker = document.querySelector("#select-date");

cityPicker.addEventListener('change', (event) => {
  location.href = "/" + event.target.value + "/" + datePicker.value;
});

datePicker.addEventListener('change', (event) => {
  location.href = "/" + event.target.getAttribute("data-cinema") + "/" + event.target.value;
});