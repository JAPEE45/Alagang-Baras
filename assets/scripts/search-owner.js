let owners = [
  { id: 1, name: "Hannah Denielle Teodoro" },
  { id: 2, name: "Christian Jireh Briol" },
  { id: 3, name: "Hannah loves Christian" },
];

const select = document.getElementById("liveStockSelect")
let liveStock = []
let selectedLiveStock = {}
fetch('../../helper/getAllOwnerIdName.php')
  .then(e=> e.json())
  .then(e => owners = e)
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
      fetch(`../../helper/getLiveStockByOwnerId.php?owner_id=${owner.id}`)
        .then(e=>e.json())
        .then(e=>{
          console.log(e.data)
          liveStock = e.data
          select.innerHTML = '<option class="option-control"  selected disabled>--Select Livestock--</option>'
          e.data.forEach(c=>{
            const node = document.createElement("option")
            node.value = c.id
            node.innerHTML = `species: ${c.species} - breed: ${c.breed}`
            select.append(node)
            console.log(node)
          })

        })
        .catch(e=> console.log(e))
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

  select.addEventListener("change", e=>{
              // alert(e.target.children[1].value)
              const b = liveStock.filter(e=> e.id == select.value)
              console.log(b)
              document.getElementById("search-species").value = b[0].species 
              document.getElementById("search-breed").value = b[0].breed 
              selectedLiveStock = b[0]
            })