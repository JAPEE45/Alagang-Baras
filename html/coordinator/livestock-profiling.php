<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alagang Baras - Livestock Management System</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../../assets/styles/components/sidebar.css" />
    <link
      rel="stylesheet"
      href="../../assets/styles/layouts/moa/livestock-profiling.css"
    />
  </head>
  <body>
    <div class="overlay" id="overlay"></div>

    <button class="hamburger-btn" id="hamburgerBtn">
      <i class="fas fa-bars"></i>
    </button>

     <div class="sidebar" id="sidebar">
      <div class="logo">
        <h4>Alagang Baras</h4>
        <p>Livestock Management System</p>
      </div>

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="./dashboard.php" class="nav-link active">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </div>

        <div class="nav-item">
          <a href="./owner-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Owner Management
          </a>
        </div>

        <div class="nav-item">
          <a href="./livestock-health-monitoring.php" class="nav-link">
            <i class="fas fa-tachometer-alt"></i>
            Health Monitoring
          </a>
        </div>

        <div class="nav-item">
          <a href="./livestock-profiling-list.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Livestock Profiling
          </a>
        </div>

        <div class="nav-item">
          <a href="./reports.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Reports
          </a>
        </div>

        <!-- <div class="nav-item">
          <a href="./qr.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            QR Code
          </a>
        </div> -->
        <div class="nav-item">
          <a href="../index.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Log out
          </a>
        </div>
      </nav>
    </div>

    <!-- Main Container -->
    <div class="main-container">
      <!-- Content Area -->
      <main class="content">
        <h2 class="page-title fade-in mt-5">
          <i class="fas fa-user me-3"></i>Add Livestock
        </h2>

        <!-- Success/Error Message -->
        <?php if (!empty($ownerSuccess)): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($ownerSuccess) ?>
        </div>
        <?php elseif (!empty($ownerError)): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($ownerError) ?>
        </div>
        <?php endif; ?>

        <form action="./add_animals.php" method="POST" class="fade-in">
          <div class="row">
            <div class="col-md-12 form-row position-relative">
              <label for="ownerName" class="form-label">Owner Name:</label>
              <input
                type="search"
                class="form-control"
                id="ownerName"
                name="ownerName"
                placeholder="Search owner..."
                autocomplete="off"
                required
              />
              <ul
                id="ownerDropdown"
                class="list-group position-absolute w-100"
                style="z-index: 1000; display: none"
              ></ul>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-row">
              <label for="species" class="form-label">Species:</label>
              <select id="species" class="form-control" name="species">
                <option disabled selected>-- Please Select --</option>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
                <option value="rabbit">Rabbit</option>
                <option value="hamster">Hamster</option>
              </select>
            </div>
            <div class="col-md-6 form-row">
              <label for="breed" class="form-label">Breed:</label>
              <input
                type="text"
                class="form-control"
                id="breed"
                name="breed"
                required
              />
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-row">
              <label for="sex" class="form-label">Sex:</label>
              <select class="form-control" id="sex" name="sex">
                <option disabled selected>-- Please Select --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-md-6 form-row">
              <label for="age" class="form-label">Date of Birth:</label>
              <input
                type="date"
                class="form-control"
                id="age"
                name="age"
                required
              />
            </div>
          </div>

          <div class="btn-container mt-3">
            <button
              type="submit"
              name="add_owner"
              class="btn btn-custom btn-save"
            >
              Add Livestock
            </button>
          </div>
        </form>
      </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="../../assets/scripts/moa-livestock-profiling.js"></script> -->
    <script src="../../assets/scripts/sidebar.js"></script>
    <script>
      // Example: list of owners as objects
      const owners = [
        { id: 1, name: "Juan Dela Cruz"},
        { id: 2, name: "Maria Santos"},
        { id: 3, name: "Pedro Reyes"},
        { id: 4, name: "Jose Rizal"},
        { id: 5, name: "Ana Dela Torre"},
      ];

      const ownerInput = document.getElementById("ownerName");
      const dropdown = document.getElementById("ownerDropdown");

      ownerInput.addEventListener("input", function () {
        const query = this.value.toLowerCase();
        dropdown.innerHTML = "";

        if (!query) {
          dropdown.style.display = "none";
          return;
        }

        // Filter by owner name
        const filtered = owners.filter((owner) =>
          owner.name.toLowerCase().includes(query)
        );

        // Show results
        if (filtered.length > 0) {
          filtered.forEach((owner) => {
            const li = document.createElement("li");
            li.classList.add("list-group-item", "list-group-item-action");

            // You can customize the display
            li.innerHTML = `
          <div>${owner.name}</div>
        `;

            // When clicked — fill input with owner name
            li.addEventListener("click", function () {
              ownerInput.value = owner.name;
              dropdown.style.display = "none";

              // Optional: You can access the full owner object here
              console.log("Selected owner:", owner);
            });

            dropdown.appendChild(li);
          });
          dropdown.style.display = "block";
        } else {
          dropdown.style.display = "none";
        }
      });

      // Hide dropdown when clicking outside
      document.addEventListener("click", function (event) {
        if (
          !ownerInput.contains(event.target) &&
          !dropdown.contains(event.target)
        ) {
          dropdown.style.display = "none";
        }
      });
    </script>
  </body>
</html>
