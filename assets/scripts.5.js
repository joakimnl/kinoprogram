const body = document.querySelector("body");
body.classList.remove("no-js");

const locationPicker = document.querySelector("#select-location");
const datePicker = document.querySelector("#select-date");

const cinemaFilter = document.getElementById("cinema-filter");
const results = document.getElementById("cinema-locations");
const locationElements = document.querySelectorAll("#cinema-locations a");
const locations = [...locationElements].map((l) => ({
  href: l.getAttribute("href"),
  name: l.textContent,
}));

if (cinemaFilter) {
  cinemaFilter.addEventListener("keyup", function (e) {
    const items = results.querySelectorAll("li");
    if (this.value.length > 1) {
      items.forEach((item) => {
        item.classList.add("hide");
      });
      const filteredLocations = locations.filter((l) =>
        l.name.toLowerCase().includes(this.value.toLowerCase()),
      );
      filteredLocations.forEach((location) => {
        const item = results.querySelector(`li:has(a[href="${location.href}"])`);
        item.classList.toggle("hide");
      });
    } else {
      items.forEach((item) => {
        item.classList.remove("hide");
      });
    }
  });
}

if (locationPicker) {
  locationPicker.addEventListener("change", (event) => {
    location = "/" + event.target.value + "/" + datePicker.value;
  });
}

if (datePicker) {
  datePicker.addEventListener("change", (event) => {
    location = "/" + locationPicker.value + "/" + event.target.value;
  });
  datePicker.value = datePicker.getAttribute("data-current-date");
}
