const owners = [
  { id: 1, name: "Hannah Denielle Teodoro" },
  { id: 2, name: "Christian Jireh Briol" },
  { id: 3, name: "Hannah loves Christian" },
];

const searchOwnerInput = document.getElementById("search-owner");
const ownerIdInput = document.getElementById("owner-id");
const suggestionsBox = document.getElementById("owner-suggestions");

searchOwnerInput.addEventListener("input", function () {
  const query = this.value.trim().toLowerCase();
  suggestionsBox.innerHTML = "";

  if (query.length === 0) {
    suggestionsBox.style.display = "none";
    return;
  }

  const filteredOwners = owners.filter((owner) =>
    owner.name.toLowerCase().includes(query)
  );

  if (filteredOwners.length === 0) {
    suggestionsBox.style.display = "none";
    return;
  }

  filteredOwners.forEach((owner) => {
    const li = document.createElement("li");
    li.classList.add(
      "list-group-item",
      "list-group-item-action",
      "d-flex",
      "justify-content-between",
      "align-items-center"
    );
    li.innerHTML = `
        <span>${owner.name}</span>
      `;

    li.addEventListener("click", function () {
      searchOwnerInput.value = owner.name;
      ownerIdInput.value = owner.id;
      suggestionsBox.style.display = "none";
    });

    suggestionsBox.appendChild(li);
  });

  suggestionsBox.style.display = "block";
});

document.addEventListener("click", function (e) {
  if (
    !searchOwnerInput.contains(e.target) &&
    !suggestionsBox.contains(e.target)
  ) {
    suggestionsBox.style.display = "none";
  }
});
